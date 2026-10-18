<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 900; // 15 menit

    public function login()
    {
        if ($this->request->is('post')) {
            return $this->attemptLogin();
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/auth/login')->with('success', 'Anda telah logout.');
    }

    private function attemptLogin()
    {
        $session  = session();
        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        // Rate limit sederhana berbasis session.
        if ($this->isLocked($session)) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.');
        }

        if ($username === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $userModel = model(UserModel::class);
        $user = $userModel->findByUsername($username);

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            $this->registerFailure($session);

            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        if ((int) $user['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda nonaktif. Hubungi administrator.');
        }

        // Ambil nama tampilan + profile id sesuai role.
        [$nama, $profileId] = $this->resolveProfile($user);

        $session->regenerate();
        $session->set([
            'user_id'      => (int) $user['id'],
            'username'     => $user['username'],
            'role'         => $user['role'],
            'nama'         => $nama,
            'profile_id'   => $profileId,
            'is_logged_in' => true,
        ]);
        $session->remove('login_attempts');
        $session->remove('login_lock_until');

        return redirect()->to('/')->with('success', 'Selamat datang, ' . $nama . '!');
    }

    private function resolveProfile(array $user): array
    {
        $nama      = $user['username'];
        $profileId = null;

        if ($user['role'] === 'dosen') {
            $dosen = model(DosenModel::class)->findByUserId((int) $user['id']);
            if ($dosen !== null) {
                $nama      = $dosen['nama_lengkap'];
                $profileId = (int) $dosen['id'];
            }
        } elseif ($user['role'] === 'mahasiswa') {
            $mhs = model(MahasiswaModel::class)->findByUserId((int) $user['id']);
            if ($mhs !== null) {
                $nama      = $mhs['nama_lengkap'];
                $profileId = (int) $mhs['id'];
            }
        }

        return [$nama, $profileId];
    }

    private function isLocked($session): bool
    {
        $lockUntil = (int) $session->get('login_lock_until');

        return $lockUntil > time();
    }

    private function registerFailure($session): void
    {
        $attempts = (int) $session->get('login_attempts') + 1;
        $session->set('login_attempts', $attempts);

        if ($attempts >= self::MAX_ATTEMPTS) {
            $session->set('login_lock_until', time() + self::LOCK_SECONDS);
            $session->set('login_attempts', 0);
        }
    }
}
