<?php

namespace App\Controllers;

use App\Models\PertemuanModel;
use App\Models\PesertaKelasModel;
use App\Models\PresensiModel;

class IzinController extends BaseController
{
    /**
     * Daftar pengajuan izin/sakit mahasiswa.
     */
    public function index()
    {
        $mhsId = (int) session()->get('profile_id');

        $db = db_connect();

        $rows = $db->table('presensi')
            ->select('presensi.*, pertemuan.pertemuan_ke, pertemuan.tanggal, mata_kuliah.kode_mk, mata_kuliah.nama_mk')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->join('kelas_kuliah', 'kelas_kuliah.id = pertemuan.kelas_kuliah_id')
            ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
            ->where('presensi.mahasiswa_id', $mhsId)
            ->whereIn('presensi.status', ['Izin', 'Sakit'])
            ->orderBy('presensi.waktu_presensi', 'DESC')
            ->get()->getResultArray();

        return view('mahasiswa/izin/index', [
            'title' => 'Pengajuan Izin / Sakit',
            'rows'  => $rows,
        ]);
    }

    /**
     * Form pengajuan izin/sakit.
     */
    public function ajukan()
    {
        $mhsId = (int) session()->get('profile_id');
        $kelasList = model(PesertaKelasModel::class)->getKelasMahasiswa($mhsId);

        $pertemuanList = [];
        if ($kelasList !== []) {
            $kelasIds = array_column($kelasList, 'id');
            $pertemuanList = model(PertemuanModel::class)
                ->whereIn('kelas_kuliah_id', $kelasIds)
                ->orderBy('tanggal', 'DESC')
                ->orderBy('pertemuan_ke', 'DESC')
                ->findAll();
        }

        return view('mahasiswa/izin/form', [
            'title'          => 'Ajukan Izin / Sakit',
            'kelasList'      => $kelasList,
            'pertemuanList'  => $pertemuanList,
        ]);
    }

    /**
     * Simpan pengajuan izin/sakit.
     */
    public function store()
    {
        $mhsId       = (int) session()->get('profile_id');
        $pertemuanId = (int) $this->request->getPost('pertemuan_id');
        $jenis       = (string) $this->request->getPost('jenis');
        $alasan      = (string) $this->request->getPost('alasan');

        if (! in_array($jenis, ['Izin', 'Sakit'], true)) {
            return redirect()->back()->withInput()->with('error', 'Jenis pengajuan tidak valid.');
        }

        $pertemuan = model(PertemuanModel::class)->find($pertemuanId);
        if ($pertemuan === null) {
            return redirect()->back()->withInput()->with('error', 'Pertemuan tidak ditemukan.');
        }

        // Pastikan pertemuan milik kelas yang diikuti mahasiswa.
        $peserta = model(PesertaKelasModel::class)
            ->where('kelas_kuliah_id', (int) $pertemuan['kelas_kuliah_id'])
            ->where('mahasiswa_id', $mhsId)
            ->first();

        if ($peserta === null) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak terdaftar pada kelas pertemuan ini.');
        }

        // Upload bukti dokumen (foto surat dokter/keterangan).
        $buktiPath = null;
        $file = $this->request->getFile('bukti');
        if ($file !== null && $file->isValid() && ! $file->hasMoved()) {
            if (! in_array($file->getClientExtension(), ['jpg', 'jpeg', 'png', 'pdf'], true)) {
                return redirect()->back()->withInput()->with('error', 'Bukti harus berupa JPG, PNG, atau PDF.');
            }

            $namaBaru = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/bukti', $namaBaru);
            $buktiPath = 'uploads/bukti/' . $namaBaru;
        }

        $presensiModel = model(PresensiModel::class);
        $existing = $presensiModel->findByPertemuanMahasiswa($pertemuanId, $mhsId);

        $data = [
            'pertemuan_id'       => $pertemuanId,
            'mahasiswa_id'       => $mhsId,
            'waktu_presensi'     => date('Y-m-d H:i:s'),
            'status'             => $jenis,
            'metode_absen'       => 'manual',
            'catatan_keterangan' => $alasan,
            'bukti_dokumen'      => $buktiPath,
        ];

        if ($existing === null) {
            $presensiModel->insert($data);
        } else {
            $presensiModel->update((int) $existing['id'], $data);
        }

        return redirect()->to('/mahasiswa/izin')->with('success', 'Pengajuan ' . strtolower($jenis) . ' berhasil dikirim dan menunggu verifikasi dosen.');
    }
}
