<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 680px;">
    <div class="card-header">Ajukan Izin / Sakit</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('mahasiswa/izin/ajukan') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Pertemuan</label>
                <select name="pertemuan_id" class="form-select" required>
                    <option value="">— Pilih Pertemuan —</option>
                    <?php foreach ($pertemuanList as $p): ?>
                        <?php
                        // Cari nama kelas untuk pertemuan ini.
                        $namaKelas = '';
                        foreach ($kelasList as $k) {
                            if ((int) $k['id'] === (int) $p['kelas_kuliah_id']) {
                                $namaKelas = $k['nama_mk'];
                                break;
                            }
                        }
                        ?>
                        <option value="<?= $p['id'] ?>" <?= old('pertemuan_id') === (string) $p['id'] ? 'selected' : '' ?>>
                            <?= esc($namaKelas) ?> — Pertemuan ke-<?= (int) $p['pertemuan_ke'] ?> (<?= esc($p['tanggal'] ?? '') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($pertemuanList === []): ?>
                    <small class="text-muted d-block mt-1">Belum ada pertemuan pada kelas yang Anda ikuti.</small>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select" required>
                    <option value="Izin" <?= old('jenis') === 'Izin' ? 'selected' : '' ?>>Izin</option>
                    <option value="Sakit" <?= old('jenis') === 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Alasan / Keterangan</label>
                <textarea name="alasan" class="form-control" rows="3" placeholder="Jelaskan alasan izin/sakit..."><?= esc(old('alasan')) ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Bukti (foto surat dokter / keterangan resmi)</label>
                <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">Format JPG, PNG, atau PDF.</small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane me-1"></i>Kirim Pengajuan</button>
            <a href="<?= site_url('mahasiswa/izin') ?>" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
