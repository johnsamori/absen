<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Jadwal Kelas Kuliah</h4>
        <small class="text-muted">Alokasi jadwal, dosen pengampu, dan ruangan</small>
    </div>
    <div class="d-flex gap-2">
        <form method="get" class="d-flex gap-2">
            <select name="tahun_akademik_id" class="form-select" onchange="this.form.submit()">
                <option value="">— Semua TA —</option>
                <?php foreach ($taList as $ta): ?>
                    <option value="<?= $ta['id'] ?>" <?= $taId === (int) $ta['id'] ? 'selected' : '' ?>>
                        <?= esc($ta['tahun']) ?> <?= esc($ta['semester']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= site_url('admin/kelas-kuliah/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>Tambah
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Mata Kuliah</th><th>Kelas</th><th>Dosen</th><th>Hari</th><th>Jam</th><th>Ruangan</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['kode_mk']) ?> — <?= esc($r['nama_mk']) ?></td>
                            <td><?= esc($r['nama_kelas'] ?? '-') ?></td>
                            <td><?= esc($r['dosen_nama'] ?? '-') ?></td>
                            <td><?= esc($r['hari'] ?? '-') ?></td>
                            <td><?= esc(substr($r['jam_mulai'] ?? '', 0, 5)) ?> - <?= esc(substr($r['jam_selesai'] ?? '', 0, 5)) ?></td>
                            <td><?= esc($r['ruangan'] ?? '-') ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?= site_url('admin/kelas-kuliah/' . $r['id'] . '/peserta') ?>" class="btn btn-sm btn-outline-info" title="Kelola peserta">
                                    <i class="fa-solid fa-users"></i>
                                </a>
                                <a href="<?= site_url('admin/kelas-kuliah/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="post" action="<?= site_url('admin/kelas-kuliah/delete/' . $r['id']) ?>" class="d-inline"
                                      onsubmit="return confirm('Hapus kelas ini?')">
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
