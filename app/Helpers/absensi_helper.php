<?php

use App\Models\PengaturanKampusModel;

if (! function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return session()->get('is_logged_in') === true;
    }
}

if (! function_exists('current_user')) {
    /**
     * Mengembalikan data user yang sedang login dari session,
     * atau null bila belum login.
     */
    function current_user(): ?array
    {
        if (! is_logged_in()) {
            return null;
        }

        return [
            'user_id'    => session()->get('user_id'),
            'username'   => session()->get('username'),
            'role'       => session()->get('role'),
            'nama'       => session()->get('nama'),
            'profile_id' => session()->get('profile_id'),
        ];
    }
}

if (! function_exists('has_role')) {
    /**
     * Cek apakah user saat ini memiliki salah satu role yang diberikan.
     *
     * @param string|string[] $roles
     */
    function has_role($roles): bool
    {
        if (! is_logged_in()) {
            return false;
        }

        $role = session()->get('role');
        $roles = (array) $roles;

        return in_array($role, $roles, true);
    }
}

if (! function_exists('haversine_meters')) {
    /**
     * Formula Haversine — jarak antar dua titik koordinat dalam meter.
     */
    function haversine_meters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

if (! function_exists('status_badge')) {
    /**
     * Mengembalikan HTML badge Bootstrap untuk status presensi.
     */
    function status_badge(string $status): string
    {
        $map = [
            'Hadir' => 'success',
            'Izin'  => 'warning',
            'Sakit' => 'info',
            'Alpa'  => 'danger',
        ];

        $color = $map[$status] ?? 'secondary';

        return '<span class="badge bg-' . $color . '">' . esc($status) . '</span>';
    }
}

if (! function_exists('role_label')) {
    function role_label(string $role): string
    {
        $map = [
            'admin'      => 'Super Admin',
            'dosen'      => 'Dosen',
            'mahasiswa'  => 'Mahasiswa',
            'pimpinan'   => 'Pimpinan',
        ];

        return $map[$role] ?? ucfirst($role);
    }
}

if (! function_exists('list_hari')) {
    function list_hari(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    }
}

if (! function_exists('pengaturan_kampus')) {
    /**
     * Mengambil pengaturan kampus (koordinat pusat, radius, syarat kehadiran).
     * Di-cache di memori statis agar tidak query berulang dalam satu request.
     */
    function pengaturan_kampus(): ?array
    {
        static $cache = null;
        static $loaded = false;

        if (! $loaded) {
            $cache  = model(PengaturanKampusModel::class)->getPengaturan();
            $loaded = true;
        }

        return $cache;
    }
}

if (! function_exists('nama_hari_id')) {
    function nama_hari_id(string $hari): string
    {
        $map = [
            'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu',
            'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu',
        ];

        return $map[$hari] ?? $hari;
    }
}
