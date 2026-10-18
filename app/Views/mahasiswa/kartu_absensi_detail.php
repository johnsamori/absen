<?php
/**
 * @var array<string, mixed> $mhs
 * @var array<string, mixed> $kelas
 * @var list<array<string, mixed>> $pertemuan
 * @var array<int, array<string, mixed>> $presensiMap
 * @var int $jmlPertemuan
 * @var int $totalHadir
 * @var int $totalIzin
 * @var int $totalSakit
 * @var int $totalAlpa
 * @var float $persenHadir
 * @var bool $isEligible
 * @var array<string, mixed>|null $pengaturan
 * @var string $qrData
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lembar Presensi — <?= esc($kelas['nama_mk']) ?> — <?= esc($mhs['nama_lengkap']) ?></title>
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
            max-width: 900px;
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
            padding: 5px 8px;
            vertical-align: middle;
        }
        .table-custom thead th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
        }
        .ttd-box {
            margin-top: 32px;
        }
        .qr-card-box {
            width: 90px;
            height: 90px;
            border: 1px solid #e2e8f0;
            padding: 4px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }
        @media print {
            body { background: #fff !important; padding: 0 !important; }
            .page-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .no-print { display: none !important; }
            @page {
                size: A4 portrait;
                margin: 1.2cm;
            }
        }
    </style>
</head>
<body>

<div class="page-container">
    <!-- Action Bar (Tersembunyi saat Cetak) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3 border">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('mahasiswa/kartu-absensi') ?>" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Kartu Semester
            </a>
            <span class="text-muted small">|</span>
            <span class="text-muted small fw-medium">Format: Lembar Kontrol Presensi Mata Kuliah</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm">
                <i class="fa-solid fa-print me-1.5"></i>Cetak Lembar Presensi
            </button>
        </div>
    </div>

    <!-- KOP Surat Resmi -->
    <div class="kop-wrapper text-center">
        <div class="row align-items-center">
            <div class="col-2 text-center">
                <?php if (is_file(FCPATH . 'logo_uncen.png')): ?>
                    <img src="<?= base_url('logo_uncen.png') ?>" alt="Logo UNCEN" style="max-height: 72px; max-width: 100%; object-fit: contain;">
                <?php else: ?>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-2 border-dark" style="width: 68px; height: 68px; font-weight: 800; font-size: 24px; color: #1e3a8a;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-8 text-center">
                <div class="fw-bold" style="font-size: 13px;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                <div class="fw-bold fs-5" style="color: #1e3a8a;">UNIVERSITAS CENDERAWASIH</div>
                <div class="fw-bold fs-6">FAKULTAS ILMU SOSIAL DAN ILMU POLITIK</div>
                <div class="text-muted" style="font-size: 11px;">Jl. Kampwolker, Waena, Jayapura, Papua</div>
            </div>
            <div class="col-2 text-center">
                <div class="qr-card-box">
                    <div id="print-qrcode"></div>
                </div>
                <div class="font-monospace text-muted" style="font-size: 8.5px; margin-top: 2px;">VALIDASI RESMI</div>
            </div>
        </div>
        <div class="mt-3">
            <h5 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 0.04em; text-decoration: underline;">
                LEMBAR KONTROL PRESENSI MATA KULIAH
            </h5>
            <div class="small fw-semibold text-secondary">
                SEMESTER <?= esc(strtoupper($kelas['semester'] ?? 'GENAP')) ?> TAHUN AKADEMIK <?= esc($kelas['tahun'] ?? date('Y')) ?>
            </div>
        </div>
    </div>

    <!-- Data Mata Kuliah & Mahasiswa -->
    <div class="border rounded p-3 mb-3 bg-light bg-opacity-50">
        <div class="row g-2">
            <div class="col-md-6 border-end">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td style="width: 130px;" class="text-muted">Mata Kuliah</td><td class="fw-bold">: <?= esc($kelas['kode_mk']) ?> — <?= esc($kelas['nama_mk']) ?></td></tr>
                    <tr><td class="text-muted">Bobot SKS</td><td>: <?= (int) ($kelas['sks'] ?? 0) ?> SKS</td></tr>
                    <tr><td class="text-muted">Dosen Pengampu</td><td class="fw-semibold">: <?= esc($kelas['dosen_nama'] ?? '-') ?></td></tr>
                    <tr><td class="text-muted">Kelas / Jadwal</td><td>: Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['hari'] ?? '-') ?> (<?= esc(substr($kelas['jam_mulai'] ?? '', 0, 5)) ?> - <?= esc(substr($kelas['jam_selesai'] ?? '', 0, 5)) ?> WIT)</td></tr>
                </table>
            </div>
            <div class="col-md-6 ps-md-3">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td style="width: 130px;" class="text-muted">Nama Mahasiswa</td><td class="fw-bold text-dark">: <?= esc($mhs['nama_lengkap']) ?></td></tr>
                    <tr><td class="text-muted">NIM</td><td class="fw-bold font-monospace">: <?= esc($mhs['nim']) ?></td></tr>
                    <tr><td class="text-muted">Program Studi</td><td>: <?= esc($mhs['nama_prodi'] ?? 'FISIP') ?></td></tr>
                    <tr>
                        <td class="text-muted">Status Kelayakan</td>
                        <td>: 
                            <?php if ($isEligible): ?>
                                <span class="badge text-bg-success">MEMENUHI SYARAT (<?= (float) $persenHadir ?>%)</span>
                            <?php else: ?>
                                <span class="badge text-bg-danger">BELUM MEMENUHI (<?= (float) $persenHadir ?>%)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian 16 Pertemuan -->
    <table class="table table-bordered table-custom align-middle mb-3">
        <thead>
            <tr class="text-center">
                <th style="width: 55px;">Pert.</th>
                <th style="width: 95px;">Tanggal</th>
                <th>Pokok Bahasan / Materi Kuliah</th>
                <th style="width: 85px;">Waktu Absen</th>
                <th style="width: 85px;">Status</th>
                <th style="width: 100px;">Paraf Dosen</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Buat index pertemuan 1 sampai 16
            $pertemuanMap = [];
            foreach ($pertemuan as $pt) {
                $pertemuanMap[(int) $pt['pertemuan_ke']] = $pt;
            }

            for ($p = 1; $p <= 16; $p++):
                $pt = $pertemuanMap[$p] ?? null;
                $presensi = null;
                if ($pt !== null) {
                    $presensi = $presensiMap[(int) $pt['id']] ?? null;
                }
            ?>
                <tr>
                    <td class="text-center fw-bold">P<?= $p ?></td>
                    <td class="text-center small font-monospace">
                        <?= $pt !== null && !empty($pt['tanggal']) ? esc(date('d/m/Y', strtotime($pt['tanggal']))) : '—' ?>
                    </td>
                    <td>
                        <?php if ($pt !== null): ?>
                            <span class="small"><?= esc($pt['materi_kuliah'] !== '' ? $pt['materi_kuliah'] : 'Tatap Muka Perkuliahan') ?></span>
                        <?php else: ?>
                            <span class="text-muted small fst-italic">Belum dilaksanakan</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center small font-monospace">
                        <?= $presensi !== null && !empty($presensi['waktu_presensi']) ? esc(substr($presensi['waktu_presensi'], 11, 5)) . ' WIT' : '—' ?>
                    </td>
                    <td class="text-center">
                        <?php if ($presensi !== null): ?>
                            <?php 
                            $badgeCls = ['Hadir' => 'text-bg-success', 'Izin' => 'text-bg-warning text-dark', 'Sakit' => 'text-bg-info', 'Alpa' => 'text-bg-danger'][$presensi['status']] ?? 'text-bg-secondary';
                            ?>
                            <span class="badge <?= $badgeCls ?> px-2 py-1" style="font-size: 10.5px;">
                                <?= esc($presensi['status']) ?>
                            </span>
                        <?php elseif ($pt !== null): ?>
                            <span class="badge text-bg-light text-muted border px-2 py-1" style="font-size: 10px;">Belum Absen</span>
                        <?php else: ?>
                            <span class="text-muted" style="font-size: 11px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($presensi !== null && $presensi['status'] === 'Hadir'): ?>
                            <span class="text-success small fw-bold"><i class="fa-solid fa-check-double me-1"></i>Verified</span>
                        <?php else: ?>
                            <span class="text-muted" style="font-size: 10px;">.....................</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- Ringkasan Kehadiran Mata Kuliah -->
    <div class="row g-2 mb-3">
        <div class="col-8">
            <div class="border rounded p-2.5 bg-light" style="font-size: 11px;">
                <div class="fw-bold mb-1">Ringkasan Presensi:</div>
                <div class="d-flex flex-wrap gap-3">
                    <div>Tatap Muka Selesai: <strong><?= (int) $jmlPertemuan ?></strong> / 16</div>
                    <div>Hadir: <strong class="text-success"><?= (int) $totalHadir ?></strong></div>
                    <div>Izin: <strong class="text-warning"><?= (int) $totalIzin ?></strong></div>
                    <div>Sakit: <strong class="text-info"><?= (int) $totalSakit ?></strong></div>
                    <div>Alpa: <strong class="text-danger"><?= (int) $totalAlpa ?></strong></div>
                    <div>Rerata Kehadiran: <strong class="<?= $isEligible ? 'text-success' : 'text-danger' ?>"><?= (float) $persenHadir ?>%</strong></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded p-2.5 text-center bg-light" style="font-size: 11px;">
                <div class="text-muted mb-0.5">Syarat Ujian (UTS / UAS):</div>
                <div class="fw-bold fs-6 <?= $isEligible ? 'text-success' : 'text-danger' ?>">
                    <?= $isEligible ? 'MEMENUHI SYARAT' : 'TIDAK MEMENUHI' ?>
                </div>
                <small class="text-muted">(Ambang batas minimal: <?= (int) ($pengaturan['syarat_kehadiran_persen'] ?? 75) ?>%)</small>
            </div>
        </div>
    </div>

    <!-- Kolom Tanda Tangan Pengesahan -->
    <div class="ttd-box">
        <div class="row text-center" style="font-size: 12px;">
            <div class="col-6">
                <div>Mahasiswa Bersangkutan,</div>
                <div style="height: 60px;"></div>
                <div class="fw-bold text-decoration-underline"><?= esc($mhs['nama_lengkap']) ?></div>
                <div class="text-muted">NIM. <?= esc($mhs['nim']) ?></div>
            </div>
            <div class="col-6">
                <div>Jayapura, <?= esc(date('d F Y')) ?></div>
                <div>Dosen Pengampu Mata Kuliah,</div>
                <div style="height: 45px;"></div>
                <div class="fw-bold text-decoration-underline"><?= esc($kelas['dosen_nama'] ?? 'Dosen Pengampu') ?></div>
                <div class="text-muted">NIP. <?= esc($kelas['nip'] ?? '.....................................') ?></div>
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
            width: 80,
            height: 80,
            colorDark: '#0f172a',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }
});
</script>
</body>
</html>
