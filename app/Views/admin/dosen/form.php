<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 720px;">
    <div class="card-header"><?= esc($title) ?></div>
    <div class="card-body">
        <form method="post" action="<?= $row === null ? site_url('admin/dosen') : site_url('admin/dosen/update/' . $row['id']) ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control" value="<?= esc(old('nip', $row['nip'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIDN</label>
                    <input type="text" name="nidn" class="form-control" value="<?= esc(old('nidn', $row['nidn'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Gelar Depan</label>
                    <input type="text" name="gelar_depan" class="form-control" value="<?= esc(old('gelar_depan', $row['gelar_depan'] ?? '')) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= esc(old('nama_lengkap', $row['nama_lengkap'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" class="form-control" value="<?= esc(old('gelar_belakang', $row['gelar_belakang'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
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
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp', $row['no_hp'] ?? '')) ?>">
                </div>
            </div>

            <hr>
            <h6 class="text-muted">Akun Login</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= esc(old('username', $row['username'] ?? '')) ?>"
                           placeholder="default: NIP">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <?= $row !== null ? '(kosongkan jika tetap)' : '' ?></label>
                    <input type="text" name="password" class="form-control" value="<?= old('password') ?>" placeholder="default: dosen123">
                </div>
            </div>
            <?php if ($row !== null): ?>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                           <?= (int) old('is_active', $row['is_active'] ?? 0) === 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_active">Akun aktif</label>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/dosen') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
