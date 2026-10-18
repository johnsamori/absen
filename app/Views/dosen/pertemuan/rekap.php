<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Rekap Presensi</h4>
        <small class="text-muted">
            <?= esc($kelas['nama_mk']) ?> — Pertemuan ke-<?= (int) $pertemuan['pertemuan_ke'] ?> (<?= esc($pertemuan['tanggal'] ?? '-') ?>)
        </small>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="<?= site_url('laporan/bap/' . $pertemuan['id']) ?>" class="btn btn-outline-primary" target="_blank">
            <i class="fa-solid fa-print me-1"></i>Cetak BAP
        </a>
        <a href="<?= site_url('dosen/pertemuan/' . $kelas['id']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card p-3 text-center"><span class="text-success fs-3 fw-bold"><?= (int) $rekap['total']['Hadir'] ?></span><small class="text-muted">Hadir</small></div></div>
    <div class="col-6 col-md-3"><div class="card p-3 text-center"><span class="text-warning fs-3 fw-bold"><?= (int) $rekap['total']['Izin'] ?></span><small class="text-muted">Izin</small></div></div>
    <div class="col-6 col-md-3"><div class="card p-3 text-center"><span class="text-info fs-3 fw-bold"><?= (int) $rekap['total']['Sakit'] ?></span><small class="text-muted">Sakit</small></div></div>
    <div class="col-6 col-md-3"><div class="card p-3 text-center"><span class="text-danger fs-3 fw-bold"><?= (int) $rekap['total']['Alpa'] ?></span><small class="text-muted">Alpa</small></div></div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>NIM</th><th>Nama</th><th>Waktu</th><th>Metode</th><th>Jarak</th><th>Status</th></tr></thead>
            <tbody>
                <?php if ($rekap['rows'] === []): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada presensi.</td></tr>
                <?php else: ?>
                    <?php foreach ($rekap['rows'] as $r): ?>
                        <tr>
                            <td><?= esc($r['nim']) ?></td>
                            <td><?= esc($r['nama_lengkap']) ?></td>
                            <td><?= esc($r['waktu_presensi'] ?? '-') ?></td>
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
