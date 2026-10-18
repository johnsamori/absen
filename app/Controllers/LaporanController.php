<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\PertemuanModel;
use App\Models\PesertaKelasModel;
use App\Models\PresensiModel;
use App\Models\TahunAkademikModel;

class LaporanController extends BaseController
{
    public function index()
    {
        $taAktif = model(TahunAkademikModel::class)->getAktif();
        $kelas   = model(KelasKuliahModel::class)->getKelasRelasi();

        return view('laporan/index', [
            'title'   => 'Laporan & Rekapitulasi',
            'kelas'   => $kelas,
            'taAktif' => $taAktif,
        ]);
    }

    /**
     * Cetak Berita Acara Perkuliahan (BAP) — tampilan cetak.
     */
    public function bap(int $pertemuanId)
    {
        $pertemuan = model(PertemuanModel::class)->find($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/laporan')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $kelas = model(KelasKuliahModel::class)->getKelasRelasi(['kelas_kuliah.id' => (int) $pertemuan['kelas_kuliah_id']]);
        if ($kelas === []) {
            return redirect()->to('/laporan')->with('error', 'Kelas tidak ditemukan.');
        }

        $peserta = model(PesertaKelasModel::class)->getPeserta((int) $pertemuan['kelas_kuliah_id']);
        $presensi = model(PresensiModel::class)->findByPertemuan($pertemuanId);

        $statusMap = [];
        foreach ($presensi as $p) {
            $statusMap[(int) $p['mahasiswa_id']] = $p;
        }

        return view('laporan/bap', [
            'title'     => 'Berita Acara Perkuliahan',
            'kelas'     => $kelas[0],
            'pertemuan' => $pertemuan,
            'peserta'   => $peserta,
            'statusMap' => $statusMap,
        ]);
    }

    /**
     * Rekapitulasi presensi per kelas (matriks mahasiswa x pertemuan).
     */
    public function rekap(int $kelasKuliahId)
    {
        $kelas = model(KelasKuliahModel::class)->getKelasRelasi(['kelas_kuliah.id' => $kelasKuliahId]);
        if ($kelas === []) {
            return redirect()->to('/laporan')->with('error', 'Kelas tidak ditemukan.');
        }

        $peserta   = model(PesertaKelasModel::class)->getPeserta($kelasKuliahId);
        $pertemuan = model(PertemuanModel::class)->findByKelas($kelasKuliahId);

        $db = db_connect();
        $allPresensi = $db->table('presensi')
            ->select('presensi.status, presensi.mahasiswa_id, presensi.pertemuan_id')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->where('pertemuan.kelas_kuliah_id', $kelasKuliahId)
            ->get()->getResultArray();

        // Peta [mahasiswa_id][pertemuan_id] => status
        $map = [];
        foreach ($allPresensi as $p) {
            $map[(int) $p['mahasiswa_id']][(int) $p['pertemuan_id']] = $p['status'];
        }

        $matriks = [];
        foreach ($peserta as $m) {
            $row = ['mahasiswa' => $m, 'cell' => [], 'hadir' => 0, 'jumlah' => count($pertemuan)];
            foreach ($pertemuan as $pt) {
                $st = $map[(int) $m['id']][(int) $pt['id']] ?? '—';
                $row['cell'][(int) $pt['id']] = $st;
                if ($st === 'Hadir') {
                    $row['hadir']++;
                }
            }
            $row['persen'] = $row['jumlah'] > 0 ? round(($row['hadir'] / $row['jumlah']) * 100, 1) : 0;
            $matriks[] = $row;
        }

        return view('laporan/rekap', [
            'title'     => 'Rekapitulasi Presensi',
            'kelas'     => $kelas[0],
            'pertemuan' => $pertemuan,
            'matriks'   => $matriks,
        ]);
    }

    /**
     * Export rekap presensi kelas ke CSV.
     */
    public function exportCsv(int $kelasKuliahId)
    {
        $kelas = model(KelasKuliahModel::class)->getKelasRelasi(['kelas_kuliah.id' => $kelasKuliahId]);
        if ($kelas === []) {
            return redirect()->to('/laporan')->with('error', 'Kelas tidak ditemukan.');
        }

        $peserta   = model(PesertaKelasModel::class)->getPeserta($kelasKuliahId);
        $pertemuan = model(PertemuanModel::class)->findByKelas($kelasKuliahId);

        $db = db_connect();
        $allPresensi = $db->table('presensi')
            ->select('presensi.status, presensi.mahasiswa_id, presensi.pertemuan_id')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->where('pertemuan.kelas_kuliah_id', $kelasKuliahId)
            ->get()->getResultArray();

        $map = [];
        foreach ($allPresensi as $p) {
            $map[(int) $p['mahasiswa_id']][(int) $p['pertemuan_id']] = $p['status'];
        }

        $rows = [];
        foreach ($peserta as $m) {
            $line = [
                'nim'    => $m['nim'],
                'nama'   => $m['nama_lengkap'],
                'hadir'  => 0,
            ];
            foreach ($pertemuan as $pt) {
                $st = $map[(int) $m['id']][(int) $pt['id']] ?? '—';
                $line['P' . $pt['pertemuan_ke']] = $st;
                if ($st === 'Hadir') {
                    $line['hadir']++;
                }
            }
            $line['persen'] = count($pertemuan) > 0 ? round(($line['hadir'] / count($pertemuan)) * 100, 1) : 0;
            $rows[] = $line;
        }

        $filename = 'rekap_' . $kelas[0]['kode_mk'] . '_' . $kelas[0]['nama_kelas'] . '.csv';

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM UTF-8

        $header = ['NIM', 'Nama'];
        foreach ($pertemuan as $pt) {
            $header[] = 'Pertemuan ' . $pt['pertemuan_ke'];
        }
        $header[] = 'Hadir';
        $header[] = 'Persentase';
        fputcsv($output, $header);

        foreach ($rows as $r) {
            $line = [$r['nim'], $r['nama']];
            foreach ($pertemuan as $pt) {
                $line[] = $r['P' . $pt['pertemuan_ke']];
            }
            $line[] = $r['hadir'];
            $line[] = $r['persen'] . '%';
            fputcsv($output, $line);
        }
        fclose($output);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
