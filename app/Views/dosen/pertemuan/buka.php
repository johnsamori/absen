<?php
/**
 * @var array<string, mixed> $kelas
 * @var int $pertemuan_ke
 * @var string $tanggal
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card shadow-sm border-0" style="max-width: 720px;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
        <div class="rounded p-1 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-play"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold">Buka Sesi Pertemuan — <?= esc($kelas['nama_mk']) ?></h5>
            <small class="text-muted">Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['hari'] ?? '') ?></small>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="post" action="<?= site_url('dosen/pertemuan/' . $kelas['id'] . '/buka') ?>">
            <?= csrf_field() ?>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pertemuan Ke-</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-hashtag text-muted"></i></span>
                        <input type="number" name="pertemuan_ke" class="form-control" value="<?= esc(old('pertemuan_ke', $pertemuan_ke)) ?>" min="1" max="16" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tanggal Perkuliahan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-calendar text-muted"></i></span>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= esc(old('tanggal', $tanggal)) ?>" required>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Waktu Absen -->
            <div class="p-3 mb-3 rounded-3 border bg-light bg-opacity-50">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-bold text-dark mb-0">
                        <i class="fa-solid fa-clock text-primary me-1"></i>Setting Batas Waktu Absen di Kelas
                    </label>
                    <small class="text-muted">Presensi ditutup otomatis setelah batas waktu</small>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Jam Buka Presensi</label>
                        <input type="time" name="jam_buka" id="jam_buka" class="form-control" value="<?= esc(old('jam_buka', date('H:i'))) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Jam Tutup Presensi</label>
                        <input type="time" name="jam_tutup" id="jam_tutup" class="form-control" value="<?= esc(old('jam_tutup', date('H:i', strtotime('+30 minutes')))) ?>" required>
                    </div>
                </div>

                <div class="mt-2.5">
                    <label class="form-label small text-muted mb-1">Pilih Cepat Durasi Presensi:</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-preset-durasi" data-menit="15">15 Menit</button>
                        <button type="button" class="btn btn-sm btn-primary btn-preset-durasi" data-menit="30">30 Menit</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-preset-durasi" data-menit="45">45 Menit</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-preset-durasi" data-menit="60">60 Menit</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-preset-durasi" data-menit="90">90 Menit</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-preset-durasi" data-menit="120">2 Jam</button>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Materi / Rencana Perkuliahan</label>
                <textarea name="materi_kuliah" class="form-control" rows="3" placeholder="Tuliskan pokok bahasan materi pertemuan ini..."><?= esc(old('materi_kuliah')) ?></textarea>
            </div>

            <div class="alert alert-info border-0 py-2.5 px-3 small d-flex align-items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-info text-info fs-5"></i>
                <div>
                    <strong>Alur Presensi Baru:</strong> Mahasiswa login di HP dan menampilkan QR Code masing-masing. Dosen men-scan QR mahasiswa menggunakan kamera HP atau laptop di kelas.
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-camera me-1.5"></i>Buka Sesi & Mulai Scan Kamera
                </button>
                <a href="<?= site_url('dosen/pertemuan/' . $kelas['id']) ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jamBukaInput = document.getElementById('jam_buka');
    const jamTutupInput = document.getElementById('jam_tutup');
    const presetButtons = document.querySelectorAll('.btn-preset-durasi');

    function updateJamTutup(durasiMenit) {
        let bukaVal = jamBukaInput.value || '<?= date('H:i') ?>';
        const [hours, minutes] = bukaVal.split(':').map(Number);
        const date = new Date();
        date.setHours(hours, minutes, 0, 0);
        date.setMinutes(date.getMinutes() + Number(durasiMenit));

        const hh = String(date.getHours()).padStart(2, '0');
        const mm = String(date.getMinutes()).padStart(2, '0');
        jamTutupInput.value = `${hh}:${mm}`;
    }

    presetButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            presetButtons.forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
            updateJamTutup(this.dataset.menit);
        });
    });

    jamBukaInput.addEventListener('change', function () {
        const activePreset = document.querySelector('.btn-preset-durasi.btn-primary');
        if (activePreset) {
            updateJamTutup(activePreset.dataset.menit);
        }
    });
});
</script>

<?= $this->endSection() ?>
