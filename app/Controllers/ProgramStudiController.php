<?php

namespace App\Controllers;

use App\Models\ProgramStudiModel;

class ProgramStudiController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Program Studi',
            'rows'  => model(ProgramStudiModel::class)->orderBy('id', 'ASC')->findAll(),
        ];

        return view('admin/program_studi/index', $data);
    }

    public function create()
    {
        return view('admin/program_studi/form', [
            'title' => 'Tambah Program Studi',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $model = model(ProgramStudiModel::class);
        $data  = $this->request->getPost(['kode_prodi', 'nama_prodi', 'jenjang', 'kaprodi']);

        if (! $model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/program-studi')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(ProgramStudiModel::class)->find($id);
        if ($row === null) {
            return redirect()->to('/admin/program-studi')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/program_studi/form', [
            'title' => 'Edit Program Studi',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        $model = model(ProgramStudiModel::class);
        $data  = $this->request->getPost(['kode_prodi', 'nama_prodi', 'jenjang', 'kaprodi']);
        $data['id'] = $id; // untuk placeholder {id} pada validasi is_unique

        if (! $model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/program-studi')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        model(ProgramStudiModel::class)->delete($id);

        return redirect()->to('/admin/program-studi')->with('success', 'Program studi berhasil dihapus.');
    }
}
