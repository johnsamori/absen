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
        $jamBukaPost = (string) $this->request->getPost('jam_buka');
        $jamTutupPost= (string) $this->request->getPost('jam_tutup');

        $tglVal = $tanggal !== '' ? $tanggal : date('Y-m-d');

        // Cegah duplikat nomor pertemuan.
        $duplikat = $pertemuanModel->where('kelas_kuliah_id', $kelasKuliahId)->where('pertemuan_ke', $pertemuanKe)->first();
        if ($duplikat !== null) {
            return redirect()->back()->withInput()->with('error', 'Pertemuan ke-' . $pertemuanKe . ' sudah pernah dibuat.');
        }

        // Hitung jam_buka dan jam_tutup
        if ($jamBukaPost !== '') {
            $jamBuka = date('Y-m-d H:i:s', strtotime($tglVal . ' ' . $jamBukaPost));
        } else {
            $jamBuka = date('Y-m-d H:i:s');
        }

        if ($jamTutupPost !== '') {
            $jamTutup = date('Y-m-d H:i:s', strtotime($tglVal . ' ' . $jamTutupPost));
        } else {
            $jamTutup = date('Y-m-d H:i:s', strtotime($jamBuka) + (30 * 60)); // Default 30 menit
        }

        $salt  = bin2hex(random_bytes(32));
        $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $pertemuanModel->insert([
            'kelas_kuliah_id' => $kelasKuliahId,
            'pertemuan_ke'    => $pertemuanKe,
            'tanggal'         => $tglVal,
            'jam_buka'        => $jamBuka,
            'jam_tutup'       => $jamTutup,
            'materi_kuliah'   => $materi,
            'qr_secret_salt'  => $salt,
            'token_cadangan'  => $token,
            'status'          => 'open',
        ]);

        $pertemuanId = $pertemuanModel->getInsertID();

        return redirect()->to('/dosen/pertemuan/' . $pertemuanId . '/scan')
            ->with('success', 'Sesi Pertemuan Ke-' . $pertemuanKe . ' dibuka! Waktu presensi aktif s/d ' . date('H:i', strtotime($jamTutup)) . ' WIT. Kamera siap digunakan.');
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

        $tanggal      = (string) $this->request->getPost('tanggal');
        $materi       = (string) $this->request->getPost('materi_kuliah');
        $jamBukaPost  = (string) $this->request->getPost('jam_buka');
        $jamTutupPost = (string) $this->request->getPost('jam_tutup');

        $tglVal = $tanggal !== '' ? $tanggal : ($pertemuan['pertemuan']['tanggal'] ?? date('Y-m-d'));

        $updateData = [
            'materi_kuliah' => $materi,
            'tanggal'       => $tglVal,
        ];

        if ($jamBukaPost !== '') {
            $updateData['jam_buka'] = date('Y-m-d H:i:s', strtotime($tglVal . ' ' . $jamBukaPost));
        }
        if ($jamTutupPost !== '') {
            $updateData['jam_tutup'] = date('Y-m-d H:i:s', strtotime($tglVal . ' ' . $jamTutupPost));
        }

        model(PertemuanModel::class)->update($pertemuanId, $updateData);

        return redirect()->to('/dosen/pertemuan/' . (int) $pertemuan['pertemuan']['kelas_kuliah_id'])
            ->with('success', 'Berita acara & pengaturan waktu diperbarui.');
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

        $now = time();
        $model = model(PertemuanModel::class);
        $model->update($pertemuanId, [
            'status'          => 'open',
            'jam_buka'        => date('Y-m-d H:i:s', $now),
            'jam_tutup'       => date('Y-m-d H:i:s', $now + (30 * 60)), // 30 menit tambahan
            'qr_secret_salt'  => bin2hex(random_bytes(32)),
            'token_cadangan'  => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
        ]);

        return redirect()->to('/dosen/pertemuan/' . $pertemuanId . '/scan')
            ->with('success', 'Sesi dibuka kembali (aktif 30 menit). Kamera siap digunakan.');
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
