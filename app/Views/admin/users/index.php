<?php
/**
 * @var list<array<string, mixed>> $rows
 * @var int $me
 * @var string $q
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1">Manajemen User</h4>
        <span class="page-subtitle">Kelola akun login, role, dan status aktif</span>
    </div>
    <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>Tambah User
    </a>
</div>

<form method="get" action="<?= site_url('admin/users') ?>" class="mb-2">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" name="q" value="<?= esc($q) ?>" class="form-control"
               placeholder="Cari username, email, role, nama, NIP, atau NIM...">
        <button class="btn btn-primary" type="submit">Cari</button>
        <?php if ($q !== ''): ?>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<div class="text-muted small mb-3">
    <?= count($rows) ?> user
    <?php if ($q !== ''): ?> ditemukan untuk "<strong><?= esc($q) ?></strong>"<?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Username</th><th>Email</th><th>Role</th><th>Profil Terkait</th><th>Status</th><th>Dibuat</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4"><?= $q !== '' ? 'Tidak ada user yang sesuai dengan pencarian.' : 'Belum ada user.' ?></td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <?php
                        $roleColor = [
                            'admin' => 'danger', 'dosen' => 'primary',
                            'mahasiswa' => 'info', 'pimpinan' => 'warning',
                        ][$r['role']] ?? 'secondary';
                        ?>
                        <tr>
                            <td>
                                <?= esc($r['username']) ?>
                                <?php if ((int) $r['id'] === $me): ?>
                                    <span class="badge text-bg-light ms-1">Anda</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($r['email'] ?? '-') ?></td>
                            <td><span class="badge bg-<?= $roleColor ?>"><?= esc(role_label($r['role'])) ?></span></td>
                            <td>
                                <?php if ($r['role'] === 'dosen' && $r['dosen_nama'] !== null): ?>
                                    <?= esc($r['dosen_nama']) ?><br><small class="text-muted">NIP <?= esc($r['nip']) ?></small>
                                <?php elseif ($r['role'] === 'mahasiswa' && $r['mhs_nama'] !== null): ?>
                                    <?= esc($r['mhs_nama']) ?><br><small class="text-muted">NIM <?= esc($r['nim']) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= (int) $r['is_active'] === 1
                                    ? '<span class="badge text-bg-success">Aktif</span>'
                                    : '<span class="badge text-bg-danger">Nonaktif</span>' ?>
                            </td>
                            <td><small class="text-muted"><?= esc($r['created_at'] ?? '-') ?></small></td>
                            <td class="text-end text-nowrap">
                                <a href="<?= site_url('admin/users/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <?php if ((int) $r['id'] !== $me): ?>
                                    <form method="post" action="<?= site_url('admin/users/delete/' . $r['id']) ?>" class="d-inline"
                                          onsubmit="return confirm('Hapus user <?= esc($r['username']) ?>?<?= ($r['role'] === 'dosen' || $r['role'] === 'mahasiswa') ? ' Profil terkait ikut terhapus.' : '' ?>')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-light border small mt-3">
    <i class="fa-solid fa-circle-info me-1 text-primary"></i>
    Akun <strong>dosen/mahasiswa</strong> sebaiknya dikelola lewat menu Data Dosen / Data Mahasiswa agar profil
    (NIP/NIM, prodi) lengkap. Modul ini cocok untuk akun admin, pimpinan, reset password, dan nonaktifkan akun.
    Menghapus user dosen/mahasiswa akan ikut menghapus profil terkaitnya.
</div>

<?= $this->endSection() ?>
