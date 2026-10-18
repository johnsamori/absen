<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 720px;">
    <div class="card-header"><?= esc($title) ?></div>
    <div class="card-body">
        <form method="post" action="<?= $row === null ? site_url('admin/mata-kuliah') : site_url('admin/mata-kuliah/update/' . $row['id']) ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kode MK</label>
                    <input type="text" name="kode_mk" class="form-control" value="<?= esc(old('kode_mk', $row['kode_mk'] ?? '')) ?>" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" class="form-control" value="<?= esc(old('nama_mk', $row['nama_mk'] ?? '')) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">SKS</label>
                    <input type="number" name="sks" class="form-control" value="<?= esc(old('sks', $row['sks'] ?? 2)) ?>" min="1" max="12" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select">
                        <option value="">— Pilih Prodi —</option>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= (string) old('prodi_id', $row['prodi_id'] ?? '') === (string) $p['id'] ? 'selected' : '' ?>>
                                <?= esc($p['nama_prodi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Semester Paket</label>
                    <input type="number" name="semester_paket" class="form-control" value="<?= esc(old('semester_paket', $row['semester_paket'] ?? '')) ?>" min="1" max="8">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/mata-kuliah') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
