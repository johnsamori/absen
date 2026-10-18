<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h4 class="mb-4">Konfigurasi Kampus</h4>

<div class="card" style="max-width: 640px;">
    <div class="card-header">
        <i class="fa-solid fa-location-dot me-1"></i>Titik Pusat & Geofencing Presensi
    </div>
    <div class="card-body">
        <form method="post" action="<?= site_url('admin/pengaturan') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" class="form-control" value="<?= esc(old('nama_lokasi', $row['nama_lokasi'] ?? '')) ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude Pusat</label>
                    <input type="number" step="any" name="latitude_pusat" class="form-control"
                           value="<?= esc(old('latitude_pusat', $row['latitude_pusat'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude Pusat</label>
                    <input type="number" step="any" name="longitude_pusat" class="form-control"
                           value="<?= esc(old('longitude_pusat', $row['longitude_pusat'] ?? '')) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Radius Toleransi (meter)</label>
                    <input type="number" name="radius_toleransi_meter" class="form-control"
                           value="<?= esc(old('radius_toleransi_meter', $row['radius_toleransi_meter'] ?? 150)) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Syarat Kehadiran UTS/UAS (%)</label>
                    <input type="number" name="syarat_kehadiran_persen" class="form-control" min="1" max="100"
                           value="<?= esc(old('syarat_kehadiran_persen', $row['syarat_kehadiran_persen'] ?? 75)) ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Simpan Konfigurasi</button>
        </form>
    </div>
</div>

<div class="alert alert-info mt-3" style="max-width: 640px;">
    <i class="fa-solid fa-circle-info me-1"></i>
    Mahasiswa hanya dapat melakukan presensi (Hadir) bila berada dalam radius toleransi dari titik pusat kampus.
    Koordinat default mengarah ke kampus FISIP UNCEN (Abepura, Jayapura).
</div>

<?= $this->endSection() ?>
