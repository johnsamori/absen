<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 680px;">
    <div class="card-header">Buka Sesi Pertemuan — <?= esc($kelas['nama_mk']) ?></div>
    <div class="card-body">
        <form method="post" action="<?= site_url('dosen/pertemuan/' . $kelas['id'] . '/buka') ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pertemuan Ke-</label>
                    <input type="number" name="pertemuan_ke" class="form-control" value="<?= esc(old('pertemuan_ke', $pertemuan_ke)) ?>" min="1" max="16" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= esc(old('tanggal', $tanggal)) ?>" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Materi / Rencana Perkuliahan</label>
                <textarea name="materi_kuliah" class="form-control" rows="4" placeholder="Tuliskan pokok bahasan materi pertemuan ini..."><?= esc(old('materi_kuliah')) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-play me-1"></i>Buka Sesi & Tampilkan QR
            </button>
            <a href="<?= site_url('dosen/pertemuan/' . $kelas['id']) ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
