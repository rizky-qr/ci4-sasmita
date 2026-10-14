<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── Default CI4 route (fallback ke Auth jika belum login) ──────────────────
$routes->get('/', '\App\Modules\Auth\Controllers\Auth::index');
$routes->get('login', '\App\Modules\Auth\Controllers\Auth::index');
$routes->post('login', '\App\Modules\Auth\Controllers\Auth::login');
$routes->get('logout', '\App\Modules\Auth\Controllers\Auth::logout');
$routes->get('register', '\App\Modules\Auth\Controllers\Auth::register');
$routes->post('register', '\App\Modules\Auth\Controllers\Auth::processRegister');

// ─── Auto-load semua Routes.php dari setiap modul ───────────────────────────
foreach (glob(APPPATH . 'Modules/*/Routes.php') as $moduleRoute) {
    require_once $moduleRoute;
}
