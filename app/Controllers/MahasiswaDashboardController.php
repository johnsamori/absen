<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\MahasiswaModel;
use App\Models\PertemuanModel;
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
            $k['persen_hadir']  = $jmlPertemuan > 0 ? round(($k['hadir'] / $jmlPertemuan) * 100, 1) : 100;

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
        $qrData = 'MHS:' . ($mhs['nim'] ?? '');

        return view('mahasiswa/dashboard', [
            'title'             => 'Dashboard Mahasiswa',
            'mhs'               => $mhs,
            'qrData'            => $qrData,
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

    /**
     * Halaman QR Code Presensi Mahasiswa (untuk discan dosen di kelas).
     */
    public function qr()
    {
        $mhsId = (int) session()->get('profile_id');
        $mhs = model(MahasiswaModel::class)
            ->select('mahasiswa.*, program_studi.nama_prodi')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->where('mahasiswa.id', $mhsId)
            ->first();

        $qrData = 'MHS:' . ($mhs['nim'] ?? '');

        return view('mahasiswa/my_qr', [
            'title'  => 'QR Code Presensi Saya',
            'mhs'    => $mhs,
            'qrData' => $qrData,
        ]);
    }

    /**
     * Cetak Kartu Presensi Perkuliahan Mahasiswa (Seluruh MK Semester Aktif).
     */
    public function kartuAbsensi(?int $mahasiswaId = null)
    {
        $role = session()->get('role');
        if ($role === 'mahasiswa') {
            $mhsId = (int) session()->get('profile_id');
        } else {
            $mhsId = $mahasiswaId ?? (int) $this->request->getGet('mhs_id');
        }

        if ($mhsId <= 0) {
            return redirect()->back()->with('error', 'Mahasiswa tidak valid.');
        }

        $mhs = model(MahasiswaModel::class)
            ->select('mahasiswa.*, program_studi.nama_prodi, program_studi.jenjang, program_studi.kaprodi')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->where('mahasiswa.id', $mhsId)
            ->first();

        if ($mhs === null) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $kelasList = model(PesertaKelasModel::class)->getKelasMahasiswa($mhsId);

        $db = db_connect();
        $presensiModel = model(PresensiModel::class);
        $pengaturan = pengaturan_kampus();
        $syaratPersen = (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75);

        $totalSks = 0;
        $totalHadir = 0;
        $totalTarget = 0;
        $kelasMemenuhi = 0;
        $kelasPeringatan = 0;

        $processedKelas = [];
        foreach ($kelasList as $k) {
            $jmlPertemuan = $db->table('pertemuan')->where('kelas_kuliah_id', $k['id'])->countAllResults();
            $rekap = $presensiModel->rekapPerKelas((int) $k['id'], $mhsId);

            $k['jml_pertemuan'] = $jmlPertemuan;
            $k['hadir']         = $rekap['total']['Hadir'];
            $k['izin']          = $rekap['total']['Izin'];
            $k['sakit']         = $rekap['total']['Sakit'];
            $k['alpa']          = $rekap['total']['Alpa'];
            $k['persen_hadir']  = $jmlPertemuan > 0 ? round(($k['hadir'] / $jmlPertemuan) * 100, 1) : 100;
            $k['eligible']      = $k['persen_hadir'] >= $syaratPersen;

            $totalSks   += (int) ($k['sks'] ?? 0);
            $totalHadir += (int) $k['hadir'];
            $totalTarget+= $jmlPertemuan;

            if ($jmlPertemuan > 0) {
                if ($k['eligible']) {
                    $kelasMemenuhi++;
                } else {
                    $kelasPeringatan++;
                }
            } else {
                $kelasMemenuhi++;
            }

            $processedKelas[] = $k;
        }

        $persenRerata = $totalTarget > 0 ? round(($totalHadir / $totalTarget) * 100, 1) : 100;
        $taAktif = model(TahunAkademikModel::class)->getAktif();
        $qrData = 'MHS:' . ($mhs['nim'] ?? '');

        return view('mahasiswa/kartu_absensi', [
            'title'           => 'Kartu Presensi Perkuliahan — ' . $mhs['nama_lengkap'],
            'mhs'             => $mhs,
            'kelasList'       => $processedKelas,
            'taAktif'         => $taAktif,
            'pengaturan'      => $pengaturan,
            'totalSks'        => $totalSks,
            'totalHadir'      => $totalHadir,
            'persenRerata'    => $persenRerata,
            'kelasMemenuhi'   => $kelasMemenuhi,
            'kelasPeringatan' => $kelasPeringatan,
            'qrData'          => $qrData,
        ]);
    }

    /**
     * Cetak Lembar Presensi Detail 1 Mata Kuliah (Pertemuan 1 - 16).
     */
    public function kartuAbsensiDetail(int $kelasKuliahId, ?int $mahasiswaId = null)
    {
        $role = session()->get('role');
        if ($role === 'mahasiswa') {
            $mhsId = (int) session()->get('profile_id');
        } else {
            $mhsId = $mahasiswaId ?? (int) $this->request->getGet('mhs_id');
        }

        if ($mhsId <= 0) {
            return redirect()->back()->with('error', 'Mahasiswa tidak valid.');
        }

        $mhs = model(MahasiswaModel::class)
            ->select('mahasiswa.*, program_studi.nama_prodi, program_studi.jenjang, program_studi.kaprodi')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->where('mahasiswa.id', $mhsId)
            ->first();

        if ($mhs === null) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $kelasRel = model(KelasKuliahModel::class)->getKelasRelasi(['kelas_kuliah.id' => $kelasKuliahId]);
        if ($kelasRel === []) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }
        $kelas = $kelasRel[0];

        $pertemuan = model(PertemuanModel::class)->findByKelas($kelasKuliahId);

        $db = db_connect();
        $presensiList = $db->table('presensi')
            ->select('presensi.*, pertemuan.pertemuan_ke, pertemuan.tanggal as tgl_pertemuan, pertemuan.materi_kuliah')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->where('pertemuan.kelas_kuliah_id', $kelasKuliahId)
            ->where('presensi.mahasiswa_id', $mhsId)
            ->get()->getResultArray();

        $presensiMap = [];
        $totalHadir = 0;
        $totalIzin  = 0;
        $totalSakit = 0;
        $totalAlpa  = 0;

        foreach ($presensiList as $p) {
            $presensiMap[(int) $p['pertemuan_id']] = $p;
            if ($p['status'] === 'Hadir') $totalHadir++;
            elseif ($p['status'] === 'Izin') $totalIzin++;
            elseif ($p['status'] === 'Sakit') $totalSakit++;
            elseif ($p['status'] === 'Alpa') $totalAlpa++;
        }

        $jmlPertemuan = count($pertemuan);
        $pengaturan = pengaturan_kampus();
        $syaratPersen = (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75);
        $persenHadir = $jmlPertemuan > 0 ? round(($totalHadir / $jmlPertemuan) * 100, 1) : 100;
        $isEligible = $persenHadir >= $syaratPersen;

        $qrData = 'MHS:' . ($mhs['nim'] ?? '');

        return view('mahasiswa/kartu_absensi_detail', [
            'title'        => 'Lembar Presensi — ' . $kelas['nama_mk'],
            'mhs'          => $mhs,
            'kelas'        => $kelas,
            'pertemuan'    => $pertemuan,
            'presensiMap'  => $presensiMap,
            'jmlPertemuan' => $jmlPertemuan,
            'totalHadir'   => $totalHadir,
            'totalIzin'    => $totalIzin,
            'totalSakit'   => $totalSakit,
            'totalAlpa'    => $totalAlpa,
            'persenHadir'  => $persenHadir,
            'isEligible'   => $isEligible,
            'pengaturan'   => $pengaturan,
            'qrData'       => $qrData,
        ]);
    }
}
