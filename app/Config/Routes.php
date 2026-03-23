<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth\AuthController::index');
$routes->get('/login', 'Auth\AuthController::index');
$routes->post('/login/process', 'Auth\AuthController::login');
$routes->get('/register', 'Auth\AuthController::register');
$routes->post('/register/process', 'Auth\AuthController::processRegister');
$routes->get('/logout', 'Auth\AuthController::logout');
$routes->get('/dashboard', 'Auth\AuthController::dashboard');

// Admin Routes
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Admin\AdminController::index');
    // Siswa
    $routes->get('siswa', 'Admin\AdminController::siswa');
    $routes->post('siswa/store', 'Admin\AdminController::storeSiswa');
    $routes->post('siswa/delete/(:num)', 'Admin\AdminController::deleteSiswa/$1');
    // Guru
    $routes->get('guru', 'Admin\AdminController::guru');
    $routes->post('guru/store', 'Admin\AdminController::storeGuru');
    $routes->post('guru/delete/(:num)', 'Admin\AdminController::deleteGuru/$1');
    // Kelas
    $routes->get('kelas', 'Admin\AdminController::kelas');
    $routes->post('kelas/store', 'Admin\AdminController::storeKelas');
    // QR Code
    $routes->get('generate-qr', 'Admin\AdminController::generateQr');
    $routes->post('generate-qr/process', 'Admin\AdminController::processGenerateQr');
    $routes->post('quick-activate', 'Admin\AdminController::quickActivateSession');
    // Absensi Wajah
    $routes->get('absensi-wajah', 'Admin\AdminController::absensiWajah');
    // Laporan
    $routes->get('laporan', 'Admin\AdminController::laporan');
});

// Guru Routes
$routes->group('guru', ['filter' => 'role:guru'], function ($routes) {
    $routes->get('/', 'Guru\GuruController::index');
    $routes->get('absensi', 'Guru\GuruController::absensiKelas');
    $routes->get('rekap', 'Guru\GuruController::rekapAbsensi');
});

// Siswa Routes
$routes->group('siswa', ['filter' => 'role:siswa'], static function ($routes) {
    $routes->get('/', 'Siswa\SiswaController::index');
    $routes->get('scan', 'Siswa\SiswaController::scanQr');
    $routes->post('scan/process', 'Siswa\SiswaController::processScan');
    $routes->get('riwayat', 'Siswa\SiswaController::riwayat');
    $routes->get('absensi-wajah', 'Siswa\SiswaController::absensiWajah');
    $routes->post('proses-absensi-wajah', 'Siswa\SiswaController::prosesAbsensiWajah');
});
