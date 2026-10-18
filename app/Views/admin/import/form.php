<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title mb-1"><?= esc($title) ?></h4>
        <span class="page-subtitle"><?= esc($cfg['deskripsi']) ?></span>
    </div>
    <a href="<?= site_url('admin/' . ($jenis === 'mahasiswa' ? 'mahasiswa' : ($jenis === 'dosen' ? 'dosen' : 'mata-kuliah'))) ?>"
       class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php if ($result !== null): ?>
    <div class="card mb-4 border-start border-4 <?= $result['sukses'] > 0 ? 'border-success' : 'border-danger' ?>">
        <div class="card-body">
            <h6 class="mb-3"><i class="fa-solid fa-clipboard-check me-1"></i>Hasil Impor</h6>
            <div class="row text-center mb-3">
                <div class="col-4">
                    <div class="fs-4 fw-bold text-success"><?= (int) $result['sukses'] ?></div>
                    <small class="text-muted">Berhasil</small>
                </div>
                <div class="col-4">
                    <div class="fs-4 fw-bold text-warning"><?= (int) $result['dilewati'] ?></div>
                    <small class="text-muted">Dilewati (duplikat)</small>
                </div>
                <div class="col-4">
                    <div class="fs-4 fw-bold text-danger"><?= count($result['errors']) ?></div>
                    <small class="text-muted">Gagal</small>
                </div>
            </div>
            <?php if ($result['errors'] !== []): ?>
                <div class="alert alert-danger mb-0" style="max-height: 260px; overflow-y: auto;">
                    <strong>Baris gagal:</strong>
                    <ul class="mb-0 small">
                        <?php foreach ($result['errors'] as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (($result['skips'] ?? []) !== []): ?>
                <div class="alert alert-light border <?= $result['errors'] !== [] ? 'mt-2' : 'mb-0' ?>" style="max-height: 200px; overflow-y: auto;">
                    <strong class="text-warning">Dilewati:</strong>
                    <ul class="mb-0 small">
                        <?php foreach ($result['skips'] as $s): ?>
                            <li><?= esc($s) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="fa-solid fa-list-check me-1"></i>Format Kolom CSV</div>
            <div class="card-body">
                <ol class="mb-4 small">
                    <?php foreach ($cfg['kolom'] as $k => [$ket, $desc]): ?>
                        <li class="mb-1">
                            <code><?= esc($k) ?></code>
                            <?= $ket === 'wajib'
                                ? '<span class="badge text-bg-danger ms-1">wajib</span>'
                                : '<span class="badge text-bg-secondary ms-1">opsional</span>' ?>
                            <span class="text-muted">— <?= esc($desc) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
                <a href="<?= site_url('admin/import/' . $jenis . '/template') ?>" class="btn btn-outline-primary w-100">
                    <i class="fa-solid fa-download me-1"></i>Unduh Template CSV
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fa-solid fa-file-import me-1"></i>Unggah File CSV</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admin/import/' . $jenis . '/proses') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">File CSV</label>
                        <input type="file" name="file_csv" class="form-control" accept=".csv,.txt" required>
                        <div class="form-text">
                            Maksimal ukuran mengikuti konfigurasi server. Pemisah kolom boleh koma (,) atau titik koma (;).
                        </div>
                    </div>
                    <div class="alert alert-info small">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Akun login dibuat otomatis untuk <?= $jenis === 'mata-kuliah' ? 'mata kuliah (tanpa akun)' : $jenis ?>:
                        <?php if ($jenis === 'mahasiswa'): ?>
                            username = NIM, password = <code>mhs123</code>.
                        <?php elseif ($jenis === 'dosen'): ?>
                            username = NIP, password = <code>dosen123</code>.
                        <?php endif; ?>
                        Baris dengan NIM/NIP/Kode MK yang sudah ada akan dilewati.
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-file-import me-1"></i>Proses Impor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
