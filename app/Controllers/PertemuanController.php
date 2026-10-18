<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\PertemuanModel;
use App\Models\PresensiModel;

class PertemuanController extends BaseController
{
    public function index(int $kelasKuliahId)
    {
        $kelas = $this->ambilKelasMilik($kelasKuliahId);
        if ($kelas === null) {
            return redirect()->to('/dosen')->with('error', 'Kelas tidak ditemukan atau bukan milik Anda.');
        }

        $pertemuan = model(PertemuanModel::class)->findByKelas($kelasKuliahId);

        $data = [
            'title'     => 'Pertemuan — ' . $kelas['nama_mk'],
            'kelas'     => $kelas,
            'pertemuan' => $pertemuan,
        ];

        return view('dosen/pertemuan/index', $data);
    }

    public function buka(int $kelasKuliahId)
    {
        $kelas = $this->ambilKelasMilik($kelasKuliahId);
        if ($kelas === null) {
            return redirect()->to('/dosen')->with('error', 'Kelas tidak ditemukan atau bukan milik Anda.');
        }

        $pertemuanModel = model(PertemuanModel::class);
        $existing       = $pertemuanModel->findByKelas($kelasKuliahId);
        $next           = count($existing) + 1;

        return view('dosen/pertemuan/buka', [
            'title'     => 'Buka Sesi Pertemuan',
            'kelas'     => $kelas,
            'pertemuan_ke' => $next,
            'tanggal'   => date('Y-m-d'),
        ]);
    }

    public function store(int $kelasKuliahId)
    {
        $kelas = $this->ambilKelasMilik($kelasKuliahId);
        if ($kelas === null) {
            return redirect()->to('/dosen')->with('error', 'Kelas tidak ditemukan atau bukan milik Anda.');
        }

        $pertemuanModel = model(PertemuanModel::class);

        $pertemuanKe = (int) $this->request->getPost('pertemuan_ke');
        $materi      = (string) $this->request->getPost('materi_kuliah');
        $tanggal     = (string) $this->request->getPost('tanggal');

        // Cegah duplikat nomor pertemuan.
        $duplikat = $pertemuanModel->where('kelas_kuliah_id', $kelasKuliahId)->where('pertemuan_ke', $pertemuanKe)->first();
        if ($duplikat !== null) {
            return redirect()->back()->withInput()->with('error', 'Pertemuan ke-' . $pertemuanKe . ' sudah pernah dibuat.');
        }

        $salt  = bin2hex(random_bytes(32));
        $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $pertemuanModel->insert([
            'kelas_kuliah_id' => $kelasKuliahId,
            'pertemuan_ke'    => $pertemuanKe,
            'tanggal'         => $tanggal !== '' ? $tanggal : date('Y-m-d'),
            'jam_buka'        => date('Y-m-d H:i:s'),
            'jam_tutup'       => null,
            'materi_kuliah'   => $materi,
            'qr_secret_salt'  => $salt,
            'token_cadangan'  => $token,
            'status'          => 'open',
        ]);

        $pertemuanId = $pertemuanModel->getInsertID();

        return redirect()->to('/dosen/pertemuan/' . $kelasKuliahId . '/qr/' . $pertemuanId)
            ->with('success', 'Sesi pertemuan dibuka. QR Code dinamis siap ditampilkan.');
    }

    public function edit(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan.');
        }

        return view('dosen/pertemuan/edit', [
            'title'     => 'Edit Berita Acara Pertemuan',
            'kelas'     => $pertemuan['kelas'],
            'pertemuan' => $pertemuan['pertemuan'],
        ]);
    }

    public function update(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $model = model(PertemuanModel::class);
        $model->update($pertemuanId, [
            'materi_kuliah' => (string) $this->request->getPost('materi_kuliah'),
            'tanggal'       => (string) $this->request->getPost('tanggal'),
        ]);

        return redirect()->to('/dosen/pertemuan/' . (int) $pertemuan['pertemuan']['kelas_kuliah_id'])
            ->with('success', 'Berita acara diperbarui.');
    }

    public function tutup(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan.');
        }

        model(PertemuanModel::class)->update($pertemuanId, [
            'status'    => 'closed',
            'jam_tutup' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dosen/pertemuan/' . (int) $pertemuan['pertemuan']['kelas_kuliah_id'])
            ->with('success', 'Sesi pertemuan ditutup.');
    }

    public function bukaLagi(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $model = model(PertemuanModel::class);
        $model->update($pertemuanId, [
            'status'          => 'open',
            'jam_buka'        => date('Y-m-d H:i:s'),
            'jam_tutup'       => null,
            'qr_secret_salt'  => bin2hex(random_bytes(32)),
            'token_cadangan'  => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
        ]);

        return redirect()->to('/dosen/pertemuan/' . (int) $pertemuan['pertemuan']['kelas_kuliah_id'] . '/qr/' . $pertemuanId)
            ->with('success', 'Sesi dibuka kembali dengan QR baru.');
    }

    public function rekap(int $pertemuanId)
    {
        $pertemuan = $this->ambilPertemuanMilik($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->to('/dosen')->with('error', 'Pertemuan tidak ditemukan.');
        }

        $rekap = model(PresensiModel::class)->rekapPertemuan($pertemuanId);

        return view('dosen/pertemuan/rekap', [
            'title'     => 'Rekap Presensi Pertemuan',
            'kelas'     => $pertemuan['kelas'],
            'pertemuan' => $pertemuan['pertemuan'],
            'rekap'     => $rekap,
        ]);
    }

    /**
     * Ambil kelas yang diampu dosen yang sedang login.
     */
    private function ambilKelasMilik(int $kelasKuliahId): ?array
    {
        $dosenId = (int) session()->get('profile_id');

        $kelas = model(KelasKuliahModel::class)->getKelasRelasi([
            'kelas_kuliah.id'       => $kelasKuliahId,
            'kelas_kuliah.dosen_id' => $dosenId,
        ]);

        return $kelas[0] ?? null;
    }

    /**
     * Ambil pertemuan beserta kelas, pastikan milik dosen yang login.
     */
    private function ambilPertemuanMilik(int $pertemuanId): ?array
    {
        $pertemuan = model(PertemuanModel::class)->find($pertemuanId);
        if ($pertemuan === null) {
            return null;
        }

        $kelas = $this->ambilKelasMilik((int) $pertemuan['kelas_kuliah_id']);
        if ($kelas === null) {
            return null;
        }

        return ['pertemuan' => $pertemuan, 'kelas' => $kelas];
    }
}
