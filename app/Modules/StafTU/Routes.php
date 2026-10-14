<?php
// app/Modules/StafTU/Routes.php

$routes->group('dashboard/staf-tu', [
    'namespace' => 'App\Modules\StafTU\Controllers',
    'filter'    => 'role:STAF_TU_PRODI',
], function ($routes) {
    $routes->get('/',                       'Dashboard::index');
    $routes->get('inbox',                   'Inbox::index');
    $routes->get('detail/(:segment)',       'Inbox::detail/$1');
    $routes->post('approve/(:segment)',     'Inbox::approve/$1');
    $routes->post('tolak/(:segment)',       'Inbox::tolak/$1');
    $routes->post('revisi/(:segment)',      'Inbox::revisi/$1');
    $routes->get('diproses',               'Inbox::diproses');
    $routes->get('ditolak',                'Inbox::ditolak');
    $routes->get('semua',                  'Inbox::semua');
    $routes->get('mitra',                  'Inbox::mitra');
});
