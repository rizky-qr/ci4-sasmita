<?php
// app/Modules/Verifikasi/Routes.php
// Public route untuk verifikasi QR Code dan cetak surat resmi (tanpa filter auth)

$routes->get('verify/(:segment)', '\App\Modules\Verifikasi\Controllers\Verifikasi::index/$1');
$routes->get('surat/cetak/(:segment)', '\App\Modules\Verifikasi\Controllers\Verifikasi::cetak/$1');
