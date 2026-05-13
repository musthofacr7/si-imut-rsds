<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'login']);

// Myth:Auth Routes
$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], function($routes) {
    // Login/out
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');

    // Registration
    // $routes->get('register', 'AuthController::register', ['as' => 'register']);
    // $routes->post('register', 'AuthController::attemptRegister');

    // Activation
    // $routes->get('activate-account', 'AuthController::activateAccount', ['as' => 'activate-account']);
    // $routes->get('resend-activate-account', 'AuthController::resendActivateAccount', ['as' => 'resend-activate-account']);

    // Forgot Password
    // $routes->get('forgot', 'AuthController::forgotPassword', ['as' => 'forgot']);
    // $routes->post('forgot', 'AuthController::attemptForgot');
    // $routes->get('reset-password', 'AuthController::resetPassword', ['as' => 'reset-password']);
    // $routes->post('reset-password', 'AuthController::attemptReset');
});

// Dashboard Routes (Protected)
$routes->group('dashboard', ['filter' => 'login'], function($routes) {
    $routes->get('/', 'Dashboard::index');
});

// Profile Routes (Protected, All logged in users)
$routes->group('profile', ['filter' => 'login'], function($routes) {
    $routes->get('change-password', 'Users::changePassword');
    $routes->post('change-password', 'Users::updatePassword');
});

// User Management Routes (Protected, Administrator only)
$routes->group('users', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('create', 'Users::create');
    $routes->post('store', 'Users::store');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->post('update/(:num)', 'Users::update/$1');
    $routes->get('delete/(:num)', 'Users::delete/$1');
});

// Jenis Indikator Routes (Protected, Administrator only)
$routes->group('jenis-indikator', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'JenisIndikator::index');
    $routes->get('create', 'JenisIndikator::create');
    $routes->post('store', 'JenisIndikator::store');
    $routes->get('edit/(:num)', 'JenisIndikator::edit/$1');
    $routes->post('update/(:num)', 'JenisIndikator::update/$1');
    $routes->get('delete/(:num)', 'JenisIndikator::delete/$1');
});

// Master Satuan Indikator Routes (Protected, Administrator only)
$routes->group('master-satuan-indikator', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'MasterSatuanIndikator::index');
    $routes->get('create', 'MasterSatuanIndikator::create');
    $routes->post('store', 'MasterSatuanIndikator::store');
    $routes->get('edit/(:num)', 'MasterSatuanIndikator::edit/$1');
    $routes->post('update/(:num)', 'MasterSatuanIndikator::update/$1');
    $routes->delete('delete/(:num)', 'MasterSatuanIndikator::delete/$1');
});

// Indikator Mutu Routes (Protected, Administrator only)
$routes->group('indikator-mutu', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'IndikatorMutu::index');
    $routes->get('create', 'IndikatorMutu::create');
    $routes->post('store', 'IndikatorMutu::store');
    $routes->get('show/(:num)', 'IndikatorMutu::show/$1');
    $routes->get('export-pdf-all', 'IndikatorMutu::exportPdfAll');
    $routes->get('export-pdf/(:num)', 'IndikatorMutu::exportPdf/$1');
    $routes->get('export-word/(:num)', 'IndikatorMutu::exportWord/$1');
    $routes->get('edit/(:num)', 'IndikatorMutu::edit/$1');
    $routes->post('update/(:num)', 'IndikatorMutu::update/$1');
    $routes->get('delete/(:num)', 'IndikatorMutu::delete/$1');
});

// Area Pengukuran Routes (Protected, Administrator only)
$routes->group('area-pengukuran', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'AreaPengukuran::index');
    $routes->get('create', 'AreaPengukuran::create');
    $routes->post('store', 'AreaPengukuran::store');
    $routes->get('edit/(:num)', 'AreaPengukuran::edit/$1');
    $routes->post('update/(:num)', 'AreaPengukuran::update/$1');
    $routes->get('delete/(:num)', 'AreaPengukuran::delete/$1');
});


// Formulir IKP Routes (Protected, Administrator and PJ Mutu)
$routes->group('formulir-ikp', ['filter' => 'role:administrator,pj-mutu'], function($routes) {
    $routes->get('/', 'FormulirIKP::index');
    $routes->get('daftar', 'FormulirIKP::daftar');
    $routes->get('create', 'FormulirIKP::create');
    $routes->post('store', 'FormulirIKP::store');
    $routes->get('view/(:num)', 'FormulirIKP::view/$1');
    $routes->get('edit/(:num)', 'FormulirIKP::edit/$1');
    $routes->post('update/(:num)', 'FormulirIKP::update/$1');
    $routes->get('delete/(:num)', 'FormulirIKP::delete/$1');
});

// Investigasi Sederhana Routes (Protected, Administrator and PJ Mutu)
$routes->group('investigasi-sederhana', ['filter' => 'role:administrator,pj-mutu'], function($routes) {
    $routes->get('/', 'InvestigasiSederhana::index');
    $routes->get('create', 'InvestigasiSederhana::create');
    $routes->get('create/(:num)', 'InvestigasiSederhana::create/$1');
    $routes->post('store', 'InvestigasiSederhana::store');
    $routes->get('view/(:num)', 'InvestigasiSederhana::view/$1');
    $routes->get('edit/(:num)', 'InvestigasiSederhana::edit/$1');
    $routes->post('update/(:num)', 'InvestigasiSederhana::update/$1');
    $routes->post('delete/(:num)', 'InvestigasiSederhana::delete/$1'); // delete using POST in the modal
});

// Input Indikator Mutu RS Routes (Protected, PJ Mutu and Administrator)
$routes->group('input-indikator-mutu', ['filter' => 'role:pj-mutu'], function($routes) {
    $routes->get('/', 'InputIndikatorMutu::index');
    $routes->get('index2', 'InputIndikatorMutu::index2');
    $routes->get('create', 'InputIndikatorMutu::create');
    $routes->post('store', 'InputIndikatorMutu::store');
    $routes->get('edit/(:num)', 'InputIndikatorMutu::edit/$1');
    $routes->post('update/(:num)', 'InputIndikatorMutu::update/$1');
    $routes->get('delete/(:num)', 'InputIndikatorMutu::delete/$1');
    $routes->get('get-detail/(:num)', 'InputIndikatorMutu::getDetail/$1');
});

// Latihan Routes (Protected, PJ Mutu only)
$routes->group('latihan', ['filter' => 'role:pj-mutu'], function($routes) {
    $routes->get('/', 'Latihan::index');
    $routes->get('get-data', 'Latihan::getData');
    $routes->post('store', 'Latihan::store');
    $routes->post('update/(:num)', 'Latihan::update/$1');
    $routes->get('delete/(:num)', 'Latihan::delete/$1');
});

// Setting Indikator Mutu Routes (Protected, Administrator only)
$routes->group('setting-indikator-mutu', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'SettingIndikatorMutu::index');
    $routes->get('create', 'SettingIndikatorMutu::create');
    $routes->post('store', 'SettingIndikatorMutu::store');
    $routes->get('edit/(:num)', 'SettingIndikatorMutu::edit/$1');
    $routes->post('update/(:num)', 'SettingIndikatorMutu::update/$1');
    $routes->get('delete/(:num)', 'SettingIndikatorMutu::delete/$1');
});

// Mapping Indikator Routes (Protected, Administrator only)
$routes->group('mapping-indikator', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'MappingIndikator::index');
    $routes->get('get-detail/(:num)', 'MappingIndikator::getDetail/$1');
});

// Rekap Indikator Mutu RS Routes (Protected, Administrator only)
$routes->group('rekap-indikator-mutu', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'RekapIndikatorMutu::index');
    $routes->post('get-chart-data', 'RekapIndikatorMutu::getChartData');
});

// Monitoring Input Routes (Protected, Administrator only)
$routes->group('monitoring-input', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'MonitoringInput::index');
});

// Rekap by Jenis Indikator Routes (Protected, Administrator only)
$routes->group('rekap-jenis-indikator', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'RekapJenisIndikator::index');
    $routes->post('get-data', 'RekapJenisIndikator::getData');
});

// Rekap by Area Pengukuran Routes (Protected, Administrator only)
$routes->group('rekap-area-pengukuran', ['filter' => 'role:administrator'], function($routes) {
    $routes->get('/', 'RekapAreaPengukuran::index');
    $routes->post('get-data', 'RekapAreaPengukuran::getData');
});

// PDSA Routes (Protected, Administrator & PJ Mutu)
$routes->group('pdsa', ['filter' => 'login'], function($routes) {
    $routes->get('/', 'PDSA::index');
    $routes->get('create', 'PDSA::create');
    $routes->post('store', 'PDSA::store');
    $routes->get('view/(:num)', 'PDSA::view/$1'); // New View Route
    $routes->get('edit/(:num)', 'PDSA::edit/$1');
    $routes->post('update/(:num)', 'PDSA::update/$1');
    $routes->get('delete/(:num)', 'PDSA::delete/$1');
});


