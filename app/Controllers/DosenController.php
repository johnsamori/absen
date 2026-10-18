<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\ProgramStudiModel;
use App\Models\UserModel;

class DosenController extends BaseController
{
    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));

        $model = model(DosenModel::class)
            ->select('dosen.*, program_studi.nama_prodi, users.username, users.is_active')
            ->join('program_studi', 'program_studi.id = dosen.prodi_id', 'left')
            ->join('users', 'users.id = dosen.user_id');

        if ($q !== '') {
            $model->groupStart()
                ->like('dosen.nip', $q)
                ->orLike('dosen.nidn', $q)
                ->orLike('dosen.nama_lengkap', $q)
                ->orLike('dosen.gelar_depan', $q)
                ->orLike('dosen.gelar_belakang', $q)
                ->orLike('program_studi.nama_prodi', $q)
                ->groupEnd();
        }

        $data = [
            'title' => 'Data Dosen',
            'rows'  => $model->orderBy('dosen.nama_lengkap', 'ASC')->findAll(),
            'q'     => $q,
        ];

        return view('admin/dosen/index', $data);
    }

    public function create()
    {
        return view('admin/dosen/form', [
            'title' => 'Tambah Dosen',
            'row'   => null,
            'prodi' => model(ProgramStudiModel::class)->orderBy('nama_prodi', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $post = $this->request->getPost([
            'nip', 'nidn', 'nama_lengkap', 'gelar_depan', 'gelar_belakang', 'prodi_id', 'no_hp', 'password',
        ]);

        $username = (string) $this->request->getPost('username');
        if ($username === '') {
            $username = $post['nip'];
        }

        $password = $post['password'] !== '' ? $post['password'] : 'dosen123';
        unset($post['password']);

        $dosenModel = model(DosenModel::class);
        $userModel  = model(UserModel::class);

        // Validasi username unik.
        if ($userModel->where('username', $username)->first() !== null) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        $db = db_connect();
        $db->transBegin();

        $userId = $userModel->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email'         => $username . '@fisip.uncen.ac.id',
            'role'          => 'dosen',
            'is_active'     => 1,
        ]);

        $post['user_id'] = $userId;
        $dosenModel->insert($post);

        if ($db->transStatus() === false) {
            $db->transRollback();

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data dosen.');
        }

        $db->transCommit();

        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(DosenModel::class)
            ->select('dosen.*, users.username, users.is_active')
            ->join('users', 'users.id = dosen.user_id')
            ->where('dosen.id', $id)
            ->first();

        if ($row === null) {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/dosen/form', [
            'title' => 'Edit Dosen',
            'row'   => $row,
            'prodi' => model(ProgramStudiModel::class)->orderBy('nama_prodi', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $dosenModel = model(DosenModel::class);
        $row = $dosenModel->find($id);

        if ($row === null) {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        $post = $this->request->getPost([
            'nip', 'nidn', 'nama_lengkap', 'gelar_depan', 'gelar_belakang', 'prodi_id', 'no_hp',
        ]);

        $userModel = model(UserModel::class);

        // Perbarui username bila berubah.
        $username = (string) $this->request->getPost('username');
        if ($username !== '') {
            $existing = $userModel->where('username', $username)->where('id !=', (int) $row['user_id'])->first();
            if ($existing !== null) {
                return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
            }
            $userModel->update((int) $row['user_id'], ['id' => (int) $row['user_id'], 'username' => $username]);
        }

        // Reset password bila diisi.
        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $userModel->update((int) $row['user_id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        $isActive = $this->request->getPost('is_active') === null ? 0 : 1;
        $userModel->update((int) $row['user_id'], ['is_active' => $isActive]);

        $post['id'] = $id; // untuk placeholder {id} pada validasi is_unique
        $dosenModel->update($id, $post);

        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $row = model(DosenModel::class)->find($id);
        if ($row !== null) {
            model(UserModel::class)->delete((int) $row['user_id']);
        }

        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil dihapus.');
    }
}
