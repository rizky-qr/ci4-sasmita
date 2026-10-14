<?php
// app/Modules/Notifikasi/Routes.php

$routes->group('notifikasi', [
    'namespace' => 'App\Modules\Notifikasi\Controllers',
    'filter'    => 'auth',
], function ($routes) {
    $routes->get('/',                           'Notifikasi::index');
    $routes->post('read/(:segment)',            'Notifikasi::markRead/$1');
    $routes->post('read-all',                  'Notifikasi::markAllRead');
    $routes->get('count',                       'Notifikasi::count');  // AJAX endpoint
});
