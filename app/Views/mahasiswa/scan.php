<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fa-solid fa-camera me-1"></i>Scan QR Code</div>
            <div class="card-body">
                <div id="reader"></div>
                <div id="scan-status" class="text-muted small mt-2 text-center">Arahkan kamera ke QR Code di layar proyektor dosen.</div>
                <div class="d-grid gap-2 mt-3">
                    <button id="btn-start" class="btn btn-primary"><i class="fa-solid fa-play me-1"></i>Mulai Kamera</button>
                    <button id="btn-stop" class="btn btn-outline-secondary d-none"><i class="fa-solid fa-stop me-1"></i>Hentikan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fa-solid fa-keyboard me-1"></i>Input Token Cadangan</div>
            <div class="card-body">
                <p class="text-muted small">Bila kamera/GPS bermasalah, masukkan 6-digit token yang diumumkan dosen.</p>
                <form id="form-token">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Kode Token</label>
                        <input type="text" name="kode_token" id="kode_token" class="form-control text-center" maxlength="6"
                               inputmode="numeric" placeholder="••••••" style="letter-spacing: 0.5em; font-size: 1.5rem;">
                    </div>
                    <div class="d-grid">
                        <button type="submit" id="btn-token" class="btn btn-warning">
                            <i class="fa-solid fa-paper-plane me-1"></i>Kirim Presensi
                        </button>
                    </div>
                </form>
                <div id="token-result" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('vendor/html5-qrcode.min.js') ?>"></script>
<script>
(function () {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    let scanner = null;

    function updateCsrf(hash) {
        if (hash) {
            csrfMeta.setAttribute('content', hash);
            const field = document.querySelector('input[name="<?= csrf_token() ?>"]');
            if (field) field.value = hash;
        }
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

    async function kirimToken(kode) {
        const btn = document.getElementById('btn-token');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';

        try {
            const pos = await getPosition();
            const res = await fetch('<?= site_url('presensi/token') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfMeta.getAttribute('content') },
                body: JSON.stringify({ kode_token: kode, latitude: pos.latitude, longitude: pos.longitude })
            });
            const json = await res.json();
            updateCsrf(json.csrf_hash);
            tampilkanHasil(json, document.getElementById('token-result'));
        } catch (e) {
            tampilkanHasil({ success: false, message: e.message }, document.getElementById('token-result'));
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i>Kirim Presensi';
        }
    }

    function tampilkanHasil(json, el) {
        if (json.success) {
            el.innerHTML = '<div class="alert alert-success"><i class="fa-solid fa-circle-check me-1"></i>' + json.message + '</div>';
        } else {
            el.innerHTML = '<div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>' + (json.message || 'Gagal') + '</div>';
        }
    }

    document.getElementById('form-token').addEventListener('submit', function (e) {
        e.preventDefault();
        const kode = document.getElementById('kode_token').value.trim();
        if (kode.length !== 6) {
            tampilkanHasil({ success: false, message: 'Token harus 6 digit.' }, document.getElementById('token-result'));
            return;
        }
        kirimToken(kode);
    });

    document.getElementById('btn-start').addEventListener('click', function () {
        if (scanner) return;
        const status = document.getElementById('scan-status');
        scanner = new Html5Qrcode('reader');

        scanner.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 220, height: 220 } },
            decodedText => {
                status.innerHTML = '<span class="text-success">QR terdeteksi! Membuka...</span>';
                scanner.stop().then(() => { window.location.href = decodedText; }).catch(() => { window.location.href = decodedText; });
            },
            err => { /* abaikan error frame */ }
        ).then(() => {
            status.textContent = 'Kamera aktif. Arahkan ke QR Code.';
            document.getElementById('btn-start').classList.add('d-none');
            document.getElementById('btn-stop').classList.remove('d-none');
        }).catch(e => {
            status.innerHTML = '<span class="text-danger">Gagal mengakses kamera: ' + e + '</span>';
            scanner = null;
        });
    });

    document.getElementById('btn-stop').addEventListener('click', function () {
        if (!scanner) return;
        scanner.stop().then(() => {
            scanner = null;
            document.getElementById('scan-status').textContent = 'Kamera dihentikan.';
            document.getElementById('btn-start').classList.remove('d-none');
            document.getElementById('btn-stop').classList.add('d-none');
        });
    });
})();
</script>
<?= $this->endSection() ?>
