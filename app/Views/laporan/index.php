<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Laporan & Rekapitulasi</h4>
        <small class="text-muted">Rekap presensi, BAP, dan export data</small>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Mata Kuliah</th><th>Kelas</th><th>Dosen</th><th>TA</th><th>Hari</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($kelas === []): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kelas.</td></tr>
                <?php else: ?>
                    <?php foreach ($kelas as $k): ?>
                        <tr>
                            <td><?= esc($k['kode_mk']) ?> — <?= esc($k['nama_mk']) ?></td>
                            <td><?= esc($k['nama_kelas'] ?? '-') ?></td>
                            <td><?= esc($k['dosen_nama'] ?? '-') ?></td>
                            <td><?= esc($k['tahun'] ?? '') ?> <?= esc($k['semester'] ?? '') ?></td>
                            <td><?= esc($k['hari'] ?? '-') ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?= site_url('laporan/rekap/' . $k['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-table me-1"></i>Rekap
                                </a>
                                <a href="<?= site_url('laporan/export-csv/' . $k['id']) ?>" class="btn btn-sm btn-outline-success">
                                    <i class="fa-solid fa-file-csv me-1"></i>CSV
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
