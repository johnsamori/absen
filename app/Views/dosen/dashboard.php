<?php
/**
 * @var array<string, mixed>|null $dosen
 * @var list<array<string, mixed>> $kelas
 * @var list<array<string, mixed>> $jadwalHariIni
 * @var string $hariIni
 * @var array<string, mixed>|null $taAktif
 * @var int $totalPeserta
 * @var int $totalPertemuan
 * @var int $totalSesiBuka
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Greeting Banner -->
<div class="dash-hero mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-pill bg-white bg-opacity-10 text-white-50 small mb-2">
                <i class="fa-solid fa-graduation-cap text-warning"></i>
                <span><?= esc($dosen['nama_prodi'] ?? 'Fakultas Ilmu Sosial & Ilmu Politik') ?></span>
            </div>
            <h2 class="h3 fw-bold text-white mb-1">Halo, <?= esc($dosen['nama_lengkap'] ?? session()->get('nama')) ?> 👋</h2>
            <p class="text-white-50 mb-0 small">
                NIDN: <span class="text-white fw-medium"><?= esc($dosen['nidn'] ?? '-') ?></span> · Selamat datang di sistem absensi perkuliahan FISIP UNCEN.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <?php if ($taAktif): ?>
                <div class="badge bg-white bg-opacity-15 text-white border border-white border-opacity-20 px-3 py-2 rounded-3 text-start">
                    <div class="text-white-50" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em;">Semester Aktif</div>
                    <div class="fw-bold fs-6"><i class="fa-solid fa-calendar-check me-1 text-warning"></i><?= esc($taAktif['tahun']) ?> (<?= esc($taAktif['semester']) ?>)</div>
                </div>
            <?php endif; ?>
            <a href="<?= site_url('laporan') ?>" class="btn btn-light btn-sm fw-semibold px-3 py-2 shadow-sm">
                <i class="fa-solid fa-file-lines me-1 text-primary"></i>Laporan & BAP
            </a>
        </div>
    </div>
</div>

<!-- Active Session Alert Banner (if any) -->
<?php if ($totalSesiBuka > 0): ?>
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 p-3 rounded-3" style="background: linear-gradient(90deg, #fffbeb 0%, #fef3c7 100%);">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <span class="pulse-dot pulse-dot-warning" style="width: 12px; height: 12px;"></span>
            </div>
            <div>
                <div class="fw-bold text-dark">Perhatian: Ada <?= (int) $totalSesiBuka ?> Sesi Presensi Sedang Terbuka</div>
                <div class="small text-muted">Pastikan menutup sesi setelah kelas selesai agar rekapitulasi kehadiran valid.</div>
            </div>
        </div>
        <div class="small fw-semibold text-warning-emphasis">
            <i class="fa-solid fa-qrcode me-1"></i>QR Code Presensi Aktif
        </div>
    </div>
<?php endif; ?>

<!-- Stat Summary Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Kelas Diampu</span>
                <div class="stat-icon-wrap primary"><i class="fa-solid fa-chalkboard-user"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= count($kelas) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Mata kuliah aktif</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Mahasiswa</span>
                <div class="stat-icon-wrap info"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalPeserta) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Peserta seluruh kelas</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Pertemuan</span>
                <div class="stat-icon-wrap success"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 text-dark"><?= number_format((int) $totalPertemuan) ?></div>
            <div class="text-muted small" style="font-size: 0.75rem;">Tatap muka terlaksana</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card-modern">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Sesi Live</span>
                <div class="stat-icon-wrap warning"><i class="fa-solid fa-qrcode"></i></div>
            </div>
            <div class="fs-4 fw-bold mb-0 <?= $totalSesiBuka > 0 ? 'text-warning' : 'text-dark' ?>">
                <?= (int) $totalSesiBuka ?>
            </div>
            <div class="text-muted small" style="font-size: 0.75rem;">Sesi QR aktif saat ini</div>
        </div>
    </div>
</div>

<!-- Jadwal Hari Ini Section -->
<div class="card mb-4 border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded p-1 bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <span class="fw-bold">Jadwal Perkuliahan Hari Ini (<?= esc($hariIni) ?>)</span>
        </div>
        <span class="badge badge-soft-warning px-2.5 py-1">
            <?= count($jadwalHariIni) ?> Kelas
        </span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($jadwalHariIni)): ?>
            <div class="p-4 text-center text-muted">
                <div class="avatar-lg mx-auto mb-2 text-muted opacity-50">
                    <i class="fa-solid fa-mug-hot fa-2x"></i>
                </div>
                <div class="fw-medium">Tidak ada jadwal perkuliahan untuk hari ini (<?= esc($hariIni) ?>).</div>
                <small class="text-muted">Gunakan waktu ini untuk memeriksa rekap absensi atau menyiapkan materi kuliah.</small>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mata Kuliah & Kelas</th>
                            <th>Jam Kuliah</th>
                            <th>Ruangan</th>
                            <th>Peserta</th>
                            <th>Status Sesi</th>
                            <th class="text-end">Aksi</th>
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
                                    <div class="d-flex align-items-center gap-1.5 text-dark fw-medium small">
                                        <i class="fa-regular fa-clock text-muted"></i>
                                        <?= esc(substr($k['jam_mulai'] ?? '', 0, 5)) ?> - <?= esc(substr($k['jam_selesai'] ?? '', 0, 5)) ?> WIT
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fa-solid fa-door-open me-1 text-muted"></i><?= esc($k['ruangan'] ?? 'R. Kuliah') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info">
                                        <i class="fa-solid fa-user-group me-1"></i><?= (int) ($k['jml_peserta'] ?? 0) ?> Mahasiswa
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($k['pertemuan_aktif'])): ?>
                                        <span class="badge badge-soft-success">
                                            <span class="pulse-dot me-1" style="width: 6px; height: 6px;"></span>
                                            Sesi P-<?= (int) $k['pertemuan_aktif']['pertemuan_ke'] ?> Dibuka
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted">Belum Dimulai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <?php if (!empty($k['pertemuan_aktif'])): ?>
                                        <a href="<?= site_url('dosen/pertemuan/' . $k['pertemuan_aktif']['id'] . '/scan') ?>" class="btn btn-success btn-sm px-2.5 shadow-sm me-1" title="Scan Kamera Mahasiswa">
                                            <i class="fa-solid fa-camera me-1"></i>Scan di Kelas
                                        </a>
                                        <a href="<?= site_url('dosen/pertemuan/' . $k['id']) ?>" class="btn btn-outline-primary btn-sm px-2.5 shadow-sm" title="Kelola Sesi">
                                            <i class="fa-solid fa-folder-open"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('dosen/pertemuan/' . $k['id'] . '/buka') ?>" class="btn btn-primary btn-sm px-3 shadow-sm">
                                            <i class="fa-solid fa-play me-1"></i>Buka Sesi
                                        </a>
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

<!-- Semua Kelas Saya Section -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded p-1 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <span class="fw-bold">Semua Kelas yang Diampu</span>
        </div>
        <span class="badge badge-soft-primary"><?= count($kelas) ?> Total Kelas</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($kelas)): ?>
            <div class="p-5 text-center text-muted">
                <i class="fa-solid fa-folder-open fa-2x mb-2 text-black-50"></i>
                <div class="fw-medium">Belum ada kelas yang terdaftar atas nama Anda.</div>
                <small class="text-muted">Silakan hubungi administrator akademik jika ada kelas yang belum dihubungkan.</small>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mata Kuliah & Kode</th>
                            <th>Hari & Jadwal</th>
                            <th>Ruangan</th>
                            <th class="text-center">Peserta</th>
                            <th class="text-center">Pertemuan</th>
                            <th>Status Terkini</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kelas as $k): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($k['nama_mk']) ?></div>
                                    <div class="small text-muted">
                                        <span class="badge bg-secondary-subtle text-secondary me-1"><?= esc($k['kode_mk']) ?></span>
                                        Kelas <?= esc($k['nama_kelas'] ?? '-') ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark small"><?= esc($k['hari'] ?? '-') ?></div>
                                    <small class="text-muted">
                                        <?= esc(substr($k['jam_mulai'] ?? '', 0, 5)) ?> - <?= esc(substr($k['jam_selesai'] ?? '', 0, 5)) ?> WIT
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= esc($k['ruangan'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold text-dark"><?= (int) $k['jml_peserta'] ?></span>
                                    <div class="text-muted small" style="font-size: 0.7rem;">Mhs</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary fw-bold">
                                        <?= (int) $k['jml_pertemuan'] ?> / 16
                                    </span>
                                </td>
                                <td>
                                    <?php if ($k['pertemuan_aktif'] !== null): ?>
                                        <span class="badge badge-soft-success">
                                            <span class="pulse-dot me-1" style="width: 6px; height: 6px;"></span>
                                            Buka · P-<?= (int) $k['pertemuan_aktif']['pertemuan_ke'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted">Standby</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <?php if ($k['pertemuan_aktif'] !== null): ?>
                                        <a href="<?= site_url('dosen/pertemuan/' . $k['pertemuan_aktif']['id'] . '/scan') ?>" class="btn btn-success btn-sm px-2.5 me-1" title="Scan Kamera">
                                            <i class="fa-solid fa-camera"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= site_url('dosen/pertemuan/' . $k['id']) ?>" class="btn btn-outline-primary btn-sm px-3">
                                        <i class="fa-solid fa-folder-open me-1"></i>Detail Kelas
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
