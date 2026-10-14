<?php
// app/Modules/Auth/Routes.php
// Routes untuk modul Auth — tidak butuh filter auth (halaman publik)

$routes->group('auth', ['namespace' => 'App\Modules\Auth\Controllers'], function ($routes) {
    $routes->get('login',  'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
});
