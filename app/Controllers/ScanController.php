<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\PertemuanModel;

class ScanController extends BaseController
{
    /**
     * Halaman scanner QR (kamera) + form token cadangan.
     */
    public function index()
    {
        return view('mahasiswa/scan', [
            'title' => 'Scan QR Presensi',
        ]);
    }

    /**
     * Halaman landing hasil scan QR (dari URL yang ter-encode di QR).
     * Menangkap GPS lalu otomatis submit presensi.
     */
    public function scan(int $pertemuanId, string $token)
    {
        $pertemuanModel = model(PertemuanModel::class);
        $pertemuan = $pertemuanModel->find($pertemuanId);

        if ($pertemuan === null) {
            return view('mahasiswa/scan_result', [
                'title'   => 'Presensi Gagal',
                'success' => false,
                'message' => 'Pertemuan tidak ditemukan.',
            ]);
        }

        $kelas = model(KelasKuliahModel::class)->getKelasRelasi(['kelas_kuliah.id' => (int) $pertemuan['kelas_kuliah_id']]);

        return view('mahasiswa/scan_result', [
            'title'     => 'Konfirmasi Presensi',
            'success'   => null, // belum diproses, akan di-submit via JS
            'pertemuan' => $pertemuan,
            'kelas'     => $kelas[0] ?? null,
            'token'     => $token,
        ]);
    }
}
