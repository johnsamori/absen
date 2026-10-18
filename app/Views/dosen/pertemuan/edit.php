<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 680px;">
    <div class="card-header">Edit Berita Acara — Pertemuan ke-<?= (int) $pertemuan['pertemuan_ke'] ?></div>
    <div class="card-body">
        <form method="post" action="<?= site_url('dosen/pertemuan/update/' . $pertemuan['id']) ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= esc(old('tanggal', $pertemuan['tanggal'] ?? '')) ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Materi / Berita Acara</label>
                <textarea name="materi_kuliah" class="form-control" rows="5"><?= esc(old('materi_kuliah', $pertemuan['materi_kuliah'] ?? '')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('dosen/pertemuan/' . $pertemuan['kelas_kuliah_id']) ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
