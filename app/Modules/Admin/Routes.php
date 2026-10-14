<?php
// app/Modules/Admin/Routes.php

$routes->group('dashboard/admin', [
    'namespace' => 'App\Modules\Admin\Controllers',
    'filter'    => 'role:ADMIN',
], function ($routes) {
    $routes->get('/',                           'Dashboard::index');
    $routes->get('users',                       'ManajemenUser::index');
    $routes->get('users/tambah',               'ManajemenUser::tambah');
    $routes->post('users/simpan',              'ManajemenUser::simpan');
    $routes->get('users/edit/(:segment)',       'ManajemenUser::edit/$1');
    $routes->post('users/update/(:segment)',    'ManajemenUser::update/$1');
    $routes->post('users/hapus/(:segment)',     'ManajemenUser::hapus/$1');
    $routes->get('mitra',                       'TempatMitra::index');
    $routes->post('mitra/simpan',              'TempatMitra::simpan');
    $routes->post('mitra/update/(:segment)',    'TempatMitra::update/$1');
    $routes->post('mitra/hapus/(:segment)',     'TempatMitra::hapus/$1');
    $routes->get('laporan',                    'Laporan::index');
    $routes->get('pengajuan',                  'Dashboard::pengajuan');
});
