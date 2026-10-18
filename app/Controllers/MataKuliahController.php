<?php

namespace App\Controllers;

use App\Models\MataKuliahModel;
use App\Models\ProgramStudiModel;

class MataKuliahController extends BaseController
{
    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));

        $model = model(MataKuliahModel::class)
            ->select('mata_kuliah.*, program_studi.nama_prodi')
            ->join('program_studi', 'program_studi.id = mata_kuliah.prodi_id', 'left');

        if ($q !== '') {
            $model->groupStart()
                ->like('mata_kuliah.kode_mk', $q)
                ->orLike('mata_kuliah.nama_mk', $q)
                ->orLike('mata_kuliah.sks', $q)
                ->orLike('mata_kuliah.semester_paket', $q)
                ->orLike('program_studi.nama_prodi', $q)
                ->groupEnd();
        }

        $data = [
            'title' => 'Mata Kuliah',
            'rows'  => $model->orderBy('mata_kuliah.kode_mk', 'ASC')->findAll(),
            'q'     => $q,
        ];

        return view('admin/mata_kuliah/index', $data);
    }

    public function create()
    {
        return view('admin/mata_kuliah/form', [
            'title' => 'Tambah Mata Kuliah',
            'row'   => null,
            'prodi' => model(ProgramStudiModel::class)->orderBy('nama_prodi', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $model = model(MataKuliahModel::class);
        $data  = $this->request->getPost(['kode_mk', 'nama_mk', 'sks', 'prodi_id', 'semester_paket']);

        if (! $model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/mata-kuliah')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(MataKuliahModel::class)->find($id);
        if ($row === null) {
            return redirect()->to('/admin/mata-kuliah')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/mata_kuliah/form', [
            'title' => 'Edit Mata Kuliah',
            'row'   => $row,
            'prodi' => model(ProgramStudiModel::class)->orderBy('nama_prodi', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $model = model(MataKuliahModel::class);
        $data  = $this->request->getPost(['kode_mk', 'nama_mk', 'sks', 'prodi_id', 'semester_paket']);
        $data['id'] = $id; // untuk placeholder {id} pada validasi is_unique

        if (! $model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/mata-kuliah')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        model(MataKuliahModel::class)->delete($id);

        return redirect()->to('/admin/mata-kuliah')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
