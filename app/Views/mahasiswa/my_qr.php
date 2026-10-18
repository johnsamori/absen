<?php
/**
 * @var array<string, mixed> $mhs
 * @var string $qrData
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <!-- Kartu Utama QR Code Mahasiswa -->
        <div class="card border-0 shadow-lg text-center overflow-hidden" id="qr-display-card" style="border-radius: 20px;">
            <!-- Header Kartu -->
            <div class="py-4 px-3 text-white position-relative" style="background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);">
                <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white small fw-semibold mb-2">
                    <i class="fa-solid fa-qrcode text-warning"></i>
                    <span>QR CODE PRESENSI MAHASISWA</span>
                </div>
                <h4 class="fw-bold mb-1 text-white"><?= esc($mhs['nama_lengkap'] ?? session()->get('nama')) ?></h4>
                <div class="text-white-50 small">
                    NIM: <span class="text-white fw-bold font-monospace fs-6"><?= esc($mhs['nim'] ?? '-') ?></span>
                </div>
                <div class="small text-white-50 mt-0.5">
                    <?= esc($mhs['nama_prodi'] ?? 'FISIP UNCEN') ?> · Angkatan <?= esc($mhs['angkatan'] ?? '-') ?> (Kelas <?= esc($mhs['kelas_paralel'] ?? '-') ?>)
                </div>
            </div>

            <div class="card-body p-4 bg-white d-flex flex-column align-items-center">
                <!-- Live Jam Digital WIT (Bukti Keaslian Layar Realtime) -->
                <div class="d-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-light border text-muted small mb-3">
                    <span class="pulse-dot bg-success" style="width: 8px; height: 8px;"></span>
                    <span class="font-monospace fw-semibold text-dark" id="digital-clock">--:--:-- WIT</span>
                    <span>·</span>
                    <span id="digital-date"><?= date('d M Y') ?></span>
                </div>

                <!-- Kontainer QR Code -->
                <div class="p-3 bg-white rounded-4 shadow-sm border mb-3 d-flex align-items-center justify-content-center" style="width: 280px; height: 280px; max-width: 100%;">
                    <div id="student-qrcode"></div>
                </div>

                <!-- Nilai Token / NIM Terenkripsi -->
                <div class="font-monospace small text-muted bg-light px-3 py-1 rounded border mb-3">
                    Kode Scan: <strong class="text-dark"><?= esc($mhs['nim']) ?></strong>
                </div>

                <!-- Info Petunjuk Scan -->
                <div class="alert alert-info border-0 rounded-3 text-start small mb-3 w-100" style="background: #f0f9ff; color: #0369a1;">
                    <div class="d-flex gap-2">
                        <i class="fa-solid fa-circle-info fs-5 text-primary mt-0.5"></i>
                        <div>
                            <strong>Petunjuk Presensi di Kelas:</strong>
                            <ul class="ps-3 mb-0 mt-1">
                                <li>Tunjukkan layar QR ini ke arah kamera HP / laptop Dosen.</li>
                                <li>Atur tingkat kecerahan layar HP ke tingkat maksimum.</li>
                                <li>Pastikan layar bersih agar kamera dosen cepat mendeteksi.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tombol Fullscreen & Kembali -->
                <div class="d-flex gap-2 w-100">
                    <button type="button" id="btn-fullscreen" class="btn btn-outline-primary flex-fill fw-semibold py-2">
                        <i class="fa-solid fa-expand me-1.5"></i>Layar Penuh
                    </button>
                    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-primary flex-fill fw-semibold py-2">
                        <i class="fa-solid fa-house me-1.5"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('vendor/qrcode.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const qrData = <?= json_encode($qrData) ?>;
    const qrEl = document.getElementById('student-qrcode');

    // Generate crisp QR Code
    if (qrEl) {
        new QRCode(qrEl, {
            text: qrData,
            width: 250,
            height: 250,
            colorDark: '#0f172a',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    // Jam Digital Realtime
    const clockEl = document.getElementById('digital-clock');
    function updateClock() {
        const now = new Date();
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        if (clockEl) {
            clockEl.textContent = `${hh}:${mm}:${ss} WIT`;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Mode Layar Penuh (Fullscreen)
    const btnFullscreen = document.getElementById('btn-fullscreen');
    const cardEl = document.getElementById('qr-display-card');

    btnFullscreen.addEventListener('click', function () {
        if (!document.fullscreenElement) {
            if (cardEl.requestFullscreen) {
                cardEl.requestFullscreen();
            } else if (cardEl.webkitRequestFullscreen) {
                cardEl.webkitRequestFullscreen();
            }
            btnFullscreen.innerHTML = '<i class="fa-solid fa-compress me-1.5"></i>Keluar Layar Penuh';
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            btnFullscreen.innerHTML = '<i class="fa-solid fa-expand me-1.5"></i>Layar Penuh';
        }
    });

    document.addEventListener('fullscreenchange', function () {
        if (!document.fullscreenElement) {
            btnFullscreen.innerHTML = '<i class="fa-solid fa-expand me-1.5"></i>Layar Penuh';
        }
    });
});
</script>
<?= $this->endSection() ?>
