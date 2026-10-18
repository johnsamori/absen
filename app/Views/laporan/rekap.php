<?php
/**
 * @var array<string, mixed> $kelas
 * @var list<array<string, mixed>> $pertemuan
 * @var list<array<string, mixed>> $matriks
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Rekapitulasi Presensi</h4>
        <small class="text-muted">
            <?= esc($kelas['kode_mk']) ?> — <?= esc($kelas['nama_mk']) ?>
            (Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['dosen_nama'] ?? '-') ?>)
        </small>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="<?= site_url('laporan/export-csv/' . $kelas['id']) ?>" class="btn btn-outline-success">
            <i class="fa-solid fa-file-csv me-1"></i>Export CSV
        </a>
        <a href="<?= site_url('laporan') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <?php foreach ($pertemuan as $pt): ?>
                        <th class="text-center">P<?= (int) $pt['pertemuan_ke'] ?></th>
                    <?php endforeach; ?>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Persentase</th>
                    <th class="text-end no-print">Cetak Kartu</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($matriks === []): ?>
                    <tr><td colspan="<?= 5 + count($pertemuan) ?>" class="text-center text-muted py-4">Belum ada peserta.</td></tr>
                <?php else: ?>
                    <?php foreach ($matriks as $row): ?>
                        <tr>
                            <td><?= esc($row['mahasiswa']['nim']) ?></td>
                            <td><?= esc($row['mahasiswa']['nama_lengkap']) ?></td>
                            <?php foreach ($pertemuan as $pt): ?>
                                <?php $st = $row['cell'][(int) $pt['id']] ?? '—'; ?>
                                <td class="text-center">
                                    <?php
                                    $color = ['Hadir' => 'success', 'Izin' => 'warning', 'Sakit' => 'info', 'Alpa' => 'danger', '—' => 'secondary'][$st];
                                    echo '<span class="badge bg-' . $color . '">' . $st . '</span>';
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="text-center fw-bold"><?= (int) $row['hadir'] ?></td>
                            <td class="text-center"><?= (float) $row['persen'] ?>%</td>
                            <td class="text-end no-print text-nowrap">
                                <a href="<?= site_url('laporan/kartu-absensi/' . $kelas['id'] . '/' . $row['mahasiswa']['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary py-0.5 px-2" title="Cetak Lembar Presensi MK Ini">
                                    <i class="fa-solid fa-print me-1"></i>Lembar MK
                                </a>
                                <a href="<?= site_url('laporan/kartu-mahasiswa/' . $row['mahasiswa']['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-0.5 px-2" title="Cetak Kartu Semester Lengkap">
                                    <i class="fa-solid fa-id-card-clip"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
