<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\KelasKuliahModel;
use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;
use App\Models\PresensiModel;
use App\Models\ProgramStudiModel;
use App\Models\TahunAkademikModel;

class AdminController extends BaseController
{
    public function index()
    {
        $db = db_connect();

        // Distribusi status presensi
        $statusStat = $db->table('presensi')
            ->select('status, COUNT(*) as jml')
            ->groupBy('status')
            ->get()->getResultArray();

        $statusMap = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
        foreach ($statusStat as $s) {
            $statusMap[$s['status']] = (int) $s['jml'];
        }

        // Sesi pertemuan aktif saat ini
        $sesiAktif = $db->table('pertemuan')
            ->select('pertemuan.*, kelas_kuliah.nama_kelas, mata_kuliah.nama_mk, dosen.nama_lengkap as nama_dosen')
            ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
            ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
            ->join('dosen', 'dosen.id = kelas_kuliah.dosen_id', 'left')
            ->where('pertemuan.status', 'open')
            ->get()->getResultArray();

        // Rekap per prodi untuk visualisasi
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

        $data = [
            'title'           => 'Dashboard Admin',
            'totalProdi'      => model(ProgramStudiModel::class)->countAllResults(),
            'totalDosen'      => model(DosenModel::class)->countAllResults(),
            'totalMahasiswa'  => model(MahasiswaModel::class)->countAllResults(),
            'totalMk'         => model(MataKuliahModel::class)->countAllResults(),
            'totalKelas'      => model(KelasKuliahModel::class)->countAllResults(),
            'taAktif'         => model(TahunAkademikModel::class)->getAktif(),
            'totalPresensi'   => model(PresensiModel::class)->countAllResults(),
            'statusMap'       => $statusMap,
            'sesiAktif'       => $sesiAktif,
            'prodiStat'       => $prodiStat,
            'pengaturan'      => pengaturan_kampus(),
            'presensiTerbaru' => $db->table('presensi')
                ->select('presensi.*, mahasiswa.nim, mahasiswa.nama_lengkap, pertemuan.pertemuan_ke, mata_kuliah.nama_mk')
                ->join('mahasiswa', 'mahasiswa.id = presensi.mahasiswa_id')
                ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
                ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
                ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
                ->orderBy('presensi.waktu_presensi', 'DESC')
                ->limit(8)
                ->get()->getResultArray(),
        ];

        return view('admin/dashboard', $data);
    }
}
