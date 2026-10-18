<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Kelola Presensi</h4>
        <small class="text-muted">
            <?= esc($kelas['nama_mk']) ?> — Pertemuan ke-<?= (int) $pertemuan['pertemuan_ke'] ?> ·
            <?= $pertemuan['status'] === 'open' ? '<span class="text-success">Sesi terbuka</span>' : '<span class="text-danger">Sesi ditutup</span>' ?>
        </small>
    </div>
    <a href="<?= site_url('dosen/pertemuan/' . $kelas['id']) ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php if ($izinSakit !== []): ?>
    <div class="card mb-4 border-start border-4 border-warning">
        <div class="card-header"><i class="fa-solid fa-file-medical me-1"></i>Verifikasi Pengajuan Izin / Sakit</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>NIM</th><th>Nama</th><th>Status</th><th>Alasan</th><th>Bukti</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($izinSakit as $p): ?>
                        <tr>
                            <td><?= esc($p['nim'] ?? '') ?></td>
                            <td><?= esc($p['nama_lengkap'] ?? '') ?></td>
                            <td><?= status_badge($p['status']) ?></td>
                            <td><?= esc($p['catatan_keterangan'] ?? '-') ?></td>
                            <td>
                                <?php if (! empty($p['bukti_dokumen'])): ?>
                                    <a href="<?= base_url($p['bukti_dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-paperclip me-1"></i>Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <form method="post" action="<?= site_url('dosen/presensi/' . $p['id'] . '/setujui') ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-success"><i class="fa-solid fa-check me-1"></i>Setujui</button>
                                </form>
                                <form method="post" action="<?= site_url('dosen/presensi/' . $p['id'] . '/tolak') ?>" class="d-inline"
                                      onsubmit="return confirm('Tolak pengajuan ini dan ubah status menjadi Alpa?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-xmark me-1"></i>Tolak</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><i class="fa-solid fa-users me-1"></i>Daftar Peserta & Status Presensi</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>NIM</th><th>Nama</th><th>Status Saat Ini</th><th>Ubah Status</th></tr></thead>
            <tbody>
                <?php if ($peserta === []): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada peserta di kelas ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($peserta as $p): ?>
                        <?php
                        $cur = $statusMap[(int) $p['id']] ?? null;
                        $curStatus = $cur['status'] ?? 'Belum';
                        ?>
                        <tr>
                            <td><?= esc($p['nim']) ?></td>
                            <td><?= esc($p['nama_lengkap']) ?></td>
                            <td>
                                <?= $curStatus === 'Belum' ? '<span class="badge text-bg-light text-dark">Belum absen</span>' : status_badge($curStatus) ?>
                                <?= $cur !== null && ! empty($cur['catatan_keterangan']) ? '<br><small class="text-muted">' . esc($cur['catatan_keterangan']) . '</small>' : '' ?>
                            </td>
                            <td>
                                <form method="post" action="<?= site_url('dosen/pertemuan/' . $pertemuan['id'] . '/set-status') ?>" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="mahasiswa_id" value="<?= (int) $p['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" style="max-width: 140px;">
                                        <?php foreach (['Hadir', 'Izin', 'Sakit', 'Alpa'] as $s): ?>
                                            <option value="<?= $s ?>" <?= $curStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-sm btn-primary"><i class="fa-solid fa-save"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
