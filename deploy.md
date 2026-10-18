# Panduan Deployment CodeIgniter 4 pada Linux Debian 13 (Trixie)
### Stack: Nginx + MariaDB + PHP-FPM

Dokumen ini berisi panduan lengkap langkah demi langkah untuk melakukan deploy aplikasi CodeIgniter 4 (Absensi FISIP) ke server berbasis **Linux Debian 13** menggunakan **Nginx**, **MariaDB**, dan **PHP-FPM**.

---

## Daftar Isi
1. [Spesifikasi & Kebutuhan Server](#1-spesifikasi--kebutuhan-server)
2. [Update Sistem & Paket Dasar](#2-update-sistem--paket-dasar)
3. [Instalasi Nginx, MariaDB, dan PHP-FPM](#3-instalasi-nginx-mariadb-dan-php-fpm)
4. [Konfigurasi Keamanan & Database MariaDB](#4-konfigurasi-keamanan--database-mariadb)
5. [Instalasi Composer](#5-instalasi-composer)
6. [Deploy Source Code Aplikasi](#6-deploy-source-code-aplikasi)
7. [Konfigurasi Environment (.env)](#7-konfigurasi-environment-env)
8. [Migrasi & Seeding Database](#8-migrasi--seeding-database)
9. [Hak Akses & Permission Folder](#9-hak-akses--permission-folder)
10. [Konfigurasi Virtual Host Nginx](#10-konfigurasi-virtual-host-nginx)
11. [Konfigurasi Firewall (UFW) & SSL (Certbot Let's Encrypt)](#11-konfigurasi-firewall-ufw--ssl-certbot-lets-encrypt)
12. [Troubleshooting & Pemeliharaan](#12-troubleshooting--pemeliharaan)

---

## 1. Spesifikasi & Kebutuhan Server

- **OS**: Debian 13 (Trixie) 64-bit
- **Web Server**: Nginx
- **Database**: MariaDB Server 10.11+ / 11.x
- **PHP**: PHP 8.2 atau 8.3 dengan ekstensi:
  - `php-fpm`
  - `php-cli`
  - `php-mysql` (MySQLi & PDO)
  - `php-intl` (wajib untuk CodeIgniter 4)
  - `php-mbstring` (wajib)
  - `php-curl`
  - `php-xml`
  - `php-zip`
  - `php-gd`
- **Akses**: User dengan hak `sudo` atau `root`

---

## 2. Update Sistem & Paket Dasar

Login ke server via SSH, lalu jalankan pembaruan paket sistem:

```bash
sudo apt update && sudo apt upgrade -y
```

Pasang beberapa tool utilitas yang dibutuhkan:

```bash
sudo apt install -y curl wget git unzip zip ufw software-properties-common ca-certificates lsb-release apt-transport-https
```

---

## 3. Instalasi Nginx, MariaDB, dan PHP-FPM

### A. Instal Nginx & MariaDB
```bash
sudo apt install -y nginx mariadb-server
```

Pastikan Nginx dan MariaDB otomatis berjalan saat boot:
```bash
sudo systemctl enable nginx
sudo systemctl start nginx

sudo systemctl enable mariadb
sudo systemctl start mariadb
```

### B. Instal PHP & Ekstensi yang Dibutuhkan
Debian 13 menyediakan PHP 8.2 / 8.3 secara default di repositori resminya:

```bash
sudo apt install -y php-fpm php-cli php-mysql php-intl php-mbstring php-curl php-xml php-zip php-gd
```

Cek versi PHP yang terinstal:
```bash
php -v
```

Periksa socket PHP-FPM yang aktif (biasanya `/run/php/php8.2-fpm.sock` atau `/run/php/php8.3-fpm.sock`):
```bash
ls -la /run/php/
```
> **Catatan**: Catat nama socket ini (misal: `php8.2-fpm.sock` atau `php8.3-fpm.sock`) untuk konfigurasi Nginx di langkah ke-10.

---

## 4. Konfigurasi Keamanan & Database MariaDB

### A. Amankan Instalasi MariaDB
Jalankan skrip keamanan interaktif:
```bash
sudo mariadb-secure-installation
```
Ikuti instruksi:
- Set root password? **Y** (masukkan password root yang kuat)
- Remove anonymous users? **Y**
- Disallow root login remotely? **Y**
- Remove test database and access to it? **Y**
- Reload privilege tables now? **Y**

### B. Buat Database dan User Khusus Aplikasi
Masuk ke prompt MariaDB:
```bash
sudo mariadb -u root -p
```

Eksekusi perintah SQL berikut (sesuaikan nama database, user, dan password):
```sql
CREATE DATABASE db_absensi_fisip CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'PasswordKuat_123!';

GRANT ALL PRIVILEGES ON db_absensi_fisip.* TO 'app_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

---

## 5. Instalasi Composer

Unduh dan pasang Composer secara global ke `/usr/local/bin`:

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

Verifikasi instalasi:
```bash
composer --version
```

---

## 6. Deploy Source Code Aplikasi

Buat direktori web jika belum ada:
```bash
sudo mkdir -p /var/www/absen
```

### Opsi A: Menggunakan Git (Direkomendasikan)
Clone repositori ke direktori `/var/www/absen`:
```bash
sudo git clone https://github.com/username/repository-anda.git /var/www/absen
cd /var/www/absen
```

### Opsi B: Transfer File via SCP / RSYNC dari Lokal
Dari komputer lokal Anda:
```bash
rsync -avz --exclude 'vendor' --exclude 'writable/logs/*' /opt/lampp/htdocs/absen/ user@ip_server:/var/www/absen/
```

### Instal Dependensi Composer
Masuk ke direktori proyek dan instal vendor tanpa paket dev:
```bash
cd /var/www/absen
composer install --no-dev --optimize-autoloader
```

---

## 7. Konfigurasi Environment (.env)

Salin template file `.env` jika belum ada:
```bash
cd /var/www/absen
cp env .env
```

Edit file `.env`:
```bash
nano .env
```

Sesuaikan konfigurasi penting berikut:

```ini
#--------------------------------------------------------------------
# ENVIRONMENT (Wajib 'production' saat live)
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
# Ganti dengan IP Server atau Domain Anda dengan akhiran / (WAJIB tanpa /absen/public/)
# Contoh jika menggunakan IP:
app.baseURL = 'http://54.169.106.29/'
# Contoh jika sudah ada domain dan SSL:
# app.baseURL = 'https://absensi.domainanda.com/'
# app.forceGlobalSecureRequests = true
app.forceGlobalSecureRequests = false
app.indexPage = ''

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = 127.0.0.1
database.default.database = db_absensi_fisip
database.default.username = app_user
database.default.password = PasswordKuat_123!
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

#--------------------------------------------------------------------
# ENCRYPTION KEY
#--------------------------------------------------------------------
# Gunakan key aplikasi Anda atau generate baru jika belum ada
encryption.key = hex2bin:d6a76dadd350d98fba499da5638aff92f4875341461ac3a6ffb0bfa99755ca4a

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------
session.driver = CodeIgniter\Session\Handlers\FileHandler
session.savePath = null
```

Simpan file (`Ctrl + O`, lalu `Enter`, kemudian keluar `Ctrl + X`).

---

## 8. Migrasi & Seeding Database

Jalankan perintah spark untuk membentuk tabel dan mengisi data awal:

```bash
cd /var/www/absen

# Menjalankan migrasi tabel
php spark migrate

# Menjalankan Seeder data awal (MainSeeder)
php spark db:seed MainSeeder
```

---

## 9. Hak Akses & Permission Folder

CodeIgniter 4 membutuhkan akses tulis ke direktori `writable/`. Nginx dan PHP-FPM di Debian berjalan dengan user `www-data`.

Jalankan perintah berikut:

```bash
# Ubah ownership seluruh project ke www-data
sudo chown -R www-data:www-data /var/www/absen

# Beri permission standar folder dan file
sudo find /var/www/absen -type d -exec chmod 755 {} \;
sudo find /var/www/absen -type f -exec chmod 644 {} \;

# Berikan izin khusus tulis untuk folder writable
sudo chmod -R 775 /var/www/absen/writable

# Khusus file executable spark
sudo chmod +x /var/www/absen/spark
```

---

## 10. Konfigurasi Virtual Host Nginx

Buat konfigurasi server block baru untuk aplikasi:

```bash
sudo nano /etc/nginx/sites-available/absen
```

Masukkan konfigurasi berikut (sesuaikan `server_name` dan path PHP-FPM socket):

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name absensi.domainanda.com; # Ganti dengan domain atau IP publik server

    # PENTING: Root harus mengarah ke direktori public/
    root /var/www/absen/public;
    index index.php index.html;

    charset utf-8;

    # Log akses dan error
    access_log /var/log/nginx/absen_access.log;
    error_log /var/log/nginx/absen_error.log;

    # URL rewriting khas CodeIgniter 4
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    # Blok eksekusi PHP melalui PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        
        # Sesuaikan dengan versi PHP yang aktif di Debian 13
        # Cek versi via: ls /run/php/php*-fpm.sock
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        # fastcgi_pass unix:/run/php/php8.3-fpm.sock;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    # Keamanan: Larang akses langsung ke file tersembunyi (seperti .env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Optimasi Cache untuk Asset Statis
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|webp|svg|woff|woff2|ttf)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }

    # Batas ukuran upload file (sesuaikan kebutuhan, misal 20M)
    client_max_body_size 20M;
}
```

Aktifkan konfigurasi dengan membuat symlink ke `sites-enabled`:

```bash
sudo ln -s /etc/nginx/sites-available/absen /etc/nginx/sites-enabled/
```

Nonaktifkan default config jika tidak diperlukan lagi:
```bash
sudo rm -f /etc/nginx/sites-enabled/default
```

Uji sintaks konfigurasi Nginx:
```bash
sudo nginx -t
```
Jika muncul pesan `syntax is ok` dan `test is successful`, reload Nginx:
```bash
sudo systemctl reload nginx
```

---

## 11. Konfigurasi Firewall (UFW) & SSL (Certbot Let's Encrypt)

### A. Konfigurasi Firewall
```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```
Cek status firewall:
```bash
sudo ufw status
```

### B. Pasang Sertifikat SSL Gratis (HTTPS)
Instal Certbot dan plugin Nginx:
```bash
sudo apt install -y certbot python3-certbot-nginx
```

Jalankan Certbot untuk mengonfigurasi SSL otomatis:
```bash
sudo certbot --nginx -d absensi.domainanda.com
```
Ikuti petunjuk (masukkan email dan setujui ToS). Certbot akan secara otomatis memperbarui konfigurasi Nginx agar redirect HTTP ke HTTPS.

Uji coba auto-renewal sertifikat:
```bash
sudo certbot renew --dry-run
```

---

## 12. Troubleshooting & Pemeliharaan

### Perintah Cek Status Layanan
```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm   # atau php8.3-fpm
sudo systemctl status mariadb
```

### Memeriksa Log Error
1. **Nginx Error Log**:
   ```bash
   sudo tail -f /var/log/nginx/absen_error.log
   ```
2. **PHP-FPM Log**:
   ```bash
   sudo tail -f /var/log/php8.2-fpm.log
   ```
3. **CodeIgniter Log**:
   ```bash
   tail -f /var/www/absen/writable/logs/log-*.log
   ```

### Jika Terjadi Error 500 / Blank Screen
1. Periksa izin direktori `writable/`:
   ```bash
   sudo chown -R www-data:www-data /var/www/absen/writable
   sudo chmod -R 775 /var/www/absen/writable
   ```
2. Periksa apakah ekstensi `php-intl` dan `php-mbstring` sudah terpasang:
   ```bash
   php -m | grep -E "intl|mbstring"
   ```
3. Periksa file `.env` apakah database credentials sudah tepat dan server database sedang berjalan.

### Backup Database & File
Lakukan backup rutin database dan folder writable:
```bash
# Backup Database
mysqldump -u app_user -p db_absensi_fisip > backup_$(date +%F).sql

# Backup Dokumen/Upload
tar -czvf backup_writable_$(date +%F).tar.gz /var/www/absen/writable/
```
