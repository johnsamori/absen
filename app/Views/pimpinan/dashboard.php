<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Greeting Banner -->
<div class="dash-hero mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-pill bg-white bg-opacity-10 text-white-50 small mb-2">
                <i class="fa-solid fa-chart-line text-warning"></i>
                <span>Executive Academic Analytics</span>
            </div>
            <h2 class="h3 fw-bold text-white mb-1">Dashboard Pimpinan Fakultas</h2>
            <p class="text-white-50 mb-0 small">
                Monitoring menyeluruh efektivitas perkuliahan, kehadiran mahasiswa, dan pemenuhan tatap muka dosen FISIP UNCEN.
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
                <i class="fa-solid fa-print me-1 text-primary"></i>Laporan Eksekutif
            </a>
        </div>
    </div>
</div>

<!-- Executive Metric Cards -->
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
            <div class="text-muted small" style="font-size: 0.75rem;">Tenaga pendidik</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Kelas Kuliah</span>
                <div class="stat-icon-wrap warning"><i class="fa-solid fa-chalkboard-user"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalKelas) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Rombel aktif</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Presensi Hari Ini</span>
                <div class="stat-icon-wrap primary"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) ($presensiHariIni ?? 0)) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Entri hari ini</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Sesi Live</span>
                <div class="stat-icon-wrap success"><i class="fa-solid fa-qrcode"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 <?= ($sesiAktifCount ?? 0) > 0 ? 'text-success' : 'text-dark' ?>">
                <?= (int) ($sesiAktifCount ?? 0) ?>
            </div>
            <div class="text-muted small" style="font-size: 0.75rem;">Perkuliahan berlangsung</div>
        </div>
    </div>

    <div class="col-6 col-lg-4 col-xl-2">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Presensi</span>
                <div class="stat-icon-wrap purple"><i class="fa-solid fa-clipboard-check"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalPresensi) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Akumulasi semester</div>
        </div>
    </div>
</div>

<!-- Interactive Charts Row -->
<div class="row g-4 mb-4">
    <!-- Bar Chart: Kehadiran per Prodi -->
    <div class="col-xl-7">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>
                    <span class="fw-bold">Rata-rata Tingkat Kehadiran per Program Studi</span>
                </div>
                <span class="badge badge-soft-primary"><?= count($prodiStat) ?> Program Studi</span>
            </div>
            <div class="card-body">
                <?php if (empty($prodiStat)): ?>
                    <p class="text-center text-muted py-5 mb-0">Belum ada data presensi prodi.</p>
                <?php else: ?>
                    <div class="chart-wrapper" style="min-height: 270px;">
                        <canvas id="chartPimpinanProdi"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart: Distribusi Status Kehadiran Global -->
    <div class="col-xl-5">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <span class="fw-bold">Distribusi Status Presensi Fakultas</span>
                </div>
                <span class="badge bg-light text-muted fw-normal">Global</span>
            </div>
            <div class="card-body">
                <div class="chart-wrapper d-flex align-items-center justify-content-center mb-3" style="min-height: 190px;">
                    <canvas id="chartPimpinanStatus"></canvas>
                </div>
                <div class="row g-2 text-center">
                    <div class="col-3">
                        <div class="p-2 rounded bg-success bg-opacity-10">
                            <div class="fw-bold text-success fs-5"><?= number_format($statusMap['Hadir'] ?? 0) ?></div>
                            <small class="text-muted" style="font-size: 0.72rem;">Hadir</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded bg-warning bg-opacity-10">
                            <div class="fw-bold text-warning fs-5"><?= number_format($statusMap['Izin'] ?? 0) ?></div>
                            <small class="text-muted" style="font-size: 0.72rem;">Izin</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded bg-info bg-opacity-10">
                            <div class="fw-bold text-info fs-5"><?= number_format($statusMap['Sakit'] ?? 0) ?></div>
                            <small class="text-muted" style="font-size: 0.72rem;">Sakit</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded bg-danger bg-opacity-10">
                            <div class="fw-bold text-danger fs-5"><?= number_format($statusMap['Alpa'] ?? 0) ?></div>
                            <small class="text-muted" style="font-size: 0.72rem;">Alpa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dosen Tatap Muka & Prodi Summary Tables -->
<div class="row g-4">
    <!-- Ketercapaian Tatap Muka Dosen -->
    <div class="col-xl-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <span class="fw-bold">Aktivitas Mengajar Dosen</span>
                </div>
                <span class="badge bg-light text-muted fw-normal">Target: 16 Pertemuan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Dosen</th>
                            <th class="text-center" style="min-width: 140px;">Progres Tatap Muka</th>
                            <th class="text-end">Pertemuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dosenStat)): ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data pengajaran.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_slice($dosenStat, 0, 8) as $d): ?>
                                <?php
                                $jml = (int) $d['jml_pertemuan'];
                                $pct = min(100, round(($jml / 16) * 100));
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark small"><?= esc($d['nama_lengkap']) ?></div>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 6px; border-radius: 4px;">
                                            <div class="progress-bar <?= $pct >= 75 ? 'bg-success' : 'bg-primary' ?>"
                                                 style="width: <?= $pct ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-primary-subtle text-primary fw-bold">
                                            <?= $jml ?> Sesi
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Analisis Detail Program Studi -->
    <div class="col-xl-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="fw-bold">Rincian Partisipasi Program Studi</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Program Studi</th>
                            <th class="text-center">Kehadiran</th>
                            <th class="text-center">Persentase</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($prodiStat)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data prodi.</td></tr>
                        <?php else: ?>
                            <?php foreach ($prodiStat as $p): ?>
                                <?php $pass = (float) $p['persen'] >= 75; ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark small"><?= esc($p['nama_prodi'] ?? 'Tanpa Prodi') ?></div>
                                    </td>
                                    <td class="text-center small text-muted">
                                        <span class="fw-bold text-dark"><?= number_format((int) $p['hadir']) ?></span> / <?= number_format((int) $p['total']) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold <?= $pass ? 'text-success' : 'text-danger' ?>">
                                            <?= (float) $p['persen'] ?>%
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($pass): ?>
                                            <span class="badge badge-soft-success">Optimal</span>
                                        <?php else: ?>
                                            <span class="badge badge-soft-warning">Perlu Atensi</span>
                                        <?php endif; ?>
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
    // 1. Doughnut Chart Status Kehadiran
    const pimpinanStatusCtx = document.getElementById('chartPimpinanStatus');
    if (pimpinanStatusCtx) {
        new Chart(pimpinanStatusCtx, {
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
                cutout: '72%'
            }
        });
    }

    // 2. Bar Chart Kehadiran per Prodi
    const pimpinanProdiCtx = document.getElementById('chartPimpinanProdi');
    if (pimpinanProdiCtx) {
        const prodiLabels = <?= json_encode(array_column($prodiStat, 'nama_prodi')) ?>;
        const prodiValues = <?= json_encode(array_map('floatval', array_column($prodiStat, 'persen'))) ?>;

        new Chart(pimpinanProdiCtx, {
            type: 'bar',
            data: {
                labels: prodiLabels,
                datasets: [{
                    label: 'Tingkat Kehadiran (%)',
                    data: prodiValues,
                    backgroundColor: prodiValues.map(v => v >= 75 ? 'rgba(79, 70, 229, 0.85)' : 'rgba(239, 68, 68, 0.85)'),
                    borderRadius: 6,
                    maxBarThickness: 36
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
