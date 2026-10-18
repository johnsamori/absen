<?php

namespace App\Controllers;

use App\Models\TahunAkademikModel;

class TahunAkademikController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Tahun Akademik',
            'rows'  => model(TahunAkademikModel::class)->orderBy('id', 'DESC')->findAll(),
        ];

        return view('admin/tahun_akademik/index', $data);
    }

    public function create()
    {
        return view('admin/tahun_akademik/form', ['title' => 'Tambah Tahun Akademik', 'row' => null]);
    }

    public function store()
    {
        $model = model(TahunAkademikModel::class);
        $data  = $this->request->getPost(['tahun', 'semester', 'is_active']);

        if ($data['is_active'] === null) {
            $data['is_active'] = 0;
        }

        if (! $model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        if ((int) $data['is_active'] === 1) {
            $model->setAktif($model->getInsertID());
        }

        return redirect()->to('/admin/tahun-akademik')->with('success', 'Tahun akademik berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(TahunAkademikModel::class)->find($id);
        if ($row === null) {
            return redirect()->to('/admin/tahun-akademik')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/tahun_akademik/form', ['title' => 'Edit Tahun Akademik', 'row' => $row]);
    }

    public function update(int $id)
    {
        $model = model(TahunAkademikModel::class);
        $data  = $this->request->getPost(['tahun', 'semester', 'is_active']);

        if ($data['is_active'] === null) {
            $data['is_active'] = 0;
        }

        if (! $model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        if ((int) $data['is_active'] === 1) {
            $model->setAktif($id);
        }

        return redirect()->to('/admin/tahun-akademik')->with('success', 'Tahun akademik berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        model(TahunAkademikModel::class)->delete($id);

        return redirect()->to('/admin/tahun-akademik')->with('success', 'Tahun akademik berhasil dihapus.');
    }
}
