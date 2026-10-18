<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\MahasiswaModel;
use App\Models\PertemuanModel;
use App\Models\PesertaKelasModel;
use App\Models\PresensiModel;

class PresensiDosenController extends BaseController
{
    /**
     * Halaman Scanner Kamera Presensi di kelas (Kamera HP / Laptop Dosen).
     */
    public function scanner(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan atau bukan milik Anda.');
        }

        $kelasId     = (int) $pertemuan['pertemuan']['kelas_kuliah_id'];
        $peserta     = model(PesertaKelasModel::class)->getPeserta($kelasId);
        $presensi    = model(PresensiModel::class)->findByPertemuan($pertemuanId);

        $statusMap = [];
        $hadirCount = 0;
        foreach ($presensi as $p) {
            $statusMap[(int) $p['mahasiswa_id']] = $p;
            if ($p['status'] === 'Hadir') {
                $hadirCount++;
            }
        }

        $now = time();
        $jamTutupTs = !empty($pertemuan['pertemuan']['jam_tutup']) ? strtotime($pertemuan['pertemuan']['jam_tutup']) : null;
        $sisaDetik = $jamTutupTs !== null ? max(0, $jamTutupTs - $now) : 1800; // default 30 menit jika null
        $isExpired = $jamTutupTs !== null && $now > $jamTutupTs;

        $totalPeserta = count($peserta);
        $persenHadir = $totalPeserta > 0 ? round(($hadirCount / $totalPeserta) * 100, 1) : 0;

        return view('dosen/pertemuan/scan', [
            'title'        => 'Scan Presensi di Kelas — P' . $pertemuan['pertemuan']['pertemuan_ke'],
            'kelas'        => $pertemuan['kelas'],
            'pertemuan'    => $pertemuan['pertemuan'],
            'peserta'      => $peserta,
            'statusMap'    => $statusMap,
            'hadirCount'   => $hadirCount,
            'totalPeserta' => $totalPeserta,
            'persenHadir'  => $persenHadir,
            'sisaDetik'    => $sisaDetik,
            'isExpired'    => $isExpired,
        ]);
    }

    /**
     * AJAX endpoint: Dosen scan QR / input NIM mahasiswa di kelas.
     * Request JSON / POST: { qr_data: '...' } atau { nim: '...' }
     */
    public function scanMahasiswa(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Akses ditolak atau sesi tidak ditemukan.']);
        }

        if ($pertemuan['pertemuan']['status'] !== 'open') {
            return $this->response->setStatusCode(422)->setJSON([
                'success'   => false,
                'message'   => 'Sesi presensi pertemuan ini sudah berstatus ditutup (Closed). Silakan buka kembali terlebih dahulu.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        // Cek apakah waktu absen sudah habis
        $now = time();
        $jamTutup = $pertemuan['pertemuan']['jam_tutup'];
        if (!empty($jamTutup) && $now > strtotime($jamTutup)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success'   => false,
                'expired'   => true,
                'message'   => 'Batas waktu presensi telah berakhir pada pukul ' . date('H:i', strtotime($jamTutup)) . ' WIT. Silakan perpanjang waktu jika ingin melanjutkan presensi.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $input = $this->request->getJSON(true) ?? [];
        $raw = (string) ($input['qr_data'] ?? $input['nim'] ?? $this->request->getPost('qr_data') ?? $this->request->getPost('nim') ?? '');
        $raw = trim($raw);

        if ($raw === '') {
            return $this->response->setStatusCode(422)->setJSON([
                'success'   => false,
                'message'   => 'Data scan QR / NIM tidak terbaca.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        // Parse QR payload (dukung: "MHS:2026...", JSON {"nim":"2026..."}, atau "2026...")
        $nim = $raw;
        if (str_starts_with($raw, '{') && str_ends_with($raw, '}')) {
            $parsed = json_decode($raw, true);
            if (is_array($parsed) && !empty($parsed['nim'])) {
                $nim = (string) $parsed['nim'];
            }
        } elseif (stripos($raw, 'MHS:') === 0) {
            $nim = substr($raw, 4);
        } elseif (stripos($raw, 'FISIP:') === 0) {
            $nim = substr($raw, 6);
        }
        $nim = trim($nim, " \t\n\r\0\x0B\"'");

        // Cari data mahasiswa
        $mhsModel = model(MahasiswaModel::class);
        $mhs = $mhsModel->select('mahasiswa.*, program_studi.nama_prodi')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->where('mahasiswa.nim', $nim)
            ->first();

        // Jika tidak ditemukan dengan NIM, coba dengan ID jika numerik
        if ($mhs === null && ctype_digit($nim)) {
            $mhs = $mhsModel->select('mahasiswa.*, program_studi.nama_prodi')
                ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
                ->where('mahasiswa.id', (int) $nim)
                ->first();
        }

        if ($mhs === null) {
            return $this->response->setStatusCode(404)->setJSON([
                'success'   => false,
                'message'   => 'Mahasiswa dengan NIM "' . esc($nim) . '" tidak terdaftar di sistem.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $mhsId   = (int) $mhs['id'];
        $kelasId = (int) $pertemuan['pertemuan']['kelas_kuliah_id'];

        // Cek apakah mahasiswa terdaftar sebagai peserta kelas ini
        $pesertaModel = model(PesertaKelasModel::class);
        $isPeserta = $pesertaModel->where('kelas_kuliah_id', $kelasId)->where('mahasiswa_id', $mhsId)->first();
        if ($isPeserta === null) {
            return $this->response->setStatusCode(422)->setJSON([
                'success'   => false,
                'not_enrolled' => true,
                'message'   => 'Mahasiswa ' . esc($mhs['nama_lengkap']) . ' (NIM: ' . esc($mhs['nim']) . ') bukan peserta kelas ini!',
                'mahasiswa' => [
                    'id'           => $mhs['id'],
                    'nim'          => $mhs['nim'],
                    'nama_lengkap' => $mhs['nama_lengkap'],
                    'prodi'        => $mhs['nama_prodi'] ?? 'FISIP',
                ],
                'csrf_hash' => csrf_hash(),
            ]);
        }

        // Cek apakah sudah pernah absen Hadir
        $presensiModel = model(PresensiModel::class);
        $existing = $presensiModel->findByPertemuanMahasiswa($pertemuanId, $mhsId);
        if ($existing !== null && $existing['status'] === 'Hadir') {
            $waktuFmt = !empty($existing['waktu_presensi']) ? date('H:i:s', strtotime($existing['waktu_presensi'])) : '-';
            return $this->response->setStatusCode(200)->setJSON([
                'success'   => false,
                'duplicate' => true,
                'message'   => 'Mahasiswa ' . esc($mhs['nama_lengkap']) . ' sudah tercatat HADIR sebelumnya pada pukul ' . $waktuFmt . ' WIT.',
                'mahasiswa' => [
                    'id'           => $mhs['id'],
                    'nim'          => $mhs['nim'],
                    'nama_lengkap' => $mhs['nama_lengkap'],
                    'prodi'        => $mhs['nama_prodi'] ?? 'FISIP',
                ],
                'waktu'     => $waktuFmt,
                'csrf_hash' => csrf_hash(),
            ]);
        }

        // Simpan / update kehadiran Hadir
        $waktuNow = date('Y-m-d H:i:s');
        $dataPresensi = [
            'pertemuan_id'       => $pertemuanId,
            'mahasiswa_id'       => $mhsId,
            'waktu_presensi'     => $waktuNow,
            'status'             => 'Hadir',
            'metode_absen'       => 'qr',
            'catatan_keterangan' => 'Discan kamera dosen di kelas',
        ];

        if ($existing === null) {
            $presensiModel->insert($dataPresensi);
        } else {
            $presensiModel->update((int) $existing['id'], $dataPresensi);
        }

        // Hitung ulang statistik realtime
        $totalPeserta = $pesertaModel->where('kelas_kuliah_id', $kelasId)->countAllResults();
        $db = db_connect();
        $hadirCount   = $db->table('presensi')->where('pertemuan_id', $pertemuanId)->where('status', 'Hadir')->countAllResults();
        $persenHadir  = $totalPeserta > 0 ? round(($hadirCount / $totalPeserta) * 100, 1) : 0;

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Presensi BERHASIL! ' . esc($mhs['nama_lengkap']),
            'mahasiswa' => [
                'id'            => $mhs['id'],
                'nim'           => $mhs['nim'],
                'nama_lengkap'  => $mhs['nama_lengkap'],
                'prodi'         => $mhs['nama_prodi'] ?? 'FISIP',
                'angkatan'      => $mhs['angkatan'] ?? '-',
                'kelas_paralel' => $mhs['kelas_paralel'] ?? '-',
            ],
            'waktu'     => date('H:i:s', strtotime($waktuNow)),
            'stats'     => [
                'total_peserta' => $totalPeserta,
                'hadir_count'   => $hadirCount,
                'persen'        => $persenHadir,
            ],
            'csrf_hash' => csrf_hash(),
        ]);
    }

    /**
     * AJAX endpoint: Perpanjang waktu presensi dosen.
     */
    public function perpanjangWaktu(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $input = $this->request->getJSON(true) ?? [];
        $menit = (int) ($input['tambah_menit'] ?? $this->request->getPost('tambah_menit') ?? 15);
        if ($menit <= 0) {
            $menit = 15;
        }

        $now = time();
        $curTutup = !empty($pertemuan['pertemuan']['jam_tutup']) ? strtotime($pertemuan['pertemuan']['jam_tutup']) : 0;
        $baseTime = max($now, $curTutup);
        $newTutup = date('Y-m-d H:i:s', $baseTime + ($menit * 60));

        model(PertemuanModel::class)->update($pertemuanId, [
            'status'    => 'open',
            'jam_tutup' => $newTutup,
        ]);

        $sisaDetik = max(0, strtotime($newTutup) - $now);

        return $this->response->setJSON([
            'success'       => true,
            'message'       => 'Waktu presensi berhasil diperpanjang +' . $menit . ' menit (sampai ' . date('H:i', strtotime($newTutup)) . ' WIT).',
            'jam_tutup'     => $newTutup,
            'jam_tutup_fmt' => date('H:i', strtotime($newTutup)),
            'sisa_detik'    => $sisaDetik,
            'csrf_hash'     => csrf_hash(),
        ]);
    }

    /**
     * Halaman kelola presensi: roster peserta + ubah status manual + verifikasi izin/sakit.
     */
    public function kelola(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan atau bukan milik Anda.');
        }

        $kelasId  = (int) $pertemuan['pertemuan']['kelas_kuliah_id'];
        $peserta  = model(PesertaKelasModel::class)->getPeserta($kelasId);
        $presensi = model(PresensiModel::class)->findByPertemuan($pertemuanId);

        $statusMap = [];
        foreach ($presensi as $p) {
            $statusMap[(int) $p['mahasiswa_id']] = $p;
        }

        $izinSakit = array_filter($presensi, fn ($p) => in_array($p['status'], ['Izin', 'Sakit'], true));

        return view('dosen/pertemuan/kelola', [
            'title'      => 'Kelola Presensi',
            'kelas'      => $pertemuan['kelas'],
            'pertemuan'  => $pertemuan['pertemuan'],
            'peserta'    => $peserta,
            'statusMap'  => $statusMap,
            'izinSakit'  => $izinSakit,
        ]);
    }

    /**
     * Set status presensi mahasiswa secara manual (Hadir/Izin/Sakit/Alpa).
     */
    public function setStatus(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $mhsId  = (int) $this->request->getPost('mahasiswa_id');
        $status = (string) $this->request->getPost('status');

        if (! in_array($status, ['Hadir', 'Izin', 'Sakit', 'Alpa'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $presensiModel = model(PresensiModel::class);
        $existing = $presensiModel->findByPertemuanMahasiswa($pertemuanId, $mhsId);

        $data = [
            'pertemuan_id'   => $pertemuanId,
            'mahasiswa_id'   => $mhsId,
            'status'         => $status,
            'metode_absen'   => 'manual',
            'waktu_presensi' => date('Y-m-d H:i:s'),
        ];

        if ($existing === null) {
            $presensiModel->insert($data);
        } else {
            $presensiModel->update((int) $existing['id'], $data);
        }

        return redirect()->back()->with('success', 'Status presensi diperbarui.');
    }

    /**
     * Setujui pengajuan izin/sakit (status tetap, beri catatan verifikasi).
     */
    public function setujuiIzin(int $presensiId)
    {
        return $this->prosesVerifikasi($presensiId, false);
    }

    /**
     * Tolak pengajuan izin/sakit (ubah jadi Alpa).
     */
    public function tolakIzin(int $presensiId)
    {
        return $this->prosesVerifikasi($presensiId, true);
    }

    private function prosesVerifikasi(int $presensiId, bool $tolak): \CodeIgniter\HTTP\RedirectResponse
    {
        $presensiModel = model(PresensiModel::class);
        $presensi = $presensiModel->find($presensiId);

        if ($presensi === null) {
            return redirect()->to('/dosen')->with('error', 'Data presensi tidak ditemukan.');
        }

        // Pastikan pemilik pertemuan adalah dosen yang login.
        if ($this->ambilPertemuanMilik((int) $presensi['pertemuan_id']) === null) {
            return redirect()->to('/dosen')->with('error', 'Akses ditolak.');
        }

        if ($tolak) {
            $presensiModel->update($presensiId, [
                'status'              => 'Alpa',
                'catatan_keterangan'  => trim(($presensi['catatan_keterangan'] ?? '') . ' [Ditolak dosen pengampu]'),
            ]);

            return redirect()->back()->with('success', 'Pengajuan izin/sakit ditolak (status diubah Alpa).');
        }

        $presensiModel->update($presensiId, [
            'catatan_keterangan' => trim(($presensi['catatan_keterangan'] ?? '') . ' [Disetujui dosen pengampu]'),
        ]);

        return redirect()->back()->with('success', 'Pengajuan izin/sakit disetujui.');
    }

    private function ambilPertemuanMilik(int $pertemuanId): ?array
    {
        $pertemuan = model(PertemuanModel::class)->find($pertemuanId);
        if ($pertemuan === null) {
            return null;
        }

        $dosenId = (int) session()->get('profile_id');

        $kelas = model(KelasKuliahModel::class)->getKelasRelasi([
            'kelas_kuliah.id'       => (int) $pertemuan['kelas_kuliah_id'],
            'kelas_kuliah.dosen_id' => $dosenId,
        ]);

        if ($kelas === []) {
            return null;
        }

        return ['pertemuan' => $pertemuan, 'kelas' => $kelas[0]];
    }
}
