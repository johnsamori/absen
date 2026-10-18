<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\KelasKuliahModel;
use App\Models\MahasiswaModel;
use App\Models\PresensiModel;

class PimpinanController extends BaseController
{
    public function index()
    {
        $db = db_connect();

        // Statistik umum.
        $totalMahasiswa = model(MahasiswaModel::class)->countAllResults();
        $totalDosen     = model(DosenModel::class)->countAllResults();
        $totalKelas     = model(KelasKuliahModel::class)->countAllResults();
        $totalPresensi  = model(PresensiModel::class)->countAllResults();

        // Persentase kehadiran per program studi.
        $prodiStat = $db->table('presensi')
            ->select('program_studi.nama_prodi,
                      COUNT(presensi.id) as total,
                      SUM(CASE WHEN presensi.status = "Hadir" THEN 1 ELSE 0 END) as hadir')
            ->join('mahasiswa', 'mahasiswa.id = presensi.mahasiswa_id')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->groupBy('program_studi.nama_prodi')
            ->orderBy('program_studi.nama_prodi', 'ASC')
            ->get()->getResultArray();

        foreach ($prodiStat as &$p) {
            $p['persen'] = (int) $p['total'] > 0 ? round(((int) $p['hadir'] / (int) $p['total']) * 100, 1) : 0;
        }
        unset($p);

        // Ketercapaian tatap muka dosen (jumlah pertemuan per dosen).
        $dosenStat = $db->table('pertemuan')
            ->select('dosen.nama_lengkap, COUNT(pertemuan.id) as jml_pertemuan')
            ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
            ->join('dosen', 'dosen.id = kelas_kuliah.dosen_id')
            ->groupBy('dosen.nama_lengkap')
            ->orderBy('jml_pertemuan', 'DESC')
            ->get()->getResultArray();

        // Distribusi status presensi.
        $statusStat = $db->table('presensi')
            ->select('status, COUNT(*) as jml')
            ->groupBy('status')
            ->get()->getResultArray();

        $statusMap = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
        foreach ($statusStat as $s) {
            $statusMap[$s['status']] = (int) $s['jml'];
        }

        $taAktif = model(\App\Models\TahunAkademikModel::class)->getAktif();
        $presensiHariIni = $db->table('presensi')
            ->where('DATE(waktu_presensi)', date('Y-m-d'))
            ->countAllResults();
        $sesiAktifCount = $db->table('pertemuan')
            ->where('status', 'open')
            ->countAllResults();

        return view('pimpinan/dashboard', [
            'title'           => 'Dashboard Pimpinan',
            'taAktif'         => $taAktif,
            'totalMahasiswa'  => $totalMahasiswa,
            'totalDosen'      => $totalDosen,
            'totalKelas'      => $totalKelas,
            'totalPresensi'   => $totalPresensi,
            'presensiHariIni' => $presensiHariIni,
            'sesiAktifCount'  => $sesiAktifCount,
            'prodiStat'       => $prodiStat,
            'dosenStat'       => $dosenStat,
            'statusMap'       => $statusMap,
        ]);
    }
}
