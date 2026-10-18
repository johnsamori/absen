<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 640px;">
    <div class="card-header"><?= esc($title) ?></div>
    <div class="card-body">
        <form method="post" action="<?= $row === null ? site_url('admin/users') : site_url('admin/users/update/' . $row['id']) ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= esc(old('username', $row['username'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc(old('email', $row['email'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <?php foreach (['admin' => 'Super Admin', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa', 'pimpinan' => 'Pimpinan'] as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= old('role', $row['role'] ?? '') === $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <?= $row !== null ? '(kosongkan jika tetap)' : '' ?></label>
                    <input type="text" name="password" class="form-control" value="<?= old('password') ?>" placeholder="min. 6 karakter">
                </div>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                       <?= (int) old('is_active', $row['is_active'] ?? 1) === 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Akun aktif</label>
            </div>

            <?php if ($row !== null && $row['role'] === 'dosen' && ($profil ?? null) !== null): ?>
                <div class="alert alert-light border small mb-3">
                    <i class="fa-solid fa-user-tie me-1 text-primary"></i>
                    Terhubung ke dosen: <strong><?= esc($profil['nama_lengkap']) ?></strong> (NIP <?= esc($profil['nip']) ?>).
                </div>
            <?php elseif ($row !== null && $row['role'] === 'mahasiswa' && ($profil ?? null) !== null): ?>
                <div class="alert alert-light border small mb-3">
                    <i class="fa-solid fa-user-graduate me-1 text-primary"></i>
                    Terhubung ke mahasiswa: <strong><?= esc($profil['nama_lengkap']) ?></strong> (NIM <?= esc($profil['nim']) ?>).
                </div>
            <?php elseif ($row !== null && ($row['role'] === 'dosen' || $row['role'] === 'mahasiswa')): ?>
                <div class="alert alert-warning small mb-3">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    User ini ber-role <?= esc(role_label($row['role'])) ?> namun belum punya profil terkait. Sebaiknya kelola lewat
                    Data Dosen / Data Mahasiswa.
                </div>
            <?php endif; ?>

            <?php if ($row === null): ?>
                <div class="alert alert-info small">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    Untuk akun dosen/mahasiswa, disarankan membuat lewat menu Data Dosen / Data Mahasiswa agar profil
                    (NIP/NIM, prodi) otomatis terbentuk.
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
