<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    /**
     * Halaman beranda — arahkan sesuai role pengguna.
     */
    public function index()
    {
        $role = session()->get('role');

        $target = match ($role) {
            'admin'     => '/admin',
            'dosen'     => '/dosen',
            'mahasiswa' => '/mahasiswa',
            'pimpinan'  => '/pimpinan',
            default     => '/auth/login',
        };

        return redirect()->to($target);
    }
}
