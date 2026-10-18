<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Greeting Banner -->
<div class="dash-hero mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-pill bg-white bg-opacity-10 text-white-50 small mb-2">
                <span class="pulse-dot"></span>
                <span>Sistem Absensi Digital FISIP UNCEN</span>
            </div>
            <h2 class="h3 fw-bold text-white mb-1">Dashboard Super Admin</h2>
            <p class="text-white-50 mb-0 small">
                Monitoring terpusat aktivitas perkuliahan, kehadiran mahasiswa, dan integritas data akademik.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <?php if ($taAktif): ?>
                <div class="badge bg-white bg-opacity-15 text-white border border-white border-opacity-20 px-3 py-2 rounded-3 text-start">
                    <div class="text-white-50" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;">Tahun Akademik</div>
                    <div class="fw-bold fs-6"><i class="fa-solid fa-calendar-check me-1 text-warning"></i><?= esc($taAktif['tahun']) ?> (<?= esc($taAktif['semester']) ?>)</div>
                </div>
            <?php endif; ?>
            <a href="<?= site_url('laporan') ?>" class="btn btn-light btn-sm fw-semibold px-3 py-2 shadow-sm">
                <i class="fa-solid fa-file-export me-1 text-primary"></i>Laporan
            </a>
        </div>
    </div>
</div>

<!-- Key Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Mahasiswa</span>
                <div class="stat-icon-wrap info"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalMahasiswa) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Terdaftar aktif</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Dosen</span>
                <div class="stat-icon-wrap success"><i class="fa-solid fa-user-tie"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalDosen) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Pengajar prodi</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Kelas Kuliah</span>
                <div class="stat-icon-wrap warning"><i class="fa-solid fa-chalkboard-user"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalKelas) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Semester ini</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Presensi</span>
                <div class="stat-icon-wrap primary"><i class="fa-solid fa-clipboard-check"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalPresensi) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Entri kehadiran</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Mata Kuliah</span>
                <div class="stat-icon-wrap purple"><i class="fa-solid fa-book"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalMk) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Kurikulum FISIP</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Program Studi</span>
                <div class="stat-icon-wrap danger"><i class="fa-solid fa-graduation-cap"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalProdi) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Jurusan aktif</div>
        </div>
    </div>
</div>

<!-- Live Sesi & Status Distribution -->
<div class="row g-4 mb-4">
    <!-- Sesi Perkuliahan Berlangsung -->
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="pulse-dot"></span>
                    <span class="fw-bold">Sesi Presensi Berlangsung (Live)</span>
                </div>
                <span class="badge badge-soft-success px-2.5 py-1">
                    <?= count($sesiAktif) ?> Sesi Aktif
                </span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($sesiAktif)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-clock fa-2x mb-2 text-black-50"></i>
                        <p class="mb-0 fw-medium">Tidak ada sesi absensi yang sedang dibuka saat ini.</p>
                        <small class="text-muted">Sesi baru akan muncul otomatis saat dosen mengaktifkan QR pertemuan.</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mata Kuliah / Kelas</th>
                                    <th>Dosen Pengampu</th>
                                    <th>Pertemuan</th>
                                    <th>Tgl & Jam Buka</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sesiAktif as $s): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark"><?= esc($s['nama_mk'] ?? 'Mata Kuliah') ?></div>
                                            <small class="text-muted">Kelas <?= esc($s['nama_kelas'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center text-primary" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-user-tie"></i>
                                                </div>
                                                <span class="small fw-medium"><?= esc($s['nama_dosen'] ?? 'Dosen') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary fw-semibold">P-<?= (int) $s['pertemuan_ke'] ?></span>
                                        </td>
                                        <td class="small text-muted">
                                            <?= esc($s['tanggal']) ?><br>
                                            <span class="fw-semibold text-dark"><?= esc(substr($s['waktu_buka'] ?? '', 0, 5)) ?> WIT</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge badge-soft-success">
                                                <span class="pulse-dot me-1" style="width: 6px; height: 6px;"></span> Open
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Distribusi Status Kehadiran -->
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Distribusi Kehadiran Global</span>
                <span class="badge bg-light text-muted fw-normal">Seluruh Sesi</span>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <div class="chart-wrapper d-flex align-items-center justify-content-center" style="min-height: 190px;">
                            <canvas id="chartStatusPresensi"></canvas>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="p-2 rounded bg-light d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background: #10b981;"></span>
                                    <span class="small fw-medium">Hadir</span>
                                </div>
                                <span class="fw-bold text-success"><?= number_format($statusMap['Hadir'] ?? 0) ?></span>
                            </div>
                            <div class="p-2 rounded bg-light d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background: #f59e0b;"></span>
                                    <span class="small fw-medium">Izin</span>
                                </div>
                                <span class="fw-bold text-warning"><?= number_format($statusMap['Izin'] ?? 0) ?></span>
                            </div>
                            <div class="p-2 rounded bg-light d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background: #0ea5e9;"></span>
                                    <span class="small fw-medium">Sakit</span>
                                </div>
                                <span class="fw-bold text-info"><?= number_format($statusMap['Sakit'] ?? 0) ?></span>
                            </div>
                            <div class="p-2 rounded bg-light d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 10px; height: 10px; background: #ef4444;"></span>
                                    <span class="small fw-medium">Alpa</span>
                                </div>
                                <span class="fw-bold text-danger"><?= number_format($statusMap['Alpa'] ?? 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kehadiran per Prodi & Riwayat Realtime -->
<div class="row g-4">
    <!-- Statistik Prodi -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Persentase Kehadiran per Program Studi</span>
                <span class="badge badge-soft-primary"><?= count($prodiStat) ?> Prodi</span>
            </div>
            <div class="card-body">
                <?php if (empty($prodiStat)): ?>
                    <p class="text-center text-muted py-4 mb-0">Belum ada data presensi prodi.</p>
                <?php else: ?>
                    <div class="chart-wrapper mb-3" style="min-height: 220px;">
                        <canvas id="chartProdi"></canvas>
                    </div>
                    <div class="list-group list-group-flush border-top">
                        <?php foreach ($prodiStat as $p): ?>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <span class="small fw-medium text-dark"><?= esc($p['nama_prodi'] ?? 'Tanpa Prodi') ?></span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small text-muted"><?= number_format((int) $p['hadir']) ?> / <?= number_format((int) $p['total']) ?> mhs</span>
                                    <span class="badge <?= (float) $p['persen'] >= 75 ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                                        <?= (float) $p['persen'] ?>%
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Aktivitas Presensi Terbaru -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Aktivitas Presensi Terbaru</span>
                <span class="badge bg-light text-muted fw-normal">8 Terakhir</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Waktu</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($presensiTerbaru)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data presensi.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($presensiTerbaru as $p): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark" style="font-size: 0.88rem;"><?= esc($p['nama_lengkap']) ?></div>
                                        <small class="text-muted font-monospace"><?= esc($p['nim']) ?></small>
                                    </td>
                                    <td>
                                        <div class="small fw-medium text-truncate" style="max-width: 170px;" title="<?= esc($p['nama_mk'] ?? '-') ?>">
                                            <?= esc($p['nama_mk'] ?? '-') ?>
                                        </div>
                                        <small class="text-muted">P-<?= (int) $p['pertemuan_ke'] ?></small>
                                    </td>
                                    <td>
                                        <div class="small text-dark fw-medium"><?= esc(date('H:i', strtotime($p['waktu_presensi']))) ?> WIT</div>
                                        <small class="text-muted"><?= esc(date('d/m/Y', strtotime($p['waktu_presensi']))) ?></small>
                                    </td>
                                    <td class="text-end">
                                        <?= status_badge($p['status']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Doughnut Chart Status Presensi
    const statusCtx = document.getElementById('chartStatusPresensi');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                datasets: [{
                    data: [
                        <?= (int) ($statusMap['Hadir'] ?? 0) ?>,
                        <?= (int) ($statusMap['Izin'] ?? 0) ?>,
                        <?= (int) ($statusMap['Sakit'] ?? 0) ?>,
                        <?= (int) ($statusMap['Alpa'] ?? 0) ?>
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#0ea5e9', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });
    }

    // 2. Bar Chart Kehadiran per Prodi
    const prodiCtx = document.getElementById('chartProdi');
    if (prodiCtx) {
        const prodiLabels = <?= json_encode(array_column($prodiStat, 'nama_prodi')) ?>;
        const prodiValues = <?= json_encode(array_map('floatval', array_column($prodiStat, 'persen'))) ?>;

        new Chart(prodiCtx, {
            type: 'bar',
            data: {
                labels: prodiLabels,
                datasets: [{
                    label: 'Kehadiran (%)',
                    data: prodiValues,
                    backgroundColor: prodiValues.map(v => v >= 75 ? 'rgba(79, 70, 229, 0.85)' : 'rgba(239, 68, 68, 0.85)'),
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(val) { return val + '%'; }
                        },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return ' Kehadiran: ' + ctx.raw + '%'; }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
