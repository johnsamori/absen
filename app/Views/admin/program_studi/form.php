<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 640px;">
    <div class="card-header"><?= esc($title) ?></div>
    <div class="card-body">
        <form method="post" action="<?= $row === null ? site_url('admin/program-studi') : site_url('admin/program-studi/update/' . $row['id']) ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Kode Prodi</label>
                <input type="text" name="kode_prodi" class="form-control" value="<?= esc(old('kode_prodi', $row['kode_prodi'] ?? '')) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Program Studi</label>
                <input type="text" name="nama_prodi" class="form-control" value="<?= esc(old('nama_prodi', $row['nama_prodi'] ?? '')) ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenjang</label>
                    <select name="jenjang" class="form-select">
                        <?php foreach (['S1', 'S2', 'S3'] as $j): ?>
                            <option value="<?= $j ?>" <?= old('jenjang', $row['jenjang'] ?? '') === $j ? 'selected' : '' ?>><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ketua Prodi</label>
                    <input type="text" name="kaprodi" class="form-control" value="<?= esc(old('kaprodi', $row['kaprodi'] ?? '')) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/program-studi') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
