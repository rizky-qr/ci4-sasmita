<?php
// app/Modules/Kaprodi/Routes.php

$routes->group('dashboard/kaprodi', [
    'namespace' => 'App\Modules\Kaprodi\Controllers',
    'filter'    => 'role:KAPRODI',
], function ($routes) {
    $routes->get('/',                       'Dashboard::index');
    $routes->get('inbox',                   'Inbox::index');
    $routes->get('detail/(:segment)',       'Inbox::detail/$1');
    $routes->post('approve/(:segment)',     'Inbox::approve/$1');
    $routes->post('tolak/(:segment)',       'Inbox::tolak/$1');
    $routes->post('revisi/(:segment)',      'Inbox::revisi/$1');
    $routes->get('riwayat',                'Inbox::riwayat');
    $routes->get('statistik',              'Statistik::index');
});
