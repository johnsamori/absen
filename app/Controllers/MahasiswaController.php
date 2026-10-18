<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;
use App\Models\UserModel;

class MahasiswaController extends BaseController
{
    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));

        $model = model(MahasiswaModel::class)
            ->select('mahasiswa.*, program_studi.nama_prodi, users.username, users.is_active')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->join('users', 'users.id = mahasiswa.user_id');

        if ($q !== '') {
            $model->groupStart()
                ->like('mahasiswa.nim', $q)
                ->orLike('mahasiswa.nama_lengkap', $q)
                ->orLike('mahasiswa.angkatan', $q)
                ->orLike('mahasiswa.kelas_paralel', $q)
                ->orLike('program_studi.nama_prodi', $q)
                ->groupEnd();
        }

        $data = [
            'title' => 'Data Mahasiswa',
            'rows'  => $model->orderBy('mahasiswa.nim', 'ASC')->findAll(),
            'q'     => $q,
        ];

        return view('admin/mahasiswa/index', $data);
    }

    public function create()
    {
        return view('admin/mahasiswa/form', [
            'title' => 'Tambah Mahasiswa',
            'row'   => null,
            'prodi' => model(ProgramStudiModel::class)->orderBy('nama_prodi', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $post = $this->request->getPost([
            'nim', 'nama_lengkap', 'prodi_id', 'angkatan', 'kelas_paralel', 'no_hp', 'password',
        ]);

        $username = (string) $this->request->getPost('username');
        if ($username === '') {
            $username = $post['nim'];
        }

        $password = $post['password'] !== '' ? $post['password'] : 'mhs123';
        unset($post['password']);

        $mhsModel  = model(MahasiswaModel::class);
        $userModel = model(UserModel::class);

        if ($userModel->where('username', $username)->first() !== null) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        $db = db_connect();
        $db->transBegin();

        $userId = $userModel->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email'         => $username . '@student.uncen.ac.id',
            'role'          => 'mahasiswa',
            'is_active'     => 1,
        ]);

        $post['user_id'] = $userId;
        $mhsModel->insert($post);

        if ($db->transStatus() === false) {
            $db->transRollback();

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data mahasiswa.');
        }

        $db->transCommit();

        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(MahasiswaModel::class)
            ->select('mahasiswa.*, users.username, users.is_active')
            ->join('users', 'users.id = mahasiswa.user_id')
            ->where('mahasiswa.id', $id)
            ->first();

        if ($row === null) {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/mahasiswa/form', [
            'title' => 'Edit Mahasiswa',
            'row'   => $row,
            'prodi' => model(ProgramStudiModel::class)->orderBy('nama_prodi', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $mhsModel = model(MahasiswaModel::class);
        $row = $mhsModel->find($id);

        if ($row === null) {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        $post = $this->request->getPost([
            'nim', 'nama_lengkap', 'prodi_id', 'angkatan', 'kelas_paralel', 'no_hp',
        ]);

        $userModel = model(UserModel::class);

        $username = (string) $this->request->getPost('username');
        if ($username !== '') {
            $existing = $userModel->where('username', $username)->where('id !=', (int) $row['user_id'])->first();
            if ($existing !== null) {
                return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
            }
            $userModel->update((int) $row['user_id'], ['id' => (int) $row['user_id'], 'username' => $username]);
        }

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $userModel->update((int) $row['user_id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        $isActive = $this->request->getPost('is_active') === null ? 0 : 1;
        $userModel->update((int) $row['user_id'], ['is_active' => $isActive]);

        $post['id'] = $id; // untuk placeholder {id} pada validasi is_unique
        $mhsModel->update($id, $post);

        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $row = model(MahasiswaModel::class)->find($id);
        if ($row !== null) {
            model(UserModel::class)->delete((int) $row['user_id']);
        }

        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
