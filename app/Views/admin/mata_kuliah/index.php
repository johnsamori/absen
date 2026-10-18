<?php
/**
 * @var list<array<string, mixed>> $rows
 * @var string $q
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Mata Kuliah</h4>
    <div class="d-flex gap-2">
        <a href="<?= site_url('admin/import/mata-kuliah') ?>" class="btn btn-outline-primary">
            <i class="fa-solid fa-file-import me-1"></i>Impor
        </a>
        <a href="<?= site_url('admin/mata-kuliah/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>Tambah
        </a>
    </div>
</div>

<form method="get" action="<?= site_url('admin/mata-kuliah') ?>" class="mb-2">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" name="q" value="<?= esc($q) ?>" class="form-control"
               placeholder="Cari kode MK, nama mata kuliah, SKS, semester, atau prodi...">
        <button class="btn btn-primary" type="submit">Cari</button>
        <?php if ($q !== ''): ?>
            <a href="<?= site_url('admin/mata-kuliah') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<div class="text-muted small mb-3">
    <?= count($rows) ?> mata kuliah
    <?php if ($q !== ''): ?> ditemukan untuk "<strong><?= esc($q) ?></strong>"<?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Kode MK</th><th>Mata Kuliah</th><th>SKS</th><th>Prodi</th><th>Semester</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4"><?= $q !== '' ? 'Tidak ada mata kuliah yang sesuai dengan pencarian.' : 'Belum ada data.' ?></td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['kode_mk']) ?></td>
                            <td><?= esc($r['nama_mk']) ?></td>
                            <td><?= (int) $r['sks'] ?></td>
                            <td><?= esc($r['nama_prodi'] ?? '-') ?></td>
                            <td><?= esc($r['semester_paket'] ?? '-') ?></td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/mata-kuliah/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="post" action="<?= site_url('admin/mata-kuliah/delete/' . $r['id']) ?>" class="d-inline"
                                      onsubmit="return confirm('Hapus mata kuliah ini?')">
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
