<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 640px;">
    <div class="card-header"><?= esc($title) ?></div>
    <div class="card-body">
        <form method="post" action="<?= $row === null ? site_url('admin/tahun-akademik') : site_url('admin/tahun-akademik/update/' . $row['id']) ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Tahun</label>
                <input type="text" name="tahun" class="form-control" placeholder="cth: 2026/2027"
                       value="<?= esc(old('tahun', $row['tahun'] ?? '')) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select">
                    <?php foreach (['Ganjil', 'Genap'] as $s): ?>
                        <option value="<?= $s ?>" <?= old('semester', $row['semester'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                       <?= (int) old('is_active', $row['is_active'] ?? 0) === 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Jadikan tahun akademik aktif</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/tahun-akademik') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
