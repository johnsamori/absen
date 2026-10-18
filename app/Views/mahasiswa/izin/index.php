<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Pengajuan Izin / Sakit</h4>
    <a href="<?= site_url('mahasiswa/izin/ajukan') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>Ajukan
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Waktu</th><th>Mata Kuliah</th><th>Pert.</th><th>Jenis</th><th>Alasan</th><th>Bukti</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= esc($r['waktu_presensi'] ?? '-') ?></td>
                            <td><?= esc($r['kode_mk'] ?? '') ?> — <?= esc($r['nama_mk'] ?? '') ?></td>
                            <td>P<?= (int) $r['pertemuan_ke'] ?></td>
                            <td><?= status_badge($r['status']) ?></td>
                            <td><?= esc($r['catatan_keterangan'] ?? '-') ?></td>
                            <td>
                                <?php if (! empty($r['bukti_dokumen'])): ?>
                                    <a href="<?= base_url($r['bukti_dokumen']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-paperclip me-1"></i>Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= status_badge($r['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
