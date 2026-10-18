<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\PertemuanModel;
use App\Models\PesertaKelasModel;
use App\Models\PresensiModel;

class PresensiDosenController extends BaseController
{
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
