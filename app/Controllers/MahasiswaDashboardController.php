<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\PresensiModel;
use App\Models\TahunAkademikModel;

class MahasiswaDashboardController extends BaseController
{
    public function index()
    {
        $mhsId = (int) session()->get('profile_id');

        $mhs = model(MahasiswaModel::class)
            ->select('mahasiswa.*, program_studi.nama_prodi')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->where('mahasiswa.id', $mhsId)
            ->first();

        $kelasList = model(PesertaKelasModel::class)->getKelasMahasiswa($mhsId);

        $db = db_connect();
        $presensiModel = model(PresensiModel::class);

        $kelas = [];
        $totalHadir = 0;
        $totalIzin  = 0;
        $totalSakit = 0;
        $totalAlpa  = 0;
        $totalPertemuan = 0;

        $pengaturan = pengaturan_kampus();
        $syaratPersen = (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75);
        $kelasMemenuhi = 0;
        $kelasPeringatan = 0;

        foreach ($kelasList as $k) {
            $jmlPertemuan = $db->table('pertemuan')->where('kelas_kuliah_id', $k['id'])->countAllResults();
            $rekap = $presensiModel->rekapPerKelas((int) $k['id'], $mhsId);

            $k['jml_pertemuan'] = $jmlPertemuan;
            $k['hadir']         = $rekap['total']['Hadir'];
            $k['izin']          = $rekap['total']['Izin'];
            $k['sakit']         = $rekap['total']['Sakit'];
            $k['alpa']          = $rekap['total']['Alpa'];
            $k['persen_hadir']  = $rekap['persen_hadir'];

            $totalHadir += (int) $k['hadir'];
            $totalIzin  += (int) $k['izin'];
            $totalSakit += (int) $k['sakit'];
            $totalAlpa  += (int) $k['alpa'];
            $totalPertemuan += $jmlPertemuan;

            if ($jmlPertemuan > 0) {
                if ($k['persen_hadir'] >= $syaratPersen) {
                    $kelasMemenuhi++;
                } else {
                    $kelasPeringatan++;
                }
            } else {
                $kelasMemenuhi++;
            }

            $kelas[] = $k;
        }

        $totalRecord = $totalHadir + $totalIzin + $totalSakit + $totalAlpa;
        $persenKeseluruhan = $totalRecord > 0 ? round(($totalHadir / $totalRecord) * 100, 1) : 100;

        // Cek apakah ada sesi presensi yang sedang OPEN untuk kelas yang diambil mahasiswa
        $kelasIds = array_column($kelasList, 'id');
        $sesiAktif = [];
        if (!empty($kelasIds)) {
            $sesiAktif = $db->table('pertemuan')
                ->select('pertemuan.*, kelas_kuliah.nama_kelas, mata_kuliah.nama_mk, mata_kuliah.kode_mk, dosen.nama_lengkap as nama_dosen')
                ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
                ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
                ->join('dosen', 'dosen.id = kelas_kuliah.dosen_id', 'left')
                ->whereIn('pertemuan.kelas_kuliah_id', $kelasIds)
                ->where('pertemuan.status', 'open')
                ->get()->getResultArray();
        }

        // Riwayat presensi terbaru mahasiswa
        $riwayatTerbaru = $db->table('presensi')
            ->select('presensi.*, pertemuan.pertemuan_ke, mata_kuliah.nama_mk, mata_kuliah.kode_mk, kelas_kuliah.nama_kelas')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
            ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
            ->where('presensi.mahasiswa_id', $mhsId)
            ->orderBy('presensi.waktu_presensi', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $hariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][(int) date('w')];
        $jadwalHariIni = array_values(array_filter($kelas, fn ($k) => $k['hari'] === $hariIni));

        $taAktif = model(TahunAkademikModel::class)->getAktif();

        return view('mahasiswa/dashboard', [
            'title'             => 'Dashboard Mahasiswa',
            'mhs'               => $mhs,
            'kelas'             => $kelas,
            'jadwalHariIni'     => $jadwalHariIni,
            'hariIni'           => $hariIni,
            'taAktif'           => $taAktif,
            'pengaturan'        => $pengaturan,
            'totalHadir'        => $totalHadir,
            'totalIzin'         => $totalIzin,
            'totalSakit'        => $totalSakit,
            'totalAlpa'         => $totalAlpa,
            'totalPertemuan'    => $totalPertemuan,
            'persenKeseluruhan' => $persenKeseluruhan,
            'kelasMemenuhi'     => $kelasMemenuhi,
            'kelasPeringatan'   => $kelasPeringatan,
            'sesiAktif'         => $sesiAktif,
            'riwayatTerbaru'    => $riwayatTerbaru,
        ]);
    }
}
