<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAP — <?= esc($kelas['nama_mk']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; font-size: 14px; }
        .kop { border-bottom: 3px double #000; padding-bottom: 1rem; }
        .ttd { margin-top: 4rem; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print me-1"></i>Cetak</button>
        <button onclick="window.close()" class="btn btn-outline-secondary">Tutup</button>
    </div>

    <div class="kop text-center mb-4">
        <h5 class="fw-bold mb-0">BERITA ACARA PERKULIAHAN</h5>
        <div>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
        <div>UNIVERSITAS CENDERAWASIH — FAKULTAS ILMU SOSIAL DAN ILMU POLITIK</div>
    </div>

    <table class="table table-sm table-borderless mb-4" style="max-width: 720px;">
        <tr>
            <td style="width: 160px;">Mata Kuliah</td>
            <td>: <?= esc($kelas['kode_mk']) ?> — <?= esc($kelas['nama_mk']) ?></td>
            <td style="width: 100px;">SKS</td>
            <td>: <?= (int) $kelas['sks'] ?></td>
        </tr>
        <tr>
            <td>Dosen Pengampu</td>
            <td>: <?= esc(trim(($kelas['gelar_depan'] ?? '') . ' ' . ($kelas['dosen_nama'] ?? '') . ' ' . ($kelas['gelar_belakang'] ?? ''))) ?></td>
            <td>Kelas</td>
            <td>: <?= esc($kelas['nama_kelas'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>Hari / Jam</td>
            <td>: <?= esc($kelas['hari'] ?? '') ?>, <?= esc(substr($kelas['jam_mulai'] ?? '', 0, 5)) ?> - <?= esc(substr($kelas['jam_selesai'] ?? '', 0, 5)) ?></td>
            <td>Ruangan</td>
            <td>: <?= esc($kelas['ruangan'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>Tahun Akademik</td>
            <td>: <?= esc($kelas['tahun'] ?? '') ?> <?= esc($kelas['semester'] ?? '') ?></td>
            <td>Pertemuan Ke</td>
            <td>: <?= (int) $pertemuan['pertemuan_ke'] ?> / 16</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= esc($pertemuan['tanggal'] ?? '-') ?></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="mb-3"><strong>Materi Perkuliahan:</strong></div>
    <p class="border rounded p-3"><?= nl2br(esc($pertemuan['materi_kuliah'] ?? '-')) ?></p>

    <div class="mb-2"><strong>Daftar Hadir Mahasiswa:</strong></div>
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr><th style="width: 40px;">No</th><th>NIM</th><th>Nama Mahasiswa</th><th style="width: 90px;" class="text-center">Status</th></tr>
        </thead>
        <tbody>
            <?php foreach ($peserta as $i => $p): ?>
                <?php
                $st = $statusMap[(int) $p['id']]['status'] ?? 'Alpa';
                ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td><?= esc($p['nim']) ?></td>
                    <td><?= esc($p['nama_lengkap']) ?></td>
                    <td class="text-center"><?= esc($st) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($peserta === []): ?>
                <tr><td colspan="4" class="text-center text-muted">Belum ada peserta.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="row ttd text-center">
        <div class="col-6">
            <div>Mengetahui,<br>Ketua Program Studi</div>
            <div style="height: 80px;"></div>
            <div><u><?= esc($kelas['kaprodi'] ?? '............................') ?></u></div>
            <div>NIP.</div>
        </div>
        <div class="col-6">
            <div>Jayapura, <?= esc(date('d F Y', strtotime($pertemuan['tanggal'] ?? 'now'))) ?></div>
            <div>Dosen Pengampu</div>
            <div style="height: 80px;"></div>
            <div><u><?= esc($kelas['dosen_nama'] ?? '............................') ?></u></div>
            <div>NIP.</div>
        </div>
    </div>
</div>
</body>
</html>
