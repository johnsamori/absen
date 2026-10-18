<?php
/**
 * @var array<string, mixed> $kelas
 * @var array<string, mixed> $pertemuan
 * @var string $scanUrl
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Proyeksi QR Dinamis</h4>
        <small class="text-muted">
            <?= esc($kelas['nama_mk']) ?> — Pertemuan ke-<?= (int) $pertemuan['pertemuan_ke'] ?> ·
            QR berganti otomatis setiap 20 detik
        </small>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/scan') ?>" class="btn btn-primary">
            <i class="fa-solid fa-camera me-1"></i>Scan Kamera di Kelas
        </a>
        <a href="<?= site_url('dosen/pertemuan/' . $kelas['id']) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Kembali
        </a>
        <?php if ($pertemuan['status'] === 'open'): ?>
            <form method="post" action="<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/tutup') ?>"
                  onsubmit="return confirm('Tutup sesi presensi ini?')">
                <?= csrf_field() ?>
                <button class="btn btn-danger"><i class="fa-solid fa-stop me-1"></i>Tutup Sesi</button>
            </form>
        <?php else: ?>
            <form method="post" action="<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/buka-lagi') ?>">
                <?= csrf_field() ?>
                <button class="btn btn-success"><i class="fa-solid fa-rotate me-1"></i>Buka Kembali</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="qr-stage text-center">
            <div class="mb-3">
                <span class="badge text-bg-warning text-dark">Sesi: <?= $pertemuan['status'] === 'open' ? 'OPEN' : 'CLOSED' ?></span>
                <span class="badge text-bg-light text-dark" id="countdown">refresh 20s</span>
            </div>
            <div class="qr-frame">
                <div id="qrcode"></div>
            </div>
            <div class="mt-3 text-white-50 small">Pindai QR untuk presensi</div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header">Live Kehadiran</div>
            <div class="card-body">
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <div class="live-counter text-success" id="cnt-hadir">0</div>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <div>
                        <div class="live-counter" id="cnt-total">0</div>
                        <small class="text-muted">Peserta</small>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Izin</span><span id="cnt-izin">0</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Sakit</span><span id="cnt-sakit">0</span>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>Alpa</span><span id="cnt-alpa">0</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fa-solid fa-key me-1"></i>Token Cadangan (manual)</div>
            <div class="card-body text-center">
                <div class="token-cadangan"><?= esc($pertemuan['token_cadangan'] ?? '') ?></div>
                <small class="text-muted">Umumkan kode ini bila mahasiswa kesulitan kamera/GPS</small>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('vendor/qrcode.min.js') ?>"></script>
<script>
(function () {
    const pertemuanId = <?= (int) $pertemuan['id'] ?>;
    const kelasId     = <?= (int) $kelas['id'] ?>;
    const qrEl        = document.getElementById('qrcode');
    const csrfMeta    = document.querySelector('meta[name="csrf-token"]');
    let qr = null;
    let countdown = 20;

    async function fetchJSON(url) {
        const res = await fetch(url, { headers: { 'X-CSRF-TOKEN': csrfMeta.getAttribute('content') } });
        const json = await res.json();
        if (json.csrf_hash) csrfMeta.setAttribute('content', json.csrf_hash);
        return json;
    }

    function renderQr(text) {
        if (qr) { qr.clear(); qr.makeCode(text); }
        else { qr = new QRCode(qrEl, { text: text, width: 280, height: 280, correctLevel: QRCode.CorrectLevel.M }); }
    }

    async function refreshToken() {
        try {
            const data = await fetchJSON('<?= site_url('dosen/api/qr-token/') ?>' + pertemuanId);
            if (data.error) {
                document.getElementById('countdown').textContent = data.error;
                return;
            }
            renderQr(data.url);
            countdown = Math.max(1, data.sisa_detik);
        } catch (e) {
            console.error(e);
        }
    }

    async function refreshLive() {
        try {
            const data = await fetchJSON('<?= site_url('dosen/api/live/') ?>' + pertemuanId);
            if (data.error) return;
            document.getElementById('cnt-hadir').textContent = data.total.Hadir ?? 0;
            document.getElementById('cnt-izin').textContent  = data.total.Izin ?? 0;
            document.getElementById('cnt-sakit').textContent = data.total.Sakit ?? 0;
            document.getElementById('cnt-alpa').textContent  = data.total.Alpa ?? 0;
            document.getElementById('cnt-total').textContent = data.total_peserta;
        } catch (e) {
            console.error(e);
        }
    }

    // Render QR awal dari data server, lalu polling tiap detik.
    renderQr(<?= json_encode($scanUrl) ?>);

    setInterval(() => {
        countdown -= 1;
        document.getElementById('countdown').textContent = 'refresh ' + countdown + 's';
        if (countdown <= 0) { refreshToken(); }
    }, 1000);

    setInterval(refreshLive, 5000);
    refreshLive();
    refreshToken();
})();
</script>
<?= $this->endSection() ?>
