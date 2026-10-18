<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<form method="post" action="<?= site_url('auth/login') ?>" autocomplete="off">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required autofocus>
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" name="password" class="form-control" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">
        <i class="fa-solid fa-right-to-bracket me-1"></i>Masuk
    </button>
</form>

<div class="text-center text-muted small mt-4">
    <p class="mb-1">Akun demo:</p>
    <p class="mb-0">admin / admin123 · dosen1 / dosen123 · mhs1 / mhs123 · pimpinan / pimpinan123</p>
</div>

<?= $this->endSection() ?>
