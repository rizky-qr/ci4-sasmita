<?php
// app/Modules/Dekan/Routes.php

$routes->group('dashboard/dekan', [
    'namespace' => 'App\Modules\Dekan\Controllers',
    'filter'    => 'role:DEKAN',
], function ($routes) {
    $routes->get('/',                       'Dashboard::index');
    $routes->get('inbox',                   'Inbox::index');
    $routes->get('detail/(:segment)',       'Inbox::detail/$1');
    $routes->post('setujui/(:segment)',     'Inbox::setujui/$1');
    $routes->post('tolak/(:segment)',       'Inbox::tolak/$1');
    $routes->get('selesai',                'Inbox::selesai');
    $routes->get('laporan',                'Laporan::index');
});
