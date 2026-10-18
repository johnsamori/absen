<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter autentikasi multi-role.
 *
 * Konfigurasi route:
 *   ['filter' => 'auth']                                  -> wajib login (semua role)
 *   ['filter' => 'auth:admin,dosen']                      -> wajib login + role tertentu
 *   ['filter' => 'guest']                                 -> khusus tamu (belum login)
 */
class AuthFilter implements FilterInterface
{
    /**
     * @param array|null $arguments daftar role yang diizinkan (opsional)
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Mode guest: hanya untuk yang belum login.
        if ($arguments === ['guest']) {
            if ($session->get('is_logged_in') === true) {
                return redirect()->to('/');
            }

            return null;
        }

        if ($session->get('is_logged_in') !== true) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Batasi role bila diberikan.
        if ($arguments !== null && $arguments !== []) {
            $role = $session->get('role');
            if (! in_array($role, $arguments, true)) {
                return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request.
    }
}
