<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'Absensi FISIP UNCEN') ?> — Absensi FISIP UNCEN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
    <?= $this->renderSection('head') ?>
</head>
<body>
<?php
$user = current_user();
$role = $user['role'] ?? '';
$path = service('uri')->getPath();
$menu = [];

if ($role === 'admin') {
    $menu = [
        ['/admin', 'Dashboard', 'fa-gauge-high'],
        ['/admin/program-studi', 'Prodi', 'fa-graduation-cap'],
        ['/admin/tahun-akademik', 'Tahun Ajar', 'fa-calendar-days'],
        ['/admin/dosen', 'Dosen', 'fa-user-tie'],
        ['/admin/mahasiswa', 'Mahasiswa', 'fa-users'],
        ['/admin/users', 'Users', 'fa-user-gear'],
        ['/admin/mata-kuliah', 'Mata Kuliah', 'fa-book'],
        ['/admin/kelas-kuliah', 'Kelas', 'fa-chalkboard-user'],
        ['/admin/pengaturan', 'Konfigurasi', 'fa-location-dot'],
        ['/laporan', 'Laporan', 'fa-file-lines'],
    ];
} elseif ($role === 'dosen') {
    $menu = [
        ['/dosen', 'Dashboard', 'fa-gauge-high'],
        ['/laporan', 'Laporan & BAP', 'fa-file-lines'],
    ];
} elseif ($role === 'mahasiswa') {
    $menu = [
        ['/mahasiswa', 'Dashboard', 'fa-gauge-high'],
        ['/mahasiswa/qr', 'QR Code Saya', 'fa-qrcode'],
        ['/mahasiswa/kartu-absensi', 'Kartu Presensi', 'fa-id-card-clip'],
        ['/mahasiswa/riwayat', 'Riwayat', 'fa-clock-rotate-left'],
        ['/mahasiswa/izin', 'Izin / Sakit', 'fa-file-medical'],
    ];
} elseif ($role === 'pimpinan') {
    $menu = [
        ['/pimpinan', 'Dashboard', 'fa-gauge-high'],
        ['/laporan', 'Laporan', 'fa-file-lines'],
    ];
}

$isActive = function (string $url) use ($path): bool {
    return ($path === $url) || ($url !== '/' && str_starts_with($path, $url));
};

$initials = mb_strtoupper(mb_substr($user['nama'] ?? 'U', 0, 1));
?>
<div class="app-wrapper">
    <!-- Backdrop offcanvas mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="fa-solid fa-building-columns"></i></div>
            <div class="flex-grow-1">
                <div class="fw-bold">Absensi FISIP</div>
                <small>Universitas Cenderawasih</small>
            </div>
            <button class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <?php foreach ($menu as [$url, $label, $icon]): ?>
                <a class="nav-item <?= $isActive($url) ? 'active' : '' ?>" href="<?= site_url(ltrim($url, '/')) ?>">
                    <i class="fa-solid <?= $icon ?>"></i><span><?= esc($label) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="px-3 py-3 d-none d-lg-block" style="border-top: 1px solid rgba(255,255,255,0.12);">
            <div class="d-flex align-items-center gap-2 text-white-50 small">
                <i class="fa-solid fa-circle-info"></i><span>v1.0 · Absensi Mahasiswa</span>
            </div>
        </div>
    </aside>

    <!-- Main area -->
    <div class="main-area">
        <header class="topbar">
            <button class="burger" id="sidebarToggle" aria-label="Buka menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="topbar-title d-lg-none">Absensi FISIP</span>

            <div class="user-chip">
                <div class="user-avatar"><?= esc($initials) ?></div>
                <div class="user-meta d-none d-sm-block">
                    <div class="name"><?= esc($user['nama'] ?? '') ?></div>
                    <div class="role"><?= esc(role_label($role)) ?></div>
                </div>
                <form method="post" action="<?= site_url('auth/logout') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="d-none d-sm-inline ms-1">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i><?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i><?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<!-- Bottom navigation (mobile) -->
<nav class="bottom-nav">
    <div class="bn-inner">
        <?php foreach ($menu as [$url, $label, $icon]): ?>
            <a class="bn-item <?= $isActive($url) ? 'active' : '' ?>" href="<?= site_url(ltrim($url, '/')) ?>">
                <i class="fa-solid <?= $icon ?>"></i>
                <span><?= esc($label) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggle   = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.add('open');
        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        backdrop.classList.remove('open');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    backdrop.addEventListener('click', closeSidebar);

    // Tutup otomatis saat memilih menu di mobile
    sidebar.querySelectorAll('.nav-item').forEach(a => a.addEventListener('click', closeSidebar));
})();
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
