<?php
/**
 * @var array<string, mixed> $kelas
 * @var array<string, mixed> $pertemuan
 * @var list<array<string, mixed>> $peserta
 * @var array<int, array<string, mixed>> $statusMap
 * @var int $hadirCount
 * @var int $totalPeserta
 * @var float|int $persenHadir
 * @var int $sisaDetik
 * @var bool $isExpired
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header Info & Waktu Presensi -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary px-2.5 py-1 fw-bold">Pertemuan Ke-<?= (int) $pertemuan['pertemuan_ke'] ?></span>
            <span class="badge <?= $pertemuan['status'] === 'open' ? 'bg-success' : 'bg-secondary' ?>" id="badge-status-pertemuan">
                <?= ucfirst($pertemuan['status']) ?>
            </span>
            <span class="text-muted small">· <?= esc($pertemuan['tanggal'] ?? date('Y-m-d')) ?></span>
        </div>
        <h4 class="fw-bold mb-0 text-dark"><?= esc($kelas['nama_mk']) ?></h4>
        <small class="text-muted">
            Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['hari'] ?? '') ?> <?= esc(substr($kelas['jam_mulai'] ?? '', 0, 5)) ?>-<?= esc(substr($kelas['jam_selesai'] ?? '', 0, 5)) ?> WIT · <?= esc($kelas['ruangan'] ?? '-') ?>
        </small>
    </div>

    <!-- Countdown & Quick Action Buttons -->
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Live Countdown Timer Card -->
        <div class="card border-0 shadow-sm px-3 py-1.5 d-flex flex-row align-items-center gap-2.5 rounded-3 bg-white" id="countdown-card">
            <div class="rounded-circle d-flex align-items-center justify-content-center" id="timer-icon-wrap" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                <i class="fa-solid fa-stopwatch" id="timer-icon"></i>
            </div>
            <div>
                <div class="text-muted" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Batas Waktu Absen</div>
                <div class="fw-bold fs-6 font-monospace" id="countdown-timer" style="line-height: 1.1;">
                    --:--
                </div>
            </div>
        </div>

        <!-- Perpanjang Waktu Dropdown / Buttons -->
        <div class="btn-group shadow-sm">
            <button type="button" class="btn btn-outline-primary dropdown-toggle fw-semibold" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-clock-rotate-left me-1"></i>+ Tambah Waktu
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><h6 class="dropdown-header">Perpanjang Waktu Absen</h6></li>
                <li><a class="dropdown-item btn-extend-time" href="javascript:void(0)" data-menit="5"><i class="fa-solid fa-plus me-2 text-primary"></i>+ 5 Menit</a></li>
                <li><a class="dropdown-item btn-extend-time" href="javascript:void(0)" data-menit="10"><i class="fa-solid fa-plus me-2 text-primary"></i>+ 10 Menit</a></li>
                <li><a class="dropdown-item btn-extend-time" href="javascript:void(0)" data-menit="15"><i class="fa-solid fa-plus me-2 text-primary"></i>+ 15 Menit</a></li>
                <li><a class="dropdown-item btn-extend-time" href="javascript:void(0)" data-menit="30"><i class="fa-solid fa-plus me-2 text-primary"></i>+ 30 Menit</a></li>
            </ul>
        </div>

        <!-- Tombol Pindah Layar Proyektor & Kelola -->
        <a href="<?= site_url('dosen/pertemuan/' . $kelas['id'] . '/qr/' . $pertemuan['id']) ?>" class="btn btn-outline-secondary shadow-sm" title="Buka Mode Layar Proyektor">
            <i class="fa-solid fa-tv me-1"></i>Proyektor
        </a>
        <a href="<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/kelola') ?>" class="btn btn-outline-secondary shadow-sm" title="Kelola Manual">
            <i class="fa-solid fa-user-check me-1"></i>Kelola
        </a>
    </div>
</div>

<!-- Alert Waktu Habis Banner (Hidden by default, shown when expired) -->
<div id="alert-expired-banner" class="alert alert-danger border-0 shadow-sm d-none align-items-center justify-content-between flex-wrap gap-2 mb-4 p-3 rounded-3">
    <div class="d-flex align-items-center gap-2.5">
        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <div class="fw-bold">Batas Waktu Presensi Telah Berakhir!</div>
            <div class="small">Scan kamera dinonaktifkan sementara. Klik tombol di samping untuk menambah waktu absensi.</div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-danger btn-sm fw-semibold btn-extend-time" data-menit="15">
            <i class="fa-solid fa-plus me-1"></i>Perpanjang +15 Menit
        </button>
        <button class="btn btn-outline-danger btn-sm fw-semibold btn-extend-time" data-menit="30">
            +30 Menit
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Kamera Scanner & Input Manual -->
    <div class="col-lg-6 col-xl-5">
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-1 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <span class="fw-bold text-dark">Kamera Scanner di Kelas</span>
                </div>
                <!-- Sound Toggle -->
                <button type="button" id="btn-sound-toggle" class="btn btn-sm btn-light border text-muted" title="Suara Nada Beep">
                    <i class="fa-solid fa-volume-high text-success" id="sound-icon"></i>
                    <span class="small ms-1 fw-medium" id="sound-label">Suara ON</span>
                </button>
            </div>
            <div class="card-body p-3">
                <!-- Dropdown Pilih Kamera (Kamera Belakang HP / Kamera Depan / Webcam Laptop) -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-1 d-flex justify-content-between">
                        <span><i class="fa-solid fa-video me-1"></i>Pilih Perangkat Kamera:</span>
                        <span id="camera-count-badge" class="badge bg-light text-muted">Mendeteksi kamera...</span>
                    </label>
                    <select id="camera-select" class="form-select form-select-sm">
                        <option value="">Memuat daftar kamera...</option>
                    </select>
                </div>

                <!-- Video Viewport Container -->
                <div class="position-relative bg-dark rounded-3 overflow-hidden text-center" style="min-height: 290px;">
                    <div id="reader" style="width: 100%;"></div>

                    <!-- Overlay Placeholder sebelum kamera aktif -->
                    <div id="camera-placeholder" class="d-flex flex-column align-items-center justify-content-center p-4 text-white position-absolute top-0 start-0 w-100 h-100" style="background: rgba(15, 23, 42, 0.95); z-index: 5;">
                        <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="fa-solid fa-camera fa-2x text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-white mb-1">Kamera Siap Digunakan</h6>
                        <p class="small text-white-50 text-center mb-3" style="max-width: 280px;">
                            Klik tombol di bawah untuk menyalakan kamera HP atau laptop Dosen untuk men-scan QR mahasiswa.
                        </p>
                        <button id="btn-start-camera" class="btn btn-primary px-4 py-2 fw-semibold shadow">
                            <i class="fa-solid fa-play me-1.5"></i>Nyalakan Kamera
                        </button>
                    </div>

                    <!-- Target Scanner Box Frame (saat kamera jalan) -->
                    <div id="scanner-frame-overlay" class="position-absolute top-50 start-50 translate-middle pointer-events-none d-none" style="width: 220px; height: 220px; border: 2.5px solid #38bdf8; border-radius: 16px; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.35); z-index: 4;">
                        <div class="scanner-scan-line"></div>
                    </div>
                </div>

                <!-- Kontrol Kamera Bawah -->
                <div class="d-flex justify-content-between align-items-center mt-3 gap-2">
                    <button id="btn-stop-camera" class="btn btn-outline-danger btn-sm d-none fw-semibold">
                        <i class="fa-solid fa-stop me-1"></i>Hentikan Kamera
                    </button>
                    <button id="btn-switch-camera" class="btn btn-outline-secondary btn-sm d-none" title="Ganti Kamera">
                        <i class="fa-solid fa-rotate me-1"></i>Ganti Kamera
                    </button>
                    <span id="scan-status-text" class="small text-muted flex-grow-1 text-center">
                        Siap memindai QR mahasiswa
                    </span>
                </div>
            </div>

            <!-- Fallback Input Manual NIM Mahasiswa -->
            <div class="card-footer bg-light p-3 border-top">
                <div class="d-flex align-items-center justify-content-between mb-1.5">
                    <label class="form-label small fw-semibold text-dark mb-0">
                        <i class="fa-solid fa-keyboard me-1 text-muted"></i>Input Manual NIM Mahasiswa
                    </label>
                    <small class="text-muted">Jika HP mahasiswa mati/layar pecah</small>
                </div>
                <form id="form-manual-nim" class="d-flex gap-2">
                    <input type="text" id="input-manual-nim" class="form-control form-control-sm" placeholder="Ketik NIM mahasiswa..." autocomplete="off">
                    <button type="submit" id="btn-submit-manual" class="btn btn-sm btn-primary px-3 text-nowrap fw-semibold">
                        <i class="fa-solid fa-check me-1"></i>Catat Hadir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Hasil Scan Terkini & Live Counter & Roster -->
    <div class="col-lg-6 col-xl-7">
        <!-- Live Scan Feedback Banner (Menampilkan Mahasiswa Terakhir Discan) -->
        <div id="scan-feedback-box" class="card border-0 shadow-sm mb-4 transition-all" style="background: #f8fafc; border-left: 5px solid #cbd5e1 !important;">
            <div class="card-body p-3.5">
                <div class="d-flex align-items-center gap-3" id="feedback-content">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" id="feedback-avatar" style="width: 52px; height: 52px; background: #64748b; font-size: 1.5rem;">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-0.5">
                            <span class="badge bg-secondary" id="feedback-badge">Standby</span>
                            <small class="text-muted" id="feedback-time">Menunggu scan mahasiswa...</small>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark" id="feedback-name">Siap Memindai QR Code</h5>
                        <div class="small text-muted" id="feedback-meta">
                            Arahkan kamera ke QR Code yang ditampilkan pada HP mahasiswa.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Counter Kehadiran -->
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Hadir</span>
                    <div class="fs-3 fw-bold text-success mb-0" id="cnt-hadir"><?= (int) $hadirCount ?></div>
                    <small class="text-muted" style="font-size: 0.72rem;">Mahasiswa</small>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Belum Hadir</span>
                    <div class="fs-3 fw-bold text-secondary mb-0" id="cnt-belum"><?= max(0, (int) $totalPeserta - (int) $hadirCount) ?></div>
                    <small class="text-muted" style="font-size: 0.72rem;">Mahasiswa</small>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow-sm p-3 text-center bg-white rounded-3">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Total Peserta</span>
                    <div class="fs-3 fw-bold text-dark mb-0" id="cnt-total"><?= (int) $totalPeserta ?></div>
                    <small class="text-muted" style="font-size: 0.72rem;">Terdaftar</small>
                </div>
            </div>
        </div>

        <!-- Progress Bar Persentase Kehadiran -->
        <div class="card border-0 shadow-sm p-3 mb-4 bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-1.5 small fw-semibold">
                <span class="text-muted">Tingkat Kehadiran Kelas</span>
                <span class="text-primary fw-bold" id="progress-percent"><?= (float) $persenHadir ?>%</span>
            </div>
            <div class="progress" style="height: 10px; border-radius: 999px;">
                <div id="progress-bar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= (float) $persenHadir ?>%;"></div>
            </div>
        </div>

        <!-- Tab Roster Peserta Kelas & Aktivitas Terakhir -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-2.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <ul class="nav nav-pills card-header-pills" id="rosterTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-1.5 px-3 fw-semibold small" id="feed-tab" data-bs-toggle="pill" data-bs-target="#tab-feed" type="button" role="tab">
                            <i class="fa-solid fa-list-check me-1"></i>Aktivitas Scan (<span id="feed-count">0</span>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1.5 px-3 fw-semibold small" id="all-tab" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab">
                            <i class="fa-solid fa-users me-1"></i>Daftar Peserta (<?= count($peserta) ?>)
                        </button>
                    </li>
                </ul>

                <!-- Filter Pencarian Peserta -->
                <div style="max-width: 200px;">
                    <input type="text" id="roster-search" class="form-control form-control-sm" placeholder="Cari nama / NIM...">
                </div>
            </div>

            <div class="card-body p-0">
                <div class="tab-content" id="rosterTabsContent">
                    <!-- Tab 1: Live Feed Scan Terakhir -->
                    <div class="tab-pane fade show active" id="tab-feed" role="tabpanel">
                        <div id="live-feed-list" class="list-group list-group-flush" style="max-height: 380px; overflow-y: auto;">
                            <div class="p-4 text-center text-muted" id="feed-empty-state">
                                <i class="fa-solid fa-qrcode fa-2x mb-2 text-muted opacity-50"></i>
                                <div class="small fw-medium">Belum ada mahasiswa yang discan pada sesi ini.</div>
                                <span class="small text-muted">Scan QR mahasiswa menggunakan kamera untuk mencatat kehadiran.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Seluruh Peserta Kelas -->
                    <div class="tab-pane fade" id="tab-all" role="tabpanel">
                        <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" id="table-peserta-kelas">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>NIM</th>
                                        <th class="text-end">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($peserta)): ?>
                                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada peserta di kelas ini.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($peserta as $p): ?>
                                            <?php
                                            $curPresensi = $statusMap[(int) $p['id']] ?? null;
                                            $isHadir = $curPresensi !== null && $curPresensi['status'] === 'Hadir';
                                            ?>
                                            <tr data-mhs-id="<?= (int) $p['id'] ?>" data-nim="<?= esc($p['nim']) ?>" data-nama="<?= esc(strtolower($p['nama_lengkap'])) ?>">
                                                <td>
                                                    <div class="fw-semibold text-dark"><?= esc($p['nama_lengkap']) ?></div>
                                                    <small class="text-muted"><?= esc($p['kelas_paralel'] ?? '-') ?></small>
                                                </td>
                                                <td class="font-monospace small"><?= esc($p['nim']) ?></td>
                                                <td class="text-end" id="roster-status-<?= (int) $p['id'] ?>">
                                                    <?php if ($isHadir): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                                            <i class="fa-solid fa-circle-check me-1"></i>Hadir (<?= esc(substr($curPresensi['waktu_presensi'] ?? '', 11, 5)) ?>)
                                                        </span>
                                                    <?php elseif ($curPresensi !== null): ?>
                                                        <?= status_badge($curPresensi['status']) ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border px-2.5 py-1">
                                                            Belum Absen
                                                        </span>
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
        </div>
    </div>
</div>

<style>
.scanner-scan-line {
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, transparent, #38bdf8, #0284c7, transparent);
    position: absolute;
    top: 10px;
    animation: scannerLine 2s infinite ease-in-out;
}
@keyframes scannerLine {
    0% { top: 10px; opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { top: 200px; opacity: 0; }
}
.pulse-glow {
    animation: pulseGlow 1.5s infinite;
}
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(2, 132, 199, 0); }
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('vendor/html5-qrcode.min.js') ?>"></script>
<script>
(function () {
    const pertemuanId   = <?= (int) $pertemuan['id'] ?>;
    const csrfMeta      = document.querySelector('meta[name="csrf-token"]');
    let sisaDetik       = <?= (int) $sisaDetik ?>;
    let isExpired       = <?= $isExpired ? 'true' : 'false' ?>;
    let soundEnabled    = true;
    let scanner         = null;
    let currentCameraId = null;
    let isScanningBusy  = false;
    let recentScanCache = new Set(); // Cegah duplicate trigger cepat dari stream video
    let feedCount       = 0;

    // Web Audio API Synthesizer (Bebas file eksternal, bekerja offline)
    let audioCtx = null;
    function getAudioContext() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    function playBeepSuccess() {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioContext();
            const now = ctx.currentTime;
            // Tone 1: C5 (523Hz)
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(523.25, now);
            gain1.gain.setValueAtTime(0.15, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.12);

            // Tone 2: G5 (783Hz) - melodius naik
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(783.99, now + 0.1);
            gain2.gain.setValueAtTime(0.18, now + 0.1);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.28);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.1);
            osc2.stop(now + 0.28);

            if (navigator.vibrate) navigator.vibrate([80, 50, 80]);
        } catch (e) {
            console.error('Audio beep error:', e);
        }
    }

    function playBeepWarning() {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioContext();
            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(260, now);
            gain.gain.setValueAtTime(0.2, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.25);

            if (navigator.vibrate) navigator.vibrate(200);
        } catch (e) {
            console.error('Audio error:', e);
        }
    }

    // Toggle Suara
    const btnSound = document.getElementById('btn-sound-toggle');
    const soundIcon = document.getElementById('sound-icon');
    const soundLabel = document.getElementById('sound-label');
    btnSound.addEventListener('click', function () {
        soundEnabled = !soundEnabled;
        if (soundEnabled) {
            soundIcon.className = 'fa-solid fa-volume-high text-success';
            soundLabel.textContent = 'Suara ON';
        } else {
            soundIcon.className = 'fa-solid fa-volume-xmark text-danger';
            soundLabel.textContent = 'Suara OFF';
        }
    });

    // Countdown Timer Logic
    const timerEl = document.getElementById('countdown-timer');
    const timerCard = document.getElementById('countdown-card');
    const timerIconWrap = document.getElementById('timer-icon-wrap');
    const expiredBanner = document.getElementById('alert-expired-banner');

    function updateCountdownDisplay() {
        if (sisaDetik <= 0) {
            isExpired = true;
            timerEl.textContent = '00:00 (Habis)';
            timerEl.classList.add('text-danger');
            timerCard.style.background = '#fef2f2';
            timerIconWrap.style.background = '#fee2e2';
            timerIconWrap.style.color = '#ef4444';
            expiredBanner.classList.remove('d-none');
            expiredBanner.classList.add('d-flex');
            return;
        }

        expiredBanner.classList.add('d-none');
        expiredBanner.classList.remove('d-flex');

        const m = Math.floor(sisaDetik / 60);
        const s = sisaDetik % 60;
        const mm = String(m).padStart(2, '0');
        const ss = String(s).padStart(2, '0');
        timerEl.textContent = `${mm}:${ss}`;

        if (sisaDetik <= 300) { // < 5 menit -> kuning/oranye
            timerEl.className = 'fw-bold fs-6 font-monospace text-warning';
            timerCard.style.background = '#fffbeb';
            timerIconWrap.style.background = '#fef3c7';
            timerIconWrap.style.color = '#d97706';
        } else {
            timerEl.className = 'fw-bold fs-6 font-monospace text-primary';
            timerCard.style.background = '#fff';
            timerIconWrap.style.background = '#e0f2fe';
            timerIconWrap.style.color = '#0284c7';
        }
    }

    setInterval(() => {
        if (sisaDetik > 0) {
            sisaDetik -= 1;
            updateCountdownDisplay();
        }
    }, 1000);
    updateCountdownDisplay();

    // Perpanjang Waktu API
    document.querySelectorAll('.btn-extend-time').forEach(btn => {
        btn.addEventListener('click', async function () {
            const menit = this.dataset.menit || 15;
            try {
                const res = await fetch('<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/perpanjang-waktu') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                    },
                    body: JSON.stringify({ tambah_menit: menit })
                });
                const json = await res.json();
                if (json.csrf_hash) csrfMeta.setAttribute('content', json.csrf_hash);

                if (json.success) {
                    sisaDetik = json.sisa_detik;
                    isExpired = false;
                    updateCountdownDisplay();

                    // Toast / visual notification
                    showNotification('Waktu presensi berhasil diperpanjang +' + menit + ' menit!', 'success');
                } else {
                    alert(json.message || 'Gagal memperpanjang waktu.');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi.');
            }
        });
    });

    // Inisialisasi Deteksi Kamera
    const cameraSelect = document.getElementById('camera-select');
    const cameraCountBadge = document.getElementById('camera-count-badge');

    Html5Qrcode.getCameras().then(cameras => {
        cameraSelect.innerHTML = '';
        if (cameras && cameras.length > 0) {
            cameraCountBadge.textContent = cameras.length + ' Kamera Ditemukan';
            cameraCountBadge.className = 'badge bg-success-subtle text-success';

            // Utamakan kamera belakang (environment) jika ada
            let preferredCamera = cameras[0].id;
            cameras.forEach((cam, idx) => {
                const opt = document.createElement('option');
                opt.value = cam.id;
                opt.text = cam.label || ('Kamera ' + (idx + 1));
                const labelLower = (cam.label || '').toLowerCase();
                if (labelLower.includes('back') || labelLower.includes('belakang') || labelLower.includes('rear') || labelLower.includes('environment')) {
                    opt.selected = true;
                    preferredCamera = cam.id;
                }
                cameraSelect.appendChild(opt);
            });
            currentCameraId = preferredCamera;
        } else {
            cameraSelect.innerHTML = '<option value="">Tidak ada kamera terdeteksi</option>';
            cameraCountBadge.textContent = 'Tidak Ada Kamera';
            cameraCountBadge.className = 'badge bg-danger-subtle text-danger';
        }
    }).catch(err => {
        console.error('Camera detection error:', err);
        cameraSelect.innerHTML = '<option value="">Izin kamera belum aktif</option>';
        cameraCountBadge.textContent = 'Perlu Izin Kamera';
    });

    // Tombol Mulai Kamera
    const btnStart = document.getElementById('btn-start-camera');
    const btnStop  = document.getElementById('btn-stop-camera');
    const btnSwitch= document.getElementById('btn-switch-camera');
    const placeholder = document.getElementById('camera-placeholder');
    const frameOverlay = document.getElementById('scanner-frame-overlay');
    const scanStatusText = document.getElementById('scan-status-text');

    async function startScanning(cameraId) {
        if (scanner) {
            try { await scanner.stop(); } catch (e) {}
        }
        scanner = new Html5Qrcode('reader');

        const config = {
            fps: 15,
            qrbox: { width: 230, height: 230 },
            aspectRatio: 1.0
        };

        const targetCamera = cameraId || (cameraSelect.value ? cameraSelect.value : { facingMode: 'environment' });

        scanner.start(
            targetCamera,
            config,
            (decodedText) => {
                handleScannedQr(decodedText);
            },
            (errorMessage) => {
                // Ignore parse frames
            }
        ).then(() => {
            placeholder.classList.add('d-none');
            frameOverlay.classList.remove('d-none');
            btnStart.classList.add('d-none');
            btnStop.classList.remove('d-none');
            btnSwitch.classList.remove('d-none');
            scanStatusText.innerHTML = '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-dot fa-fade me-1"></i>Kamera Aktif · Arahkan ke QR mahasiswa</span>';
        }).catch(err => {
            console.error('Start camera error:', err);
            scanStatusText.innerHTML = '<span class="text-danger">Gagal membuka kamera: ' + err + '</span>';
        });
    }

    btnStart.addEventListener('click', () => {
        startScanning(cameraSelect.value);
    });

    btnStop.addEventListener('click', async () => {
        if (!scanner) return;
        try {
            await scanner.stop();
            scanner = null;
            placeholder.classList.remove('d-none');
            frameOverlay.classList.add('d-none');
            btnStart.classList.remove('d-none');
            btnStop.classList.add('d-none');
            btnSwitch.classList.add('d-none');
            scanStatusText.textContent = 'Kamera dihentikan.';
        } catch (e) {
            console.error(e);
        }
    });

    cameraSelect.addEventListener('change', function () {
        if (scanner) {
            startScanning(this.value);
        }
    });

    btnSwitch.addEventListener('click', function () {
        const opts = cameraSelect.options;
        if (opts.length > 1) {
            let nextIndex = (cameraSelect.selectedIndex + 1) % opts.length;
            cameraSelect.selectedIndex = nextIndex;
            startScanning(opts[nextIndex].value);
        }
    });

    // Handle Scanned QR String
    async function handleScannedQr(rawText) {
        if (isScanningBusy) return;

        // Cegah scan ganda bertubi-tubi dalam jeda 3 detik
        if (recentScanCache.has(rawText)) {
            return;
        }

        isScanningBusy = true;
        recentScanCache.add(rawText);
        setTimeout(() => { recentScanCache.delete(rawText); }, 3000);

        await submitPresensiMahasiswa(rawText);
        isScanningBusy = false;
    }

    // Submit Presensi Mahasiswa ke Server
    async function submitPresensiMahasiswa(payload) {
        scanStatusText.innerHTML = '<span class="text-primary fw-semibold"><span class="spinner-border spinner-border-sm me-1"></span>Memverifikasi presensi...</span>';

        try {
            const res = await fetch('<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/scan-mahasiswa') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                },
                body: JSON.stringify({ qr_data: payload })
            });
            const json = await res.json();
            if (json.csrf_hash) csrfMeta.setAttribute('content', json.csrf_hash);

            if (json.success) {
                // BERHASIL PRESENSI
                playBeepSuccess();
                renderFeedbackSuccess(json);
                updateStats(json.stats);
                addLiveFeed(json.mahasiswa, json.waktu, 'Hadir');
                updateRosterRow(json.mahasiswa.id, 'Hadir', json.waktu);
            } else if (json.duplicate) {
                // SUDAH HADIR SEBELUMNYA
                playBeepWarning();
                renderFeedbackDuplicate(json);
            } else if (json.expired) {
                // WAKTU HABIS
                playBeepWarning();
                renderFeedbackExpired(json);
            } else {
                // TIDAK TERDAFTAR / ERROR LAIN
                playBeepWarning();
                renderFeedbackError(json);
            }
        } catch (e) {
            console.error('Submit error:', e);
            playBeepWarning();
            renderFeedbackError({ message: 'Terjadi gangguan jaringan saat memverifikasi presensi.' });
        } finally {
            setTimeout(() => {
                if (scanner) {
                    scanStatusText.innerHTML = '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-dot fa-fade me-1"></i>Kamera Aktif · Siap scan berikutnya</span>';
                }
            }, 1000);
        }
    }

    // Form Manual NIM Submit
    const formManual = document.getElementById('form-manual-nim');
    const inputManual = document.getElementById('input-manual-nim');
    const btnSubmitManual = document.getElementById('btn-submit-manual');

    formManual.addEventListener('submit', async function (e) {
        e.preventDefault();
        const nim = inputManual.value.trim();
        if (!nim) return;

        btnSubmitManual.disabled = true;
        btnSubmitManual.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        await submitPresensiMahasiswa(nim);

        inputManual.value = '';
        btnSubmitManual.disabled = false;
        btnSubmitManual.innerHTML = '<i class="fa-solid fa-check me-1"></i>Catat Hadir';
    });

    // Render Visual Feedback Cards
    const feedbackBox    = document.getElementById('scan-feedback-box');
    const feedbackAvatar = document.getElementById('feedback-avatar');
    const feedbackBadge  = document.getElementById('feedback-badge');
    const feedbackTime   = document.getElementById('feedback-time');
    const feedbackName   = document.getElementById('feedback-name');
    const feedbackMeta   = document.getElementById('feedback-meta');

    function renderFeedbackSuccess(json) {
        feedbackBox.style.background = '#f0fdf4';
        feedbackBox.style.borderLeft = '5px solid #22c55e !important';
        feedbackAvatar.style.background = '#22c55e';
        feedbackAvatar.innerHTML = '<i class="fa-solid fa-check"></i>';
        feedbackBadge.className = 'badge bg-success';
        feedbackBadge.textContent = 'BERHASIL HADIR';
        feedbackTime.textContent = 'Pukul ' + json.waktu + ' WIT';
        feedbackName.textContent = json.mahasiswa.nama_lengkap;
        feedbackMeta.innerHTML = `<span class="fw-semibold text-dark">NIM: ${json.mahasiswa.nim}</span> · ${json.mahasiswa.prodi || 'FISIP'} (Kelas ${json.mahasiswa.kelas_paralel || '-'})`;
    }

    function renderFeedbackDuplicate(json) {
        feedbackBox.style.background = '#fffbeb';
        feedbackBox.style.borderLeft = '5px solid #f59e0b !important';
        feedbackAvatar.style.background = '#f59e0b';
        feedbackAvatar.innerHTML = '<i class="fa-solid fa-clock-rotate-left"></i>';
        feedbackBadge.className = 'badge bg-warning text-dark';
        feedbackBadge.textContent = 'SUDAH HADIR';
        feedbackTime.textContent = 'Tercatat ' + (json.waktu || '');
        feedbackName.textContent = json.mahasiswa ? json.mahasiswa.nama_lengkap : 'Mahasiswa Sudah Absen';
        feedbackMeta.innerHTML = `<span class="text-warning-emphasis fw-medium">${json.message}</span>`;
    }

    function renderFeedbackExpired(json) {
        feedbackBox.style.background = '#fef2f2';
        feedbackBox.style.borderLeft = '5px solid #ef4444 !important';
        feedbackAvatar.style.background = '#ef4444';
        feedbackAvatar.innerHTML = '<i class="fa-solid fa-hourglass-end"></i>';
        feedbackBadge.className = 'badge bg-danger';
        feedbackBadge.textContent = 'WAKTU HABIS';
        feedbackTime.textContent = 'Presensi Ditutup';
        feedbackName.textContent = 'Batas Waktu Presensi Telah Berakhir';
        feedbackMeta.innerHTML = `<span class="text-danger">${json.message}</span>`;
    }

    function renderFeedbackError(json) {
        feedbackBox.style.background = '#fef2f2';
        feedbackBox.style.borderLeft = '5px solid #ef4444 !important';
        feedbackAvatar.style.background = '#ef4444';
        feedbackAvatar.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        feedbackBadge.className = 'badge bg-danger';
        feedbackBadge.textContent = 'GAGAL / DITOLAK';
        feedbackTime.textContent = 'Verifikasi Gagal';
        feedbackName.textContent = json.mahasiswa ? json.mahasiswa.nama_lengkap : 'Presensi Tidak Terdaftar';
        feedbackMeta.innerHTML = `<span class="text-danger fw-medium">${json.message || 'Data tidak sesuai dengan mata kuliah ini.'}</span>`;
    }

    // Update Counter & Progress Bar
    function updateStats(stats) {
        if (!stats) return;
        document.getElementById('cnt-hadir').textContent = stats.hadir_count;
        document.getElementById('cnt-belum').textContent = Math.max(0, stats.total_peserta - stats.hadir_count);
        document.getElementById('cnt-total').textContent = stats.total_peserta;
        document.getElementById('progress-percent').textContent = stats.persen + '%';
        document.getElementById('progress-bar').style.width = stats.persen + '%';
    }

    // Tambahkan item ke live feed
    const liveFeedList   = document.getElementById('live-feed-list');
    const feedEmptyState = document.getElementById('feed-empty-state');
    const feedCountEl    = document.getElementById('feed-count');

    function addLiveFeed(mhs, waktu, status) {
        if (feedEmptyState) {
            feedEmptyState.remove();
        }

        feedCount++;
        feedCountEl.textContent = feedCount;

        const initials = (mhs.nama_lengkap || 'M').substring(0, 2).toUpperCase();
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex align-items-center justify-content-between py-2.5 px-3 border-bottom';
        item.innerHTML = `
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold small" style="width: 36px; height: 36px; font-size: 0.8rem;">
                    ${initials}
                </div>
                <div>
                    <div class="fw-bold text-dark small mb-0">${mhs.nama_lengkap}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">NIM: ${mhs.nim} · ${mhs.prodi || 'FISIP'}</div>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="fa-solid fa-check me-1"></i>Hadir
                </span>
                <div class="text-muted" style="font-size: 0.7rem;">${waktu} WIT</div>
            </div>
        `;

        liveFeedList.insertBefore(item, liveFeedList.firstChild);
    }

    // Update baris roster pada tab Semua Peserta
    function updateRosterRow(mhsId, status, waktu) {
        const cell = document.getElementById('roster-status-' + mhsId);
        if (cell) {
            cell.innerHTML = `
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                    <i class="fa-solid fa-circle-check me-1"></i>Hadir (${waktu})
                </span>
            `;
        }
    }

    // Filter Pencarian Roster
    const searchInput = document.getElementById('roster-search');
    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#table-peserta-kelas tbody tr');
        rows.forEach(row => {
            const nama = row.getAttribute('data-nama') || '';
            const nim  = row.getAttribute('data-nim') || '';
            if (nama.includes(query) || nim.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Helper Notification Toast
    function showNotification(msg, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} shadow position-fixed top-0 end-0 m-3 py-2 px-3 rounded-3 z-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = `<i class="fa-solid fa-circle-check me-1.5"></i>${msg}`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3500);
    }
})();
</script>
<?= $this->endSection() ?>
