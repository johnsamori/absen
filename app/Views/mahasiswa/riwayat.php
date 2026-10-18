<?php
/**
 * @var list<array<string, mixed>> $rows
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Riwayat Presensi</h4>
    <a href="<?= site_url('mahasiswa/kartu-absensi') ?>" class="btn btn-primary btn-sm fw-semibold shadow-sm">
        <i class="fa-solid fa-print me-1.5"></i>Cetak Kartu Presensi Semester
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Waktu</th><th>Mata Kuliah</th><th>Pert.</th><th>Tanggal</th><th>Metode</th><th>Jarak</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat presensi.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['waktu_presensi'] ?? '-') ?></td>
                            <td><?= esc($r['kode_mk'] ?? '') ?> — <?= esc($r['nama_mk'] ?? '') ?></td>
                            <td>P<?= (int) $r['pertemuan_ke'] ?></td>
                            <td><?= esc($r['tanggal'] ?? '-') ?></td>
                            <td>
                                <?php
                                $metode = ['qr' => 'QR', 'token' => 'Token', 'manual' => 'Manual'][$r['metode_absen']] ?? $r['metode_absen'];
                                echo esc($metode);
                                ?>
                            </td>
                            <td><?= $r['jarak_meter'] !== null ? esc($r['jarak_meter']) . ' m' : '-' ?></td>
                            <td><?= status_badge($r['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
