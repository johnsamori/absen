<?php
/**
 * @var array<string, mixed> $kelas
 * @var list<array<string, mixed>> $pertemuan
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Pertemuan — <?= esc($kelas['nama_mk']) ?></h4>
        <small class="text-muted">Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['hari'] ?? '') ?> <?= esc(substr($kelas['jam_mulai'] ?? '', 0, 5)) ?>-<?= esc(substr($kelas['jam_selesai'] ?? '', 0, 5)) ?> · <?= esc($kelas['ruangan'] ?? '') ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('dosen') ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Kembali</a>
        <a href="<?= site_url('dosen/pertemuan/' . $kelas['id'] . '/buka') ?>" class="btn btn-primary">
            <i class="fa-solid fa-play me-1"></i>Buka Sesi
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Pert.</th><th>Tanggal</th><th>Materi</th><th>Dibuka</th><th>Ditutup</th><th>Status</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($pertemuan === []): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pertemuan. Klik "Buka Sesi" untuk memulai.</td></tr>
                <?php else: ?>
                    <?php foreach ($pertemuan as $p): ?>
                        <tr>
                            <td>P<?= (int) $p['pertemuan_ke'] ?></td>
                            <td><?= esc($p['tanggal'] ?? '-') ?></td>
                            <td><?= esc(mb_strimwidth($p['materi_kuliah'] ?? '-', 0, 40, '…')) ?></td>
                            <td><?= esc($p['jam_buka'] ?? '-') ?></td>
                            <td><?= esc($p['jam_tutup'] ?? '-') ?></td>
                            <td>
                                <?= $p['status'] === 'open'
                                    ? '<span class="badge text-bg-success">Open</span>'
                                    : '<span class="badge text-bg-secondary">Closed</span>' ?>
                                <?php if (!empty($p['jam_tutup'])): ?>
                                    <div class="small text-muted" style="font-size: 0.72rem;">
                                        s/d <?= esc(date('H:i', strtotime($p['jam_tutup']))) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <?php if ($p['status'] === 'open'): ?>
                                    <a href="<?= site_url('dosen/pertemuan/' . $p['id'] . '/scan') ?>" class="btn btn-sm btn-primary fw-semibold" title="Buka Kamera Scan Mahasiswa">
                                        <i class="fa-solid fa-camera me-1"></i>Scan di Kelas
                                    </a>
                                <?php else: ?>
                                    <a href="<?= site_url('dosen/pertemuan/' . $p['id'] . '/scan') ?>" class="btn btn-sm btn-outline-primary" title="Scanner Presensi">
                                        <i class="fa-solid fa-camera"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= site_url('dosen/pertemuan/' . $kelas['id'] . '/qr/' . $p['id']) ?>" class="btn btn-sm btn-outline-dark" title="Layar Proyektor">
                                    <i class="fa-solid fa-tv"></i>
                                </a>
                                <a href="<?= site_url('dosen/pertemuan/' . $p['id'] . '/kelola') ?>" class="btn btn-sm btn-outline-info" title="Kelola presensi">
                                    <i class="fa-solid fa-user-check"></i>
                                </a>
                                <a href="<?= site_url('dosen/pertemuan/' . $p['id'] . '/rekap') ?>" class="btn btn-sm btn-outline-primary" title="Rekap">
                                    <i class="fa-solid fa-list"></i>
                                </a>
                                <a href="<?= site_url('dosen/pertemuan/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Edit BAP & Waktu">
                                    <i class="fa-solid fa-pen"></i>
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
