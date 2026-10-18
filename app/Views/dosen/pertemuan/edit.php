<?php
/**
 * @var array<string, mixed> $kelas
 * @var array<string, mixed> $pertemuan
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card shadow-sm border-0" style="max-width: 680px;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold">Edit Berita Acara — Pertemuan ke-<?= (int) $pertemuan['pertemuan_ke'] ?></h5>
        <span class="badge <?= $pertemuan['status'] === 'open' ? 'bg-success' : 'bg-secondary' ?>">
            <?= ucfirst($pertemuan['status']) ?>
        </span>
    </div>
    <div class="card-body p-4">
        <form method="post" action="<?= site_url('dosen/pertemuan/update/' . $pertemuan['id']) ?>">
            <?= csrf_field() ?>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= esc(old('tanggal', $pertemuan['tanggal'] ?? '')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Buka</label>
                    <input type="time" name="jam_buka" class="form-control" value="<?= esc(old('jam_buka', !empty($pertemuan['jam_buka']) ? date('H:i', strtotime($pertemuan['jam_buka'])) : '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Tutup</label>
                    <input type="time" name="jam_tutup" class="form-control" value="<?= esc(old('jam_tutup', !empty($pertemuan['jam_tutup']) ? date('H:i', strtotime($pertemuan['jam_tutup'])) : '')) ?>">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Materi / Berita Acara</label>
                <textarea name="materi_kuliah" class="form-control" rows="4"><?= esc(old('materi_kuliah', $pertemuan['materi_kuliah'] ?? '')) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="fa-solid fa-save me-1.5"></i>Simpan Perubahan</button>
                <a href="<?= site_url('dosen/pertemuan/' . $pertemuan['kelas_kuliah_id']) ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
