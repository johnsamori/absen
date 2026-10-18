# Blueprint & Rencana Pengembangan: Sistem Informasi Absensi Mahasiswa FISIP UNCEN

## 1. Pendahuluan & Latar Belakang
Sistem Informasi Absensi Mahasiswa FISIP Universitas Cenderawasih (UNCEN) adalah aplikasi presensi perkuliahan berbasis web responsif yang dikembangkan menggunakan **CodeIgniter 4**. Aplikasi ini ditujukan untuk memodernisasi pencatatan kehadiran mahasiswa secara akurat, real-time, dan meminimalisir praktik kecurangan (titip absen) menggunakan integrasi **Dynamic QR Code** dan **Geofencing GPS**.

### Lingkup Program Studi FISIP UNCEN:
1. S1 Ilmu Administrasi Publik / Negara
2. S1 Ilmu Pemerintahan
3. S1 Hubungan Internasional
4. S1 Ilmu Komunikasi
5. S1 Sosiologi
6. S1 Antropologi Sosial
7. Program Pascasarjana (Magister Ilmu Administrasi, dll.)

---

## 2. Arsitektur & Teknologi

| Komponen | Spesifikasi / Tools |
| :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.4+ |
| **Framework Web** | CodeIgniter 4 (MVC, Session Auth, Filters, Service Container) |
| **Database** | MySQL / MariaDB (XAMPP Environment) |
| **Frontend UI** | Bootstrap 5, FontAwesome 6, Ajax / Fetch API |
| **QR Code Engine** | • Server: Enkripsi Dynamic Token HMAC-SHA256 & QR Generator<br>• Client: `html5-qrcode.min.js` (browser-native mobile scanner) |
| **Geolokasi** | HTML5 Geolocation API + Server-side Haversine Formula Verification |
| **Reporting / Export** | Cetak BAP & Kartu Presensi Print/PDF, Rekapitulasi Excel / CSV |
| **Hosting Environment** | Apache XAMPP (`/opt/lampp/htdocs/absen`) |

---

## 3. Matriks Hak Akses (User Roles)

### 1. Super Admin (Bagian Akademik FISIP)
- Kelola Data Master: Program Studi, Gedung & Ruang Kelas, Dosen, Mahasiswa, Tahun Akademik/Semester.
- Manajemen Mata Kuliah, Kurikulum, dan Alokasi Jadwal Kelas.
- Plotting KRS Mahasiswa & Dosen Pengampu Kelas.
- Konfigurasi Kampus: Koordinat titik pusat FISIP UNCEN (Latitude & Longitude) serta radius toleransi (meter).
- Audit trail dan monitoring aktivitas presensi.

### 2. Dosen Pengampu
- Akses jadwal mengajar mingguan dan daftar kelas aktif.
- Membuka sesi perkuliahan (Pertemuan 1 sampai 16).
- Pengisian Rencana Perkuliahan / Berita Acara (Materi, Jam Buka/Tutup).
- Menampilkan **Layar Proyektor QR Code Dinamis** (refresh otomatis setiap 20 detik).
- Live counter kehadiran mahasiswa saat perkuliahan berlangsung.
- Validasi status kehadiran: Hadir (H), Izin (I), Sakit (S), Alpa (A).
- Konfirmasi/verifikasi pengajuan surat sakit/izin mahasiswa.
- Export rekapitulasi absensi & cetak Berita Acara Perkuliahan (BAP).

### 3. Mahasiswa
- Dashboard jadwal kuliah hari ini dan persentase kehadiran per mata kuliah.
- Melakukan scan QR Code via kamera HP dan verifikasi GPS otomatis.
- Input kode token cadangan jika perangkat mengalami kendala kamera.
- Pengajuan izin/sakit dengan melampirkan foto surat dokter/keterangan resmi.
- Notifikasi status eligibilitas syarat mengikuti UTS/UAS (Threshold >= 75%).

### 4. Pimpinan (Dekanat / Kaprodi)
- Dashboard pemantauan statistik ketercapaian tatap muka dosen.
- Grafik persentase rerata kehadiran mahasiswa per program studi.
- Laporan evaluasi akademik semesteran.

---

## 4. Skema Basis Data (Database Schema)

1. `users` : id, username, password_hash, email, role (admin, dosen, mahasiswa, pimpinan), is_active, avatar, created_at, updated_at
2. `program_studi` : id, kode_prodi, nama_prodi, jenjang, kaprodi
3. `dosen` : id, user_id, nip, nidn, nama_lengkap, gelar_depan, gelar_belakang, prodi_id, no_hp
4. `mahasiswa` : id, user_id, nim, nama_lengkap, prodi_id, angkatan, kelas_paralel, no_hp
5. `tahun_akademik` : id, tahun, semester, is_active
6. `mata_kuliah` : id, kode_mk, nama_mk, sks, prodi_id, semester_paket
7. `kelas_kuliah` : id, tahun_akademik_id, mata_kuliah_id, dosen_id, nama_kelas, hari, jam_mulai, jam_selesai, ruangan
8. `peserta_kelas` : id, kelas_kuliah_id, mahasiswa_id
9. `pertemuan` : id, kelas_kuliah_id, pertemuan_ke, tanggal, jam_buka, jam_tutup, materi_kuliah, qr_secret_salt, token_cadangan, status
10. `presensi` : id, pertemuan_id, mahasiswa_id, waktu_presensi, status (Hadir, Izin, Sakit, Alpa), metode_absen, latitude, longitude, jarak_meter, bukti_dokumen, catatan_keterangan
11. `pengaturan_kampus` : id, nama_lokasi, latitude_pusat, longitude_pusat, radius_toleransi_meter, syarat_kehadiran_persen

---

## 5. Logika Presensi & Proteksi Keamanan

### A. Dynamic QR Code (Anti-Titip Absen)
1. Dosen membuka pertemuan, server membuat `qr_secret_salt` unik.
2. Setiap interval 20 detik, frontend proyektor meminta token dinamis baru yang dihitung berdasarkan interval waktu:
   Payload = `hash_hmac('sha256', pertemuan_id + floor(timestamp / 20), qr_secret_salt)`
3. Token kedaluwarsa setelah 20 detik sehingga foto layar tidak bisa dibagikan via chat.

### B. Validasi Geofencing (Formula Haversine di Server)
Saat mahasiswa memindai QR, peramban mengirimkan koordinat latitude dan longitude. Server memvalidasi jarak ke titik kampus FISIP UNCEN:
- Jika jarak <= radius toleransi (default: 150 meter), kehadiran dicatat sebagai Hadir.
- Jika jarak > radius toleransi, presensi ditolak dengan pesan peringatan di luar jangkauan kampus.

### C. Fallback Jaringan / Kendala GPS
- Dosen dapat mengumumkan 6-digit token cadangan atau mengubah status secara manual langsung di kelas.

---

## 6. Tahapan Eksekusi
1. Inisialisasi CodeIgniter 4 dan konfigurasi environment.
2. Migrasi skema database & Seeder data awal FISIP UNCEN.
3. Autentikasi multi-role & Middleware filter.
4. Modul Admin (Master data, kelas, plotting).
5. Modul Dosen (Pertemuan, Dynamic QR generator, live attendance).
6. Modul Mahasiswa (Scanner HTML5 camera, GPS Haversine).
7. Laporan BAP, Rekapitulasi Presensi, dan Pengujian End-to-End.
