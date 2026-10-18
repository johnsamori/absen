<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Greeting Banner -->
<div class="dash-hero mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-pill bg-white bg-opacity-10 text-white-50 small mb-2">
                <i class="fa-solid fa-id-card text-warning"></i>
                <span>NIM: <?= esc($mhs['nim'] ?? '-') ?> · <?= esc($mhs['nama_prodi'] ?? 'FISIP UNCEN') ?></span>
            </div>
            <h2 class="h3 fw-bold text-white mb-1">Halo, <?= esc($mhs['nama_lengkap'] ?? session()->get('nama')) ?> 👋</h2>
            <p class="text-white-50 mb-0 small">
                Pantau kehadiran perkuliahanmu, pastikan memenuhi syarat minimal <?= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ?>% untuk mengikuti UTS/UAS.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= site_url('mahasiswa/scan') ?>" class="btn btn-warning btn-lg fw-bold px-4 py-2.5 shadow text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-qrcode fs-5"></i>
                <span>Scan QR Presensi</span>
            </a>
        </div>
    </div>
</div>

<!-- Live Open Session Alert Banner (if any open meeting) -->
<?php if (!empty($sesiAktif)): ?>
    <?php foreach ($sesiAktif as $sa): ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 p-3 rounded-3" style="background: linear-gradient(90deg, #ecfdf5 0%, #d1fae5 100%);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <span class="pulse-dot" style="width: 12px; height: 12px; background: #fff;"></span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success text-white">Sesi Dibuka!</span>
                        <span class="fw-bold text-dark"><?= esc($sa['nama_mk']) ?> (Kelas <?= esc($sa['nama_kelas'] ?? '-') ?>)</span>
                    </div>
                    <div class="small text-muted mt-0.5">
                        Dosen: <strong class="text-dark"><?= esc($sa['nama_dosen'] ?? 'Dosen Pengampu') ?></strong> · Pertemuan ke-<?= (int) $sa['pertemuan_ke'] ?>
                    </div>
                </div>
            </div>
            <a href="<?= site_url('mahasiswa/scan') ?>" class="btn btn-success fw-bold px-3 py-2 shadow-sm d-flex align-items-center gap-1.5">
                <i class="fa-solid fa-camera"></i>
                <span>Scan Sekarang</span>
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Stat Summary Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Rata-rata Kehadiran</span>
                <div class="stat-icon-wrap <?= $persenKeseluruhan >= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ? 'success' : 'danger' ?>">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold mb-0 <?= $persenKeseluruhan >= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ? 'text-success' : 'text-danger' ?>">
                <?= (float) $persenKeseluruhan ?>%
            </div>
            <div class="text-muted small" style="font-size: 0.75rem;">
                Target min: <?= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ?>%
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Hadir</span>
                <div class="stat-icon-wrap primary"><i class="fa-solid fa-clipboard-user"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= (int) $totalHadir ?> Kali</div>
            <div class="text-muted small" style="font-size: 0.75rem;">Pertemuan tatap muka</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Izin & Sakit</span>
                <div class="stat-icon-wrap warning"><i class="fa-solid fa-file-medical"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= (int) ($totalIzin + $totalSakit) ?> Kali</div>
            <div class="text-muted small" style="font-size: 0.75rem;">Dengan konfirmasi</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Status Eligible</span>
                <div class="stat-icon-wrap <?= $kelasPeringatan === 0 ? 'success' : 'danger' ?>">
                    <i class="fa-solid <?= $kelasPeringatan === 0 ? 'fa-circle-check' : 'fa-triangle-exclamation' ?>"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold mb-0 <?= $kelasPeringatan === 0 ? 'text-success' : 'text-danger' ?>">
                <?= (int) $kelasMemenuhi ?> / <?= count($kelas) ?>
            </div>
            <div class="text-muted small" style="font-size: 0.75rem;">Kelas penuhi syarat UTS/UAS</div>
        </div>
    </div>
</div>

<!-- Jadwal Hari Ini Section -->
<?php if (!empty($jadwalHariIni)): ?>
    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded p-1 bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <span class="fw-bold">Jadwal Kuliah Hari Ini (<?= esc($hariIni) ?>)</span>
            </div>
            <span class="badge badge-soft-warning px-2.5 py-1">
                <?= count($jadwalHariIni) ?> Perkuliahan
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Dosen</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th class="text-end">Presensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jadwalHariIni as $k): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($k['nama_mk']) ?></div>
                                <small class="text-muted"><?= esc($k['kode_mk']) ?> · Kelas <?= esc($k['nama_kelas'] ?? '-') ?></small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1.5 text-dark small fw-medium">
                                    <i class="fa-solid fa-user-tie text-muted"></i>
                                    <?= esc($k['dosen_nama'] ?? '-') ?>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">
                                    <i class="fa-regular fa-clock text-muted me-1"></i>
                                    <?= esc(substr($k['jam_mulai'] ?? '', 0, 5)) ?> - <?= esc(substr($k['jam_selesai'] ?? '', 0, 5)) ?> WIT
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= esc($k['ruangan'] ?? 'R. Kuliah') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= site_url('mahasiswa/scan') ?>" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fa-solid fa-qrcode me-1"></i>Scan
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Rekap Kehadiran per Mata Kuliah & Riwayat Presensi -->
<div class="row g-4">
    <!-- Rekap per Mata Kuliah -->
    <div class="col-xl-8">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <span class="fw-bold">Status Kehadiran per Mata Kuliah</span>
                </div>
                <span class="badge badge-soft-primary"><?= count($kelas) ?> Mata Kuliah</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($kelas)): ?>
                    <div class="p-5 text-center text-muted">
                        <i class="fa-solid fa-folder-open fa-2x mb-2 text-black-50"></i>
                        <p class="mb-0">Belum ada kelas yang Anda ikuti pada semester ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mata Kuliah</th>
                                    <th class="text-center">H</th>
                                    <th class="text-center">I</th>
                                    <th class="text-center">S</th>
                                    <th class="text-center">A</th>
                                    <th style="min-width: 140px;">Persentase</th>
                                    <th class="text-end">Status Ujian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kelas as $k): ?>
                                    <?php
                                    $minPersen = (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75);
                                    $eligible = $k['jml_pertemuan'] === 0 || $k['persen_hadir'] >= $minPersen;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= esc($k['nama_mk']) ?></div>
                                            <small class="text-muted"><?= esc($k['kode_mk']) ?> · Kelas <?= esc($k['nama_kelas'] ?? '-') ?></small>
                                        </td>
                                        <td class="text-center fw-bold text-success"><?= (int) $k['hadir'] ?></td>
                                        <td class="text-center text-warning fw-semibold"><?= (int) $k['izin'] ?></td>
                                        <td class="text-center text-info fw-semibold"><?= (int) $k['sakit'] ?></td>
                                        <td class="text-center text-danger fw-bold"><?= (int) $k['alpa'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 0.75rem;">
                                                <span class="fw-bold <?= $eligible ? 'text-success' : 'text-danger' ?>">
                                                    <?= (float) $k['persen_hadir'] ?>%
                                                </span>
                                                <span class="text-muted"><?= (int) $k['hadir'] ?>/<?= (int) $k['jml_pertemuan'] ?> Sesi</span>
                                            </div>
                                            <div class="progress" style="height: 6px; border-radius: 4px;">
                                                <div class="progress-bar <?= $eligible ? 'bg-success' : 'bg-danger' ?>"
                                                     style="width: <?= (float) $k['persen_hadir'] ?>%"></div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($eligible): ?>
                                                <span class="badge badge-soft-success">
                                                    <i class="fa-solid fa-check me-1"></i>Eligible
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-soft-danger">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>< <?= $minPersen ?>%
                                                </span>
                                            <?php endif; ?>
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

    <!-- Riwayat Presensi Terbaru Mahasiswa -->
    <div class="col-xl-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <span class="fw-bold">Presensi Terakhir</span>
                </div>
                <a href="<?= site_url('mahasiswa/riwayat') ?>" class="text-primary small text-decoration-none fw-semibold">
                    Semua <i class="fa-solid fa-arrow-right ms-0.5"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($riwayatTerbaru)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fa-regular fa-calendar-xmark fa-2x mb-2 text-black-50"></i>
                        <p class="mb-0 small">Belum ada catatan presensi.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($riwayatTerbaru as $r): ?>
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 170px;">
                                        <?= esc($r['nama_mk'] ?? 'Mata Kuliah') ?>
                                    </div>
                                    <?= status_badge($r['status']) ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                    <span>Pertemuan ke-<?= (int) $r['pertemuan_ke'] ?></span>
                                    <span><i class="fa-regular fa-clock me-1"></i><?= esc(date('d/m/Y H:i', strtotime($r['waktu_presensi']))) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-light p-3 border-top text-center">
                <a href="<?= site_url('mahasiswa/izin') ?>" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fa-solid fa-file-medical me-1"></i>Ajukan Izin / Sakit
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
