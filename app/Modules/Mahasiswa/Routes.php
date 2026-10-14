<?php
// app/Modules/Mahasiswa/Routes.php

$routes->group('dashboard/mahasiswa', [
    'namespace' => 'App\Modules\Mahasiswa\Controllers',
    'filter'    => 'role:MAHASISWA',
], function ($routes) {
    $routes->get('/',                           'Dashboard::index');
    $routes->get('pengajuan/aktif',             'Pengajuan::aktif');
    $routes->get('pengajuan/tidak-kuliah',      'Pengajuan::tidakKuliah');
    $routes->get('pengajuan/penelitian',        'Pengajuan::penelitian');
    $routes->get('pengajuan/plp-magang',        'Pengajuan::plpMagang');
    $routes->get('pengajuan/observasi',         'Pengajuan::observasi');
    $routes->get('pengajuan/rekomendasi',       'Pengajuan::rekomendasi');
    $routes->get('pengajuan/lulus',             'Pengajuan::lulus');
    $routes->post('pengajuan/simpan',           'Pengajuan::simpan');
    $routes->post('pengajuan/perbaiki/(:segment)', 'Pengajuan::perbaiki/$1');
    $routes->get('riwayat',                     'Pengajuan::riwayat');
    $routes->get('riwayat/(:segment)',          'Pengajuan::detail/$1');
    $routes->get('notifikasi',                  'Dashboard::notifikasi');
});
