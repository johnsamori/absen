# Sistem Informasi Absensi Mahasiswa FISIP UNCEN

Aplikasi presensi perkuliahan berbasis web untuk Fakultas Ilmu Sosial dan Ilmu Politik
Universitas Cenderawasih (UNCEN), dibangun dengan **CodeIgniter 4.7**. Aplikasi ini
memodernisasi pencatatan kehadiran mahasiswa secara real-time dengan proteksi anti
kecurangan (titip absen) melalui **Dynamic QR Code** dan **Geofencing GPS**.

## Fitur Utama

- **Dynamic QR Code** — token HMAC-SHA256 berganti otomatis setiap 20 detik, sehingga
  foto layar QR tidak bisa dibagikan via chat.
- **Geofencing GPS** — validasi jarak mahasiswa ke titik pusat kampus menggunakan
  formula Haversine (radius default 150 m, dapat diubah di Konfigurasi Kampus).
- **Token cadangan 6-digit** — fallback bila kamera/GPS mahasiswa bermasalah.
- **Multi-role** — Super Admin (akademik), Dosen, Mahasiswa, Pimpinan (dekanat/kaprodi).
- **Responsif & mobile-first** — tampilan modern otomatis menyesuaikan perangkat; sidebar
  berubah jadi menu geser (offcanvas) + navigasi bawah di layar HP.
- **Pengajuan izin/sakit** dengan unggah bukti (foto surat dokter/keterangan).
- **Rekapitulasi & BAP** — cetak Berita Acara Perkuliahan, rekap matriks, export CSV.
- **Eligibilitas UTS/UAS** — indikator kehadiran minimal (default 75%).

## Teknologi

| Komponen | Spesifikasi |
|---|---|
| Bahasa | PHP 8.2+ |
| Framework | CodeIgniter 4.7 |
| Database | MySQL / MariaDB (XAMPP) |
| Frontend | Bootstrap 5, FontAwesome 6, Fetch API |
| QR Engine | Server: HMAC-SHA256 · Client: `html5-qrcode.min.js` + `qrcode.min.js` |
| Geolokasi | HTML5 Geolocation API + Haversine (server-side) |

## Struktur Direktori

```
app/
  Config/          Routes, Filters (auth+csrf), App, Database, Security
  Controllers/     Auth, Admin (CRUD master), Dosen, Mahasiswa, Pimpinan, Laporan
  Database/
    Migrations/    2026-10-17-000001_CreateAbsensiTables.php
    Seeds/         MainSeeder.php
  Filters/         AuthFilter.php
  Helpers/         absensi_helper.php (Haversine, current_user, status_badge, dll)
  Models/          11 model (users, prodi, dosen, mahasiswa, mk, kelas, dll)
  Views/           layouts/ + auth/ + admin/ + dosen/ + mahasiswa/ + pimpinan/ + laporan/
public/
  assets/          CSS
  vendor/          html5-qrcode.min.js, qrcode.min.js (lokal, tanpa CDN)
  uploads/bukti/   lampiran surat izin/sakit
```

## Cara Instalasi (XAMPP/Lampp)

```bash
cd /opt/lampp/htdocs/apptest
cp env .env                      # sudah disiapkan (lihat .env)
/opt/lampp/bin/php spark migrate --force
/opt/lampp/bin/php spark db:seed MainSeeder
```

Database `db_absensi_fisip` dibuat otomatis; pastikan MariaDB XAMPP berjalan.

Akses aplikasi: http://localhost/apptest/public/

## Akun Default (dari Seeder)

| Role | Username | Password |
|---|---|---|
| Super Admin | `admin` | `admin123` |
| Pimpinan | `pimpinan` | `pimpinan123` |
| Dosen | `dosen1` … `dosen4` | `dosen123` |
| Mahasiswa | `mhs1` … `mhs10` | `mhs123` |

## Alur Penggunaan

### Dosen
1. Login → Dashboard menampilkan jadwal & kelas aktif.
2. Pilih kelas → **Buka Sesi** (isi pertemuan ke-, tanggal, materi).
3. Sistem membuka halaman **Proyeksi QR Dinamis** (refresh tiap 20 detik) + live counter.
4. Kelola presensi: ubah status manual, setujui/tolak pengajuan izin/sakit.
5. Rekap → **Cetak BAP**.

### Mahasiswa
1. Login → Dashboard jadwal hari ini + persentase kehadiran per MK.
2. **Scan QR** dengan kamera HP, atau masukkan **token cadangan** 6-digit.
3. Browser meminta izin lokasi → server memvalidasi jarak (Haversine) → presensi tersimpan.
4. **Izin/Sakit**: ajukan dengan lampiran bukti → menunggu verifikasi dosen.

### Super Admin
Kelola master data (program studi, tahun akademik, dosen, mahasiswa, mata kuliah,
kelas kuliah & plotting peserta), **Manajemen User** (akun login, role, reset
password, aktif/nonaktif), serta **Konfigurasi Kampus** (koordinat pusat + radius).
Mendukung **impor massal** mahasiswa, dosen, dan mata kuliah dari file CSV (tombol
"Impor" pada tiap halaman data; unduh template lalu isi sesuai format).

### Pimpinan
Dashboard statistik: rerata kehadiran per prodi, distribusi status, ketercapaian tatap muka dosen.

## Catatan Keamanan & Deployment

- Koordinat default pusat kampus mengarah ke **FISIP UNCEN (Abepura, Jayapura)**:
  `-2.596944, 140.668556` — sesuaikan di menu Konfigurasi Kampus bila diperlukan.
- QR memuat URL `http://localhost/apptest/public/...`. Agar mahasiswa dapat memindai dari
  **HP di jaringan kampus**, ganti `app.baseURL` di `.env` menjadi alamat IP/LAN server
  (mis. `http://192.168.1.10/apptest/public/`), bukan `localhost`.
- Semua aksi ubah-data (hapus, reset, set status, approve) menggunakan method POST
  dengan proteksi CSRF. Token CSRF dikirim via header `X-CSRF-TOKEN` untuk fetch/JSON.
- Dynamic token kedaluwarsa dalam ~20 detik (interval waktu HMAC), dengan toleransi
  1 interval untuk kompensasi jeda jaringan.
