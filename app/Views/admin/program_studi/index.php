<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Program Studi</h4>
    <a href="<?= site_url('admin/program-studi/create') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>Tambah
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Kode</th><th>Program Studi</th><th>Jenjang</th><th>Kaprodi</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['kode_prodi']) ?></td>
                            <td><?= esc($r['nama_prodi']) ?></td>
                            <td><?= esc($r['jenjang'] ?? '-') ?></td>
                            <td><?= esc($r['kaprodi'] ?? '-') ?></td>
                            <td class="text-end">
                                <a href="<?= site_url('admin/program-studi/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="post" action="<?= site_url('admin/program-studi/delete/' . $r['id']) ?>" class="d-inline"
                                      onsubmit="return confirm('Hapus program studi ini?')">
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
