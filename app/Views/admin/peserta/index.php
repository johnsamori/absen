<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Peserta Kelas</h4>
        <small class="text-muted">
            <?= esc($kelas['kode_mk']) ?> — <?= esc($kelas['nama_mk']) ?>
            (Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['dosen_nama'] ?? 'Belum ada dosen') ?>)
        </small>
    </div>
    <a href="<?= site_url('admin/kelas-kuliah') ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Daftar Peserta (<?= count($peserta) ?>)</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                        <?php if ($peserta === []): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada peserta.</td></tr>
                        <?php else: ?>
                            <?php foreach ($peserta as $p): ?>
                                <tr>
                                    <td><?= esc($p['nim']) ?></td>
                                    <td><?= esc($p['nama_lengkap']) ?></td>
                                    <td><?= esc($p['nama_prodi'] ?? '-') ?></td>
                                    <td class="text-end">
                                        <form method="post" action="<?= site_url('admin/peserta/' . $p['peserta_id'] . '/hapus') ?>"
                                              onsubmit="return confirm('Keluarkan mahasiswa ini dari kelas?')">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-user-minus"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Tambah Peserta</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admin/kelas-kuliah/' . $kelas['id'] . '/peserta/tambah') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Pilih Mahasiswa</label>
                        <select name="mahasiswa_id" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <?php foreach ($mhsList as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= esc($m['nim']) ?> — <?= esc($m['nama_lengkap']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($mhsList === []): ?>
                            <small class="text-muted d-block mt-1">Semua mahasiswa sudah terdaftar di kelas ini.</small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i>Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
