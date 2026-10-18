<?php
/**
 * @var array<string, mixed> $mhs
 * @var list<array<string, mixed>> $kelasList
 * @var array<string, mixed>|null $taAktif
 * @var array<string, mixed>|null $pengaturan
 * @var int $totalSks
 * @var int $totalHadir
 * @var float $persenRerata
 * @var int $kelasMemenuhi
 * @var int $kelasPeringatan
 * @var string $qrData
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Presensi — <?= esc($mhs['nama_lengkap']) ?> (<?= esc($mhs['nim']) ?>)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            color: #0f172a;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 13px;
        }
        .page-container {
            max-width: 960px;
            margin: 24px auto;
            background: #ffffff;
            padding: 36px 44px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }
        .kop-wrapper {
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .table-custom th, .table-custom td {
            padding: 6px 8px;
            vertical-align: middle;
        }
        .table-custom thead th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.03em;
        }
        .ttd-box {
            margin-top: 36px;
        }
        .qr-card-box {
            width: 100px;
            height: 100px;
            border: 1px solid #e2e8f0;
            padding: 4px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .page-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm 1.2cm 1.5cm 1.2cm;
            }
        }
    </style>
</head>
<body>

<div class="page-container">
    <!-- Action Bar (Tersembunyi saat Cetak) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3 border">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('mahasiswa') ?>" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
            <span class="text-muted small">|</span>
            <span class="text-muted small fw-medium">Format: Kertas A4 Portrait Siap Cetak</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm">
                <i class="fa-solid fa-print me-1.5"></i>Cetak Kartu (Print / PDF)
            </button>
        </div>
    </div>

    <!-- KOP Surat Resmi Fakultas -->
    <div class="kop-wrapper text-center position-relative">
        <div class="row align-items-center">
            <div class="col-2 text-center">
                <?php if (is_file(FCPATH . 'logo_uncen.png')): ?>
                    <img src="<?= base_url('logo_uncen.png') ?>" alt="Logo UNCEN" style="max-height: 76px; max-width: 100%; object-fit: contain;">
                <?php else: ?>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-2 border-dark" style="width: 72px; height: 72px; font-weight: 800; font-size: 26px; color: #1e3a8a;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-8 text-center">
                <div class="fw-bold" style="font-size: 13px; letter-spacing: 0.05em;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                <div class="fw-bold fs-5" style="letter-spacing: 0.05em; color: #1e3a8a;">UNIVERSITAS CENDERAWASIH</div>
                <div class="fw-bold fs-6" style="letter-spacing: 0.03em;">FAKULTAS ILMU SOSIAL DAN ILMU POLITIK</div>
                <div class="text-muted" style="font-size: 11px;">
                    Jl. Kampwolker, Waena, Jayapura, Papua · Laman: https://fisip.uncen.ac.id
                </div>
            </div>
            <div class="col-2 text-center">
                <!-- QR Code Validasi Keaslian Dokumen -->
                <div class="qr-card-box">
                    <div id="print-qrcode"></div>
                </div>
                <div class="font-monospace text-muted" style="font-size: 9px; margin-top: 2px;">SCAN OTENTIKASI</div>
            </div>
        </div>
        <div class="mt-3">
            <h5 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 0.05em; text-decoration: underline;">
                KARTU KONTROL PRESENSI PERKULIAHAN
            </h5>
            <div class="small fw-semibold text-secondary mt-0.5">
                SEMESTER <?= esc(strtoupper($taAktif['semester'] ?? 'GENAP')) ?> TAHUN AKADEMIK <?= esc($taAktif['tahun'] ?? date('Y') . '/' . (date('Y') + 1)) ?>
            </div>
        </div>
    </div>

    <!-- Identitas Mahasiswa -->
    <div class="p-3 mb-3 bg-light bg-opacity-50 border rounded-2">
        <div class="row g-2">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 140px;" class="text-muted">Nama Mahasiswa</td>
                        <td class="fw-bold text-dark">: <?= esc($mhs['nama_lengkap']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nomor Induk (NIM)</td>
                        <td class="fw-bold font-monospace">: <?= esc($mhs['nim']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Program Studi</td>
                        <td class="fw-semibold">: <?= esc($mhs['nama_prodi'] ?? 'Ilmu Sosial & Politik') ?> (<?= esc($mhs['jenjang'] ?? 'S1') ?>)</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td style="width: 140px;" class="text-muted">Angkatan / Kelas</td>
                        <td>: <?= esc($mhs['angkatan'] ?? '-') ?> / Kelas <?= esc($mhs['kelas_paralel'] ?? 'A') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Syarat Presensi Ujian</td>
                        <td>: Minimal <strong class="text-dark"><?= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ?>%</strong> Kehadiran Kuliah</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Kelayakan</td>
                        <td>: 
                            <?php if ($kelasPeringatan === 0): ?>
                                <span class="badge text-bg-success"><i class="fa-solid fa-circle-check me-1"></i>MEMENUHI SYARAT UTS/UAS</span>
                            <?php else: ?>
                                <span class="badge text-bg-warning text-dark"><i class="fa-solid fa-triangle-exclamation me-1"></i>PERHATIAN (<?= (int) $kelasPeringatan ?> MK Kurang)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Presensi per Mata Kuliah -->
    <div class="mb-3">
        <table class="table table-bordered table-custom text-center align-middle mb-0">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 32px;">No</th>
                    <th rowspan="2" style="width: 80px;">Kode MK</th>
                    <th rowspan="2" class="text-start">Mata Kuliah</th>
                    <th rowspan="2" style="width: 45px;">SKS</th>
                    <th rowspan="2" class="text-start">Dosen Pengampu</th>
                    <th rowspan="2" style="width: 45px;">Kls</th>
                    <th colspan="2" style="width: 80px;">Tatap Muka</th>
                    <th colspan="4" style="width: 130px;">Presensi</th>
                    <th rowspan="2" style="width: 65px;">% Hadir</th>
                    <th rowspan="2" style="width: 110px;">Status Ujian</th>
                    <th rowspan="2" class="no-print" style="width: 50px;">Detail</th>
                </tr>
                <tr>
                    <th style="font-size: 10px; width: 40px;">Target</th>
                    <th style="font-size: 10px; width: 40px;">Selesai</th>
                    <th style="font-size: 10px; width: 32px;" class="text-success">H</th>
                    <th style="font-size: 10px; width: 32px;" class="text-warning">I</th>
                    <th style="font-size: 10px; width: 32px;" class="text-info">S</th>
                    <th style="font-size: 10px; width: 32px;" class="text-danger">A</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelasList)): ?>
                    <tr>
                        <td colspan="15" class="py-4 text-muted text-center">Belum ada mata kuliah yang diambil pada semester ini.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $no = 1;
                    $sumSks = 0;
                    $sumHadir = 0;
                    $sumTargetPertemuan = 0;
                    foreach ($kelasList as $k): 
                        $sumSks += (int) ($k['sks'] ?? 0);
                        $sumHadir += (int) ($k['hadir'] ?? 0);
                        $isEligible = ($k['persen_hadir'] >= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75));
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="font-monospace small"><?= esc($k['kode_mk']) ?></td>
                            <td class="text-start fw-semibold"><?= esc($k['nama_mk']) ?></td>
                            <td><?= (int) ($k['sks'] ?? 0) ?></td>
                            <td class="text-start small"><?= esc($k['dosen_nama'] ?? '-') ?></td>
                            <td><?= esc($k['nama_kelas'] ?? '-') ?></td>
                            <td>16</td>
                            <td><?= (int) ($k['jml_pertemuan'] ?? 0) ?></td>
                            <td class="fw-bold text-success"><?= (int) ($k['hadir'] ?? 0) ?></td>
                            <td><?= (int) ($k['izin'] ?? 0) ?></td>
                            <td><?= (int) ($k['sakit'] ?? 0) ?></td>
                            <td class="text-danger"><?= (int) ($k['alpa'] ?? 0) ?></td>
                            <td class="fw-bold <?= $isEligible ? 'text-success' : 'text-danger' ?>">
                                <?= (float) $k['persen_hadir'] ?>%
                            </td>
                            <td>
                                <?php if ($isEligible): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 10px;">
                                        Eligible
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5" style="font-size: 10px;">
                                        Tidak Eligible
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="no-print">
                                <a href="<?= site_url('mahasiswa/kartu-absensi/' . $k['id']) ?>" class="btn btn-outline-primary btn-sm py-0 px-1.5" title="Cetak Lembar Detail Pertemuan 1-16">
                                    <i class="fa-solid fa-file-lines" style="font-size: 11px;"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold table-light text-center">
                    <td colspan="3" class="text-end">TOTAL SKS & RERATA KEHADIRAN:</td>
                    <td><?= (int) $totalSks ?></td>
                    <td colspan="4"></td>
                    <td class="text-success"><?= (int) $totalHadir ?></td>
                    <td colspan="3"></td>
                    <td class="text-primary"><?= (float) $persenRerata ?>%</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Catatan & Ketentuan Akademik -->
    <div class="row g-2 mb-3">
        <div class="col-md-7">
            <div class="border rounded p-2.5 bg-light" style="font-size: 11px; line-height: 1.4;">
                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-circle-exclamation me-1 text-primary"></i>Ketentuan Akademik Presensi FISIP UNCEN:</div>
                <ol class="ps-3 mb-0 text-muted">
                    <li>Syarat mutlak mengikuti UTS dan UAS adalah persentase kehadiran minimal <strong><?= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ?>%</strong> dari total tatap muka.</li>
                    <li>Status Izin/Sakit diakui apabila telah diverifikasi dan disetujui oleh Dosen Pengampu mata kuliah.</li>
                    <li>Kartu ini dicetak sebagai dokumen presensi perkuliahan resmi mahasiswa.</li>
                </ol>
            </div>
        </div>
        <div class="col-md-5">
            <div class="border rounded p-2.5 text-center bg-light" style="font-size: 11px;">
                <div class="text-muted mb-1">Status Kelayakan Ujian Semester</div>
                <div class="fw-bold fs-6 <?= $kelasPeringatan === 0 ? 'text-success' : 'text-danger' ?>">
                    <?= (int) $kelasMemenuhi ?> dari <?= count($kelasList) ?> Mata Kuliah Memenuhi Syarat
                </div>
                <div class="text-muted mt-1">Dicetak pada: <?= date('d F Y, H:i') ?> WIT</div>
            </div>
        </div>
    </div>

    <!-- Kolom Tanda Tangan Pengesahan -->
    <div class="ttd-box">
        <div class="row text-center" style="font-size: 12px;">
            <div class="col-4">
                <div>Mahasiswa Bersangkutan,</div>
                <div style="height: 65px;"></div>
                <div class="fw-bold text-decoration-underline"><?= esc($mhs['nama_lengkap']) ?></div>
                <div class="text-muted">NIM. <?= esc($mhs['nim']) ?></div>
            </div>
            <div class="col-4">
                <div>Dosen Penasihat Akademik (PA),</div>
                <div style="height: 65px;"></div>
                <div class="fw-bold text-decoration-underline">........................................</div>
                <div class="text-muted">NIP. .....................................</div>
            </div>
            <div class="col-4">
                <div>Jayapura, <?= esc(date('d F Y')) ?></div>
                <div>Ketua Program Studi,</div>
                <div style="height: 50px;"></div>
                <div class="fw-bold text-decoration-underline"><?= esc($mhs['kaprodi'] ?? 'Dr. Alimin Alwi, M.Si') ?></div>
                <div class="text-muted">NIP. .....................................</div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('vendor/qrcode.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const qrEl = document.getElementById('print-qrcode');
    const qrData = <?= json_encode($qrData) ?>;
    if (qrEl && qrData) {
        new QRCode(qrEl, {
            text: qrData,
            width: 88,
            height: 88,
            colorDark: '#0f172a',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }
});
</script>
</body>
</html>
