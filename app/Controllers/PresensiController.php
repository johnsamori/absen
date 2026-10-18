<?php

namespace App\Controllers;

use App\Models\PertemuanModel;
use App\Models\PesertaKelasModel;
use App\Models\PresensiModel;

class PresensiController extends BaseController
{
    /**
     * Submit presensi via QR (token dinamis + GPS).
     * Request: POST JSON {pertemuan_id, token, latitude, longitude}
     */
    public function submit()
    {
        $input = $this->request->getJSON(true) ?? [];

        $pertemuanId = (int) ($input['pertemuan_id'] ?? $this->request->getPost('pertemuan_id') ?? 0);
        $token       = (string) ($input['token'] ?? $this->request->getPost('token') ?? '');
        $lat         = $this->floatInput($input, 'latitude');
        $lng         = $this->floatInput($input, 'longitude');

        return $this->prosesPresensi($pertemuanId, $token, $lat, $lng, 'qr');
    }

    /**
     * Submit presensi via token cadangan 6-digit.
     * Request: POST JSON {kode_token, latitude, longitude}
     */
    public function token()
    {
        $input = $this->request->getJSON(true) ?? [];

        $kodeToken = (string) ($input['kode_token'] ?? $this->request->getPost('kode_token') ?? '');
        $lat       = $this->floatInput($input, 'latitude');
        $lng       = $this->floatInput($input, 'longitude');

        if ($kodeToken === '' || strlen($kodeToken) !== 6) {
            return $this->jsonError('Kode token cadangan harus 6 digit.');
        }

        $pertemuan = model(PertemuanModel::class)
            ->where('token_cadangan', $kodeToken)
            ->where('status', 'open')
            ->orderBy('jam_buka', 'DESC')
            ->first();

        if ($pertemuan === null) {
            return $this->jsonError('Token cadangan tidak valid atau sesi sudah ditutup.');
        }

        return $this->prosesPresensi((int) $pertemuan['id'], null, $lat, $lng, 'token');
    }

    /**
     * Riwayat presensi mahasiswa yang sedang login.
     */
    public function riwayat()
    {
        $mhsId = (int) session()->get('profile_id');

        $db = db_connect();

        $rows = $db->table('presensi')
            ->select('presensi.*, pertemuan.pertemuan_ke, pertemuan.tanggal, pertemuan.materi_kuliah,
                      kelas_kuliah.nama_kelas, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai,
                      mata_kuliah.kode_mk, mata_kuliah.nama_mk')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
            ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
            ->where('presensi.mahasiswa_id', $mhsId)
            ->orderBy('presensi.waktu_presensi', 'DESC')
            ->get()->getResultArray();

        return view('mahasiswa/riwayat', [
            'title' => 'Riwayat Presensi',
            'rows'  => $rows,
        ]);
    }

    private function prosesPresensi(int $pertemuanId, ?string $token, ?float $lat, ?float $lng, string $metode)
    {
        if ($pertemuanId === 0) {
            return $this->jsonError('ID pertemuan tidak valid.');
        }

        if ($lat === null || $lng === null) {
            return $this->jsonError('Koordinat GPS tidak tersedia. Aktifkan izin lokasi perangkat Anda.');
        }

        $pertemuanModel = model(PertemuanModel::class);
        $pertemuan = $pertemuanModel->find($pertemuanId);

        if ($pertemuan === null) {
            return $this->jsonError('Pertemuan tidak ditemukan.');
        }

        if ($pertemuan['status'] !== 'open') {
            return $this->jsonError('Sesi presensi sudah ditutup.');
        }

        // Validasi token dinamis (khusus metode qr).
        if ($metode === 'qr') {
            if ($token === null || ! $pertemuanModel->validasiToken($pertemuan, $token)) {
                return $this->jsonError('Token QR tidak valid atau sudah kedaluwarsa.');
            }
        }

        $mhsId = (int) session()->get('profile_id');

        // Pastikan mahasiswa terdaftar sebagai peserta kelas.
        $peserta = model(PesertaKelasModel::class)
            ->where('kelas_kuliah_id', (int) $pertemuan['kelas_kuliah_id'])
            ->where('mahasiswa_id', $mhsId)
            ->first();

        if ($peserta === null) {
            return $this->jsonError('Anda tidak terdaftar sebagai peserta kelas ini.');
        }

        // Cegah presensi ganda yang sudah Hadir.
        $presensiModel = model(PresensiModel::class);
        $existing = $presensiModel->findByPertemuanMahasiswa($pertemuanId, $mhsId);
        if ($existing !== null && $existing['status'] === 'Hadir') {
            return $this->jsonError('Anda sudah tercatat hadir pada pertemuan ini.');
        }

        // Validasi geofencing (Haversine).
        $pengaturan = pengaturan_kampus();
        if ($pengaturan === null) {
            return $this->jsonError('Konfigurasi kampus belum diatur. Hubungi administrator.');
        }

        $jarak = haversine_meters(
            (float) $lat,
            (float) $lng,
            (float) $pengaturan['latitude_pusat'],
            (float) $pengaturan['longitude_pusat']
        );

        $radius = (int) $pengaturan['radius_toleransi_meter'];

        if ($jarak > $radius) {
            return $this->jsonError(
                sprintf('Anda berada di luar jangkauan kampus (jarak %.0f m, batas %d m).', $jarak, $radius),
                422
            );
        }

        $data = [
            'pertemuan_id'   => $pertemuanId,
            'mahasiswa_id'   => $mhsId,
            'waktu_presensi' => date('Y-m-d H:i:s'),
            'status'         => 'Hadir',
            'metode_absen'   => $metode,
            'latitude'       => $lat,
            'longitude'      => $lng,
            'jarak_meter'    => round($jarak, 2),
        ];

        if ($existing === null) {
            $presensiModel->insert($data);
        } else {
            $presensiModel->update((int) $existing['id'], $data);
        }

        return $this->response->setJSON([
            'success'     => true,
            'message'     => sprintf('Presensi berhasil! Jarak Anda %.0f meter dari kampus.', $jarak),
            'status'      => 'Hadir',
            'jarak_meter' => round($jarak, 2),
            'waktu'       => $data['waktu_presensi'],
            'csrf_hash'   => csrf_hash(),
        ]);
    }

    private function floatInput(array $input, string $key): ?float
    {
        $value = $input[$key] ?? $this->request->getPost($key) ?? null;
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function jsonError(string $message, int $status = 422)
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON([
                'success'   => false,
                'message'   => $message,
                'csrf_hash' => csrf_hash(),
            ]);
    }
}
