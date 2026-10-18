<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -------------------------------------------------------------------------
// Auth
// -------------------------------------------------------------------------
$routes->match(['GET', 'POST'], '/auth/login', 'AuthController::login', ['filter' => 'auth:guest']);
$routes->post('/auth/logout', 'AuthController::logout', ['filter' => 'auth']);

// -------------------------------------------------------------------------
// Beranda — redirect sesuai role
// -------------------------------------------------------------------------
$routes->get('/', 'DashboardController::index', ['filter' => 'auth']);

// -------------------------------------------------------------------------
// Super Admin
// -------------------------------------------------------------------------
$routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes) {
    $routes->get('/', 'AdminController::index');

    $routes->get('program-studi', 'ProgramStudiController::index');
    $routes->get('program-studi/create', 'ProgramStudiController::create');
    $routes->post('program-studi', 'ProgramStudiController::store');
    $routes->get('program-studi/edit/(:num)', 'ProgramStudiController::edit/$1');
    $routes->post('program-studi/update/(:num)', 'ProgramStudiController::update/$1');
    $routes->post('program-studi/delete/(:num)', 'ProgramStudiController::delete/$1');

    $routes->get('tahun-akademik', 'TahunAkademikController::index');
    $routes->get('tahun-akademik/create', 'TahunAkademikController::create');
    $routes->post('tahun-akademik', 'TahunAkademikController::store');
    $routes->get('tahun-akademik/edit/(:num)', 'TahunAkademikController::edit/$1');
    $routes->post('tahun-akademik/update/(:num)', 'TahunAkademikController::update/$1');
    $routes->post('tahun-akademik/delete/(:num)', 'TahunAkademikController::delete/$1');

    $routes->get('dosen', 'DosenController::index');
    $routes->get('dosen/create', 'DosenController::create');
    $routes->post('dosen', 'DosenController::store');
    $routes->get('dosen/edit/(:num)', 'DosenController::edit/$1');
    $routes->post('dosen/update/(:num)', 'DosenController::update/$1');
    $routes->post('dosen/delete/(:num)', 'DosenController::delete/$1');

    $routes->get('mahasiswa', 'MahasiswaController::index');
    $routes->get('mahasiswa/create', 'MahasiswaController::create');
    $routes->post('mahasiswa', 'MahasiswaController::store');
    $routes->get('mahasiswa/edit/(:num)', 'MahasiswaController::edit/$1');
    $routes->post('mahasiswa/update/(:num)', 'MahasiswaController::update/$1');
    $routes->post('mahasiswa/delete/(:num)', 'MahasiswaController::delete/$1');

    $routes->get('mata-kuliah', 'MataKuliahController::index');
    $routes->get('mata-kuliah/create', 'MataKuliahController::create');
    $routes->post('mata-kuliah', 'MataKuliahController::store');
    $routes->get('mata-kuliah/edit/(:num)', 'MataKuliahController::edit/$1');
    $routes->post('mata-kuliah/update/(:num)', 'MataKuliahController::update/$1');
    $routes->post('mata-kuliah/delete/(:num)', 'MataKuliahController::delete/$1');

    $routes->get('kelas-kuliah', 'KelasKuliahController::index');
    $routes->get('kelas-kuliah/create', 'KelasKuliahController::create');
    $routes->post('kelas-kuliah', 'KelasKuliahController::store');
    $routes->get('kelas-kuliah/edit/(:num)', 'KelasKuliahController::edit/$1');
    $routes->post('kelas-kuliah/update/(:num)', 'KelasKuliahController::update/$1');
    $routes->post('kelas-kuliah/delete/(:num)', 'KelasKuliahController::delete/$1');

    $routes->get('kelas-kuliah/(:num)/peserta', 'PesertaController::index/$1');
    $routes->post('kelas-kuliah/(:num)/peserta/tambah', 'PesertaController::tambah/$1');
    $routes->post('kelas-kuliah/(:num)/peserta/hapus-banyak', 'PesertaController::hapusBanyak/$1');
    $routes->post('peserta/(:num)/hapus', 'PesertaController::hapus/$1');

    $routes->get('pengaturan', 'PengaturanController::index');
    $routes->post('pengaturan', 'PengaturanController::update');

    $routes->get('users', 'UserController::index');
    $routes->get('users/create', 'UserController::create');
    $routes->post('users', 'UserController::store');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->post('users/delete/(:num)', 'UserController::delete/$1');

    $routes->get('import/(:segment)', 'ImportController::form/$1');
    $routes->post('import/(:segment)/proses', 'ImportController::proses/$1');
    $routes->get('import/(:segment)/template', 'ImportController::template/$1');
});

// -------------------------------------------------------------------------
// Dosen
// -------------------------------------------------------------------------
$routes->group('dosen', ['filter' => 'auth:dosen'], static function (RouteCollection $routes) {
    $routes->get('/', 'DosenDashboardController::index');

    $routes->get('pertemuan/(:num)', 'PertemuanController::index/$1');
    $routes->get('pertemuan/(:num)/buka', 'PertemuanController::buka/$1');
    $routes->post('pertemuan/(:num)/buka', 'PertemuanController::store/$1');
    $routes->get('pertemuan/(:num)/qr/(:num)', 'QrController::proyeksi/$1/$2');
    $routes->get('pertemuan/edit/(:num)', 'PertemuanController::edit/$1');
    $routes->post('pertemuan/update/(:num)', 'PertemuanController::update/$1');
    $routes->post('pertemuan/(:num)/tutup', 'PertemuanController::tutup/$1');
    $routes->post('pertemuan/(:num)/buka-lagi', 'PertemuanController::bukaLagi/$1');
    $routes->get('pertemuan/(:num)/rekap', 'PertemuanController::rekap/$1');
    $routes->get('pertemuan/(:num)/scan', 'PresensiDosenController::scanner/$1');
    $routes->post('pertemuan/(:num)/scan-mahasiswa', 'PresensiDosenController::scanMahasiswa/$1');
    $routes->post('pertemuan/(:num)/perpanjang-waktu', 'PresensiDosenController::perpanjangWaktu/$1');
    $routes->get('pertemuan/(:num)/kelola', 'PresensiDosenController::kelola/$1');
    $routes->post('pertemuan/(:num)/set-status', 'PresensiDosenController::setStatus/$1');
    $routes->post('presensi/(:num)/setujui', 'PresensiDosenController::setujuiIzin/$1');
    $routes->post('presensi/(:num)/tolak', 'PresensiDosenController::tolakIzin/$1');

    $routes->get('api/qr-token/(:num)', 'QrController::token/$1');
    $routes->get('api/live/(:num)', 'QrController::live/$1');
});

// -------------------------------------------------------------------------
// Mahasiswa
// -------------------------------------------------------------------------
$routes->group('mahasiswa', ['filter' => 'auth:mahasiswa'], static function (RouteCollection $routes) {
    $routes->get('/', 'MahasiswaDashboardController::index');
    $routes->get('qr', 'MahasiswaDashboardController::qr');
    $routes->get('kartu-absensi', 'MahasiswaDashboardController::kartuAbsensi');
    $routes->get('kartu-absensi/(:num)', 'MahasiswaDashboardController::kartuAbsensiDetail/$1');
    $routes->get('scan', 'ScanController::index');
    $routes->get('riwayat', 'PresensiController::riwayat');

    $routes->get('izin', 'IzinController::index');
    $routes->get('izin/ajukan', 'IzinController::ajukan');
    $routes->post('izin/ajukan', 'IzinController::store');
});

// Presensi (mahasiswa) — URL scan QR & submit
$routes->get('presensi/scan/(:num)/(:any)', 'ScanController::scan/$1/$2', ['filter' => 'auth:mahasiswa']);
$routes->post('presensi/submit', 'PresensiController::submit', ['filter' => 'auth:mahasiswa']);
$routes->post('presensi/token', 'PresensiController::token', ['filter' => 'auth:mahasiswa']);

// -------------------------------------------------------------------------
// Pimpinan
// -------------------------------------------------------------------------
$routes->get('pimpinan', 'PimpinanController::index', ['filter' => 'auth:pimpinan']);

// -------------------------------------------------------------------------
// Laporan (admin, dosen, pimpinan)
// -------------------------------------------------------------------------
$routes->group('laporan', ['filter' => 'auth:admin,dosen,pimpinan'], static function (RouteCollection $routes) {
    $routes->get('/', 'LaporanController::index');
    $routes->get('bap/(:num)', 'LaporanController::bap/$1');
    $routes->get('rekap/(:num)', 'LaporanController::rekap/$1');
    $routes->get('kartu-absensi/(:num)/(:num)', 'LaporanController::kartuAbsensi/$1/$2');
    $routes->get('kartu-mahasiswa/(:num)', 'LaporanController::kartuMahasiswaSemester/$1');
    $routes->get('export-csv/(:num)', 'LaporanController::exportCsv/$1');
});
