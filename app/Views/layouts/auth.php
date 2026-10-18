<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?= esc($title ?? 'Login') ?> — Absensi FISIP UNCEN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="brand-logo"><i class="fa-solid fa-building-columns"></i></div>
            <h4 class="fw-bold mb-1">Absensi Mahasiswa FISIP</h4>
            <p class="text-muted mb-0">Universitas Cenderawasih</p>
        </div>
        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>
