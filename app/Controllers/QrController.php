<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\PertemuanModel;
use App\Models\PresensiModel;

class QrController extends BaseController
{
    /**
     * Halaman proyektor QR Code dinamis (refresh tiap 20 detik).
     */
    public function proyeksi(int $kelasKuliahId, int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan atau bukan milik Anda.');
        }

        $tokenModel = model(PertemuanModel::class);
        $token      = $tokenModel->generateToken($pertemuan['pertemuan'], time());
        $scanUrl    = site_url('presensi/scan/' . $pertemuanId . '/' . $token);

        return view('dosen/pertemuan/proyeksi', [
            'title'     => 'Proyeksi QR — ' . $pertemuan['kelas']['nama_mk'],
            'kelas'     => $pertemuan['kelas'],
            'pertemuan' => $pertemuan['pertemuan'],
            'scanUrl'   => $scanUrl,
        ]);
    }

    /**
     * API token dinamis untuk proyektor. Mengembalikan token HMAC baru per interval.
     */
    public function token(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak.']);
        }

        if ($pertemuan['pertemuan']['status'] !== 'open') {
            return $this->response->setJSON(['error' => 'Pertemuan sudah ditutup.']);
        }

        $now     = time();
        $interval = (int) floor($now / 20);
        $sisaDetik = 20 - ($now % 20);

        $token = model(PertemuanModel::class)->generateToken($pertemuan['pertemuan'], $now);
        $url   = site_url('presensi/scan/' . $pertemuanId . '/' . $token);

        return $this->response->setJSON([
            'token'            => $token,
            'url'              => $url,
            'interval'         => $interval,
            'sisa_detik'       => $sisaDetik,
            'token_cadangan'   => $pertemuan['pertemuan']['token_cadangan'],
            'status'           => $pertemuan['pertemuan']['status'],
            'csrf_hash'        => csrf_hash(),
        ]);
    }

    /**
     * API live counter kehadiran untuk proyektor.
     */
    public function live(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak.']);
        }

        $db      = db_connect();
        $kelasId = (int) $pertemuan['pertemuan']['kelas_kuliah_id'];

        $totalPeserta = $db->table('peserta_kelas')->where('kelas_kuliah_id', $kelasId)->countAllResults();
        $rekap        = model(PresensiModel::class)->rekapPertemuan($pertemuanId);

        $terakhir = array_slice($rekap['rows'], -8);
        $terakhir = array_reverse($terakhir);

        return $this->response->setJSON([
            'total_peserta' => $totalPeserta,
            'total'         => $rekap['total'],
            'terakhir'      => $terakhir,
            'status'        => $pertemuan['pertemuan']['status'],
            'csrf_hash'     => csrf_hash(),
        ]);
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
