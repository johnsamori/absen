<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center">
            <div class="card-body p-4">
                <?php if ($success === false): ?>
                    <div class="scan-result-icon text-danger mb-3"><i class="fa-solid fa-circle-xmark"></i></div>
                    <h5>Presensi Gagal</h5>
                    <p class="text-muted"><?= esc($message) ?></p>
                    <a href="<?= site_url('mahasiswa/scan') ?>" class="btn btn-primary">Kembali ke Scanner</a>
                <?php else: ?>
                    <div class="scan-result-icon text-primary mb-3" id="result-icon"><i class="fa-solid fa-spinner fa-spin"></i></div>
                    <h5 id="result-title">Memproses Presensi...</h5>
                    <p class="text-muted">
                        <?= esc($kelas['kode_mk'] ?? '') ?> — <?= esc($kelas['nama_mk'] ?? '') ?> ·
                        Pertemuan ke-<?= (int) ($pertemuan['pertemuan_ke'] ?? 0) ?>
                    </p>
                    <div id="result-message" class="text-muted small mb-3">Mengambil lokasi Anda...</div>
                    <div id="result-actions"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php if ($success !== false): ?>
<?= $this->section('scripts') ?>
<script>
(function () {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const pertemuanId = <?= (int) $pertemuan['id'] ?>;
    const token = <?= json_encode($token) ?>;

    function setResult(success, message) {
        const icon = document.getElementById('result-icon');
        const title = document.getElementById('result-title');
        const msg = document.getElementById('result-message');
        const actions = document.getElementById('result-actions');

        if (success) {
            icon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
            icon.className = 'scan-result-icon text-success mb-3';
            title.textContent = 'Presensi Berhasil!';
        } else {
            icon.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
            icon.className = 'scan-result-icon text-danger mb-3';
            title.textContent = 'Presensi Gagal';
        }
        msg.textContent = message;
        actions.innerHTML = '<a href="' + '<?= site_url('mahasiswa') ?>' + '" class="btn btn-primary me-2">Ke Dashboard</a>' +
                            '<a href="' + '<?= site_url('mahasiswa/scan') ?>' + '" class="btn btn-outline-secondary">Scan Lagi</a>';
    }

    function getPosition() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) return reject(new Error('Geolocation tidak didukung.'));
            navigator.geolocation.getCurrentPosition(
                pos => resolve({ latitude: pos.coords.latitude, longitude: pos.coords.longitude }),
                err => reject(new Error('Gagal mendapatkan lokasi: ' + err.message)),
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        });
    }

    async function submit() {
        try {
            const pos = await getPosition();
            document.getElementById('result-message').textContent = 'Mengirim data presensi...';

            const res = await fetch('<?= site_url('presensi/submit') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfMeta.getAttribute('content') },
                body: JSON.stringify({ pertemuan_id: pertemuanId, token: token, latitude: pos.latitude, longitude: pos.longitude })
            });
            const json = await res.json();
            if (json.csrf_hash) csrfMeta.setAttribute('content', json.csrf_hash);
            setResult(!!json.success, json.message || 'Terjadi kesalahan.');
        } catch (e) {
            setResult(false, e.message);
        }
    }

    submit();
})();
</script>
<?= $this->endSection() ?>
<?php endif; ?>
