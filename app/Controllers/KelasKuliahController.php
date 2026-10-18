<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\KelasKuliahModel;
use App\Models\MataKuliahModel;
use App\Models\TahunAkademikModel;

class KelasKuliahController extends BaseController
{
    public function index()
    {
        $taModel = model(TahunAkademikModel::class);
        $taAktif = $taModel->getAktif();

        $hasTaParam = $this->request->getGet('tahun_akademik_id') !== null;
        $taId       = (int) $this->request->getGet('tahun_akademik_id');
        if (! $hasTaParam && $taAktif !== null) {
            $taId = (int) $taAktif['id'];
        }

        $q = trim((string) $this->request->getGet('q'));

        $where = $taId > 0 ? ['kelas_kuliah.tahun_akademik_id' => $taId] : [];

        $data = [
            'title'    => 'Jadwal Kelas Kuliah',
            'rows'     => model(KelasKuliahModel::class)->getKelasRelasi($where, null, 0, $q),
            'taList'   => $taModel->orderBy('id', 'DESC')->findAll(),
            'taId'     => $taId,
            'q'        => $q,
        ];

        return view('admin/kelas_kuliah/index', $data);
    }

    public function create()
    {
        return view('admin/kelas_kuliah/form', $this->formData(null));
    }

    public function store()
    {
        $model = model(KelasKuliahModel::class);
        $data  = $this->request->getPost([
            'tahun_akademik_id', 'mata_kuliah_id', 'dosen_id', 'nama_kelas', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan',
        ]);

        if ($data['dosen_id'] === '' || $data['dosen_id'] === null) {
            $data['dosen_id'] = null;
        }

        if (! $model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/kelas-kuliah')->with('success', 'Kelas kuliah berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(KelasKuliahModel::class)->find($id);
        if ($row === null) {
            return redirect()->to('/admin/kelas-kuliah')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/kelas_kuliah/form', $this->formData($row));
    }

    public function update(int $id)
    {
        $model = model(KelasKuliahModel::class);
        $data  = $this->request->getPost([
            'tahun_akademik_id', 'mata_kuliah_id', 'dosen_id', 'nama_kelas', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan',
        ]);

        if ($data['dosen_id'] === '' || $data['dosen_id'] === null) {
            $data['dosen_id'] = null;
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/kelas-kuliah')->with('success', 'Kelas kuliah berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        model(KelasKuliahModel::class)->delete($id);

        return redirect()->to('/admin/kelas-kuliah')->with('success', 'Kelas kuliah berhasil dihapus.');
    }

    private function formData(?array $row): array
    {
        $taAktif = model(TahunAkademikModel::class)->getAktif();

        return [
            'title'  => $row === null ? 'Tambah Kelas Kuliah' : 'Edit Kelas Kuliah',
            'row'    => $row,
            'taList' => model(TahunAkademikModel::class)->orderBy('id', 'DESC')->findAll(),
            'mkList' => model(MataKuliahModel::class)->orderBy('nama_mk', 'ASC')->findAll(),
            'dosenList' => model(DosenModel::class)->orderBy('nama_lengkap', 'ASC')->findAll(),
            'taAktifId' => $taAktif !== null ? (int) $taAktif['id'] : null,
        ];
    }
}
