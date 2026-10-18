<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 760px;">
    <div class="card-header"><?= esc($title) ?></div>
    <div class="card-body">
        <form method="post" action="<?= $row === null ? site_url('admin/kelas-kuliah') : site_url('admin/kelas-kuliah/update/' . $row['id']) ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Akademik</label>
                    <select name="tahun_akademik_id" class="form-select" required>
                        <?php foreach ($taList as $ta): ?>
                            <option value="<?= $ta['id'] ?>"
                                <?= (string) old('tahun_akademik_id', $row['tahun_akademik_id'] ?? $taAktifId) === (string) $ta['id'] ? 'selected' : '' ?>>
                                <?= esc($ta['tahun']) ?> <?= esc($ta['semester']) ?> <?= (int) $ta['is_active'] === 1 ? '(Aktif)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-select" required>
                        <option value="">— Pilih MK —</option>
                        <?php foreach ($mkList as $mk): ?>
                            <option value="<?= $mk['id'] ?>" <?= (string) old('mata_kuliah_id', $row['mata_kuliah_id'] ?? '') === (string) $mk['id'] ? 'selected' : '' ?>>
                                <?= esc($mk['kode_mk']) ?> — <?= esc($mk['nama_mk']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dosen Pengampu</label>
                    <select name="dosen_id" class="form-select">
                        <option value="">— Pilih Dosen —</option>
                        <?php foreach ($dosenList as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= (string) old('dosen_id', $row['dosen_id'] ?? '') === (string) $d['id'] ? 'selected' : '' ?>>
                                <?= esc($d['nama_lengkap']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control" value="<?= esc(old('nama_kelas', $row['nama_kelas'] ?? '')) ?>" placeholder="cth: A">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Hari</label>
                    <select name="hari" class="form-select">
                        <?php foreach (list_hari() as $h): ?>
                            <option value="<?= $h ?>" <?= old('hari', $row['hari'] ?? '') === $h ? 'selected' : '' ?>><?= $h ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="<?= esc(old('jam_mulai', $row['jam_mulai'] ?? '')) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="<?= esc(old('jam_selesai', $row['jam_selesai'] ?? '')) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Ruangan</label>
                    <input type="text" name="ruangan" class="form-control" value="<?= esc(old('ruangan', $row['ruangan'] ?? '')) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/kelas-kuliah') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
