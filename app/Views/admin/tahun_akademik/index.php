<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tahun Akademik</h4>
    <a href="<?= site_url('admin/tahun-akademik/create') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>Tambah
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Tahun</th><th>Semester</th><th>Status</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['tahun']) ?></td>
                            <td><?= esc($r['semester']) ?></td>
                            <td>
                                <?= (int) $r['is_active'] === 1
                                    ? '<span class="badge text-bg-success">Aktif</span>'
                                    : '<span class="badge text-bg-secondary">Tidak Aktif</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/tahun-akademik/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="post" action="<?= site_url('admin/tahun-akademik/delete/' . $r['id']) ?>" class="d-inline"
                                      onsubmit="return confirm('Hapus tahun akademik ini?')">
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
