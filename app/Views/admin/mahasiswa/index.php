<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Mahasiswa</h4>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/import/mahasiswa') ?>" class="btn btn-outline-primary">
            <i class="fa-solid fa-file-import me-1"></i>Impor
        </a>
        <a href="<?= site_url('admin/mahasiswa/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>Tambah
        </a>
    </div>
</div>

<form method="get" action="<?= site_url('admin/mahasiswa') ?>" class="mb-2">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" name="q" value="<?= esc($q) ?>" class="form-control"
               placeholder="Cari NIM, nama, angkatan, kelas, atau prodi...">
        <button class="btn btn-primary" type="submit">Cari</button>
        <?php if ($q !== ''): ?>
            <a href="<?= site_url('admin/mahasiswa') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<div class="text-muted small mb-3">
    <?= count($rows) ?> data
    <?php if ($q !== ''): ?> ditemukan untuk "<strong><?= esc($q) ?></strong>"<?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Angkatan</th><th>Kelas</th><th>Status</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['nim']) ?></td>
                            <td><?= esc($r['nama_lengkap']) ?></td>
                            <td><?= esc($r['nama_prodi'] ?? '-') ?></td>
                            <td><?= esc($r['angkatan'] ?? '-') ?></td>
                            <td><?= esc($r['kelas_paralel'] ?? '-') ?></td>
                            <td>
                                <?= (int) $r['is_active'] === 1
                                    ? '<span class="badge text-bg-success">Aktif</span>'
                                    : '<span class="badge text-bg-danger">Nonaktif</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/mahasiswa/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="post" action="<?= site_url('admin/mahasiswa/delete/' . $r['id']) ?>" class="d-inline"
                                      onsubmit="return confirm('Hapus mahasiswa ini beserta akunnya?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
