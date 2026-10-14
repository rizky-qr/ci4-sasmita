<?php
// app/Modules/User/Routes.php
// Routes untuk modul User — dilindungi filter auth

$routes->group('user', [
    'namespace' => 'App\Modules\User\Controllers',
    'filter'    => 'auth',
], function ($routes) {
    $routes->get('profile',              'User::profile');
    $routes->post('update/(:segment)',   'User::update/$1');
});
