<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

/**
 * Manajemen user (akun login) — khusus Super Admin.
 */
class UserController extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $rows = $db->table('users')
            ->select('users.*, dosen.nama_lengkap as dosen_nama, dosen.nip,
                      mahasiswa.nama_lengkap as mhs_nama, mahasiswa.nim')
            ->join('dosen', 'dosen.user_id = users.id', 'left')
            ->join('mahasiswa', 'mahasiswa.user_id = users.id', 'left')
            ->orderBy('users.id', 'ASC')
            ->get()->getResultArray();

        $me = (int) session()->get('user_id');

        return view('admin/users/index', [
            'title' => 'Manajemen User',
            'rows'  => $rows,
            'me'    => $me,
        ]);
    }

    public function create()
    {
        return view('admin/users/form', [
            'title' => 'Tambah User',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $model = model(UserModel::class);

        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');
        $email    = (string) $this->request->getPost('email');
        $role     = (string) $this->request->getPost('role');

        if ($username === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        if (! in_array($role, ['admin', 'dosen', 'mahasiswa', 'pimpinan'], true)) {
            return redirect()->back()->withInput()->with('error', 'Role tidak valid.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
        }

        if ($model->where('username', $username)->first() !== null) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        $model->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email'         => $email !== '' ? $email : null,
            'role'          => $role,
            'is_active'     => $this->request->getPost('is_active') === null ? 0 : 1,
        ]);

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = model(UserModel::class)->find($id);
        if ($row === null) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        // Info profil terkait (dosen/mahasiswa) bila ada.
        $profil = null;
        if ($row['role'] === 'dosen') {
            $profil = model(DosenModel::class)->findByUserId($id);
        } elseif ($row['role'] === 'mahasiswa') {
            $profil = model(MahasiswaModel::class)->findByUserId($id);
        }

        return view('admin/users/form', [
            'title'  => 'Edit User',
            'row'    => $row,
            'profil' => $profil,
        ]);
    }

    public function update(int $id)
    {
        $model = model(UserModel::class);
        $row   = $model->find($id);

        if ($row === null) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $username = (string) $this->request->getPost('username');
        $email    = (string) $this->request->getPost('email');
        $role     = (string) $this->request->getPost('role');
        $password = (string) $this->request->getPost('password');

        if ($username === '') {
            return redirect()->back()->withInput()->with('error', 'Username wajib diisi.');
        }

        if (! in_array($role, ['admin', 'dosen', 'mahasiswa', 'pimpinan'], true)) {
            return redirect()->back()->withInput()->with('error', 'Role tidak valid.');
        }

        $dup = $model->where('username', $username)->where('id !=', $id)->first();
        if ($dup !== null) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        $data = [
            'id'        => $id, // untuk placeholder {id} pada validasi is_unique
            'username'  => $username,
            'email'     => $email !== '' ? $email : null,
            'role'      => $role,
            'is_active' => $this->request->getPost('is_active') === null ? 0 : 1,
        ];

        if ($password !== '') {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
            }
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Cegah admin menonaktifkan / menurunkan role dirinya sendiri.
        if ((int) $id === (int) session()->get('user_id')) {
            if ($data['is_active'] !== 1) {
                return redirect()->back()->withInput()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            }
            if ($role !== 'admin') {
                return redirect()->back()->withInput()->with('error', 'Anda tidak dapat mengubah role akun sendiri dari admin.');
            }
        }

        if (! $model->update($id, $data)) {
            log_message('error', 'User update gagal: ' . json_encode($model->errors()));

            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/users')->with('success', 'User berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = model(UserModel::class);
        $row   = $model->find($id);

        if ($row === null) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        if ((int) $id === (int) session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Periksa keterkaitan profil dosen/mahasiswa.
        $terkait = null;
        if ($row['role'] === 'dosen') {
            $terkait = model(DosenModel::class)->findByUserId($id);
        } elseif ($row['role'] === 'mahasiswa') {
            $terkait = model(MahasiswaModel::class)->findByUserId($id);
        }

        $model->delete($id);

        $msg = 'User berhasil dihapus.';
        if ($terkait !== null) {
            $msg .= ' (profil ' . $row['role'] . ' terkait ikut terhapus)';
        }

        return redirect()->to('/admin/users')->with('success', $msg);
    }
}
