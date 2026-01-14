<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Custom Routes
$route['login'] = 'auth';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard';
$route['barang'] = 'barang';
$route['barang/(:num)'] = 'barang/detail/$1';
$route['barang/add'] = 'barang/add';
$route['barang/edit/(:num)'] = 'barang/edit/$1';
$route['barang/delete/(:num)'] = 'barang/delete/$1';
$route['gudang'] = 'gudang';
$route['gudang/(:num)'] = 'gudang/detail/$1';
$route['gudang/(:num)/stok'] = 'gudang/stok/$1';
$route['transaksi/masuk'] = 'transaksi/masuk';
$route['transaksi/keluar'] = 'transaksi/keluar';
$route['transaksi/transfer'] = 'transaksi/transfer';
$route['transaksi'] = 'transaksi';
$route['laporan'] = 'laporan';
$route['user'] = 'user';
$route['user/add'] = 'user/add';
$route['user/edit/(:num)'] = 'user/edit/$1';
$route['pengaturan'] = 'pengaturan';

// Pengiriman Routes
$route['pengiriman/antar_gudang'] = 'pengiriman/antar_gudang';
$route['pengiriman/penggunaan'] = 'pengiriman/penggunaan';
$route['pengiriman/add_antar_gudang'] = 'pengiriman/add_antar_gudang';
$route['pengiriman/add_penggunaan'] = 'pengiriman/add_penggunaan';
$route['pengiriman/create_antar_gudang'] = 'pengiriman/create_antar_gudang';
$route['pengiriman/create_penggunaan'] = 'pengiriman/create_penggunaan';
$route['pengiriman/detail/(:num)'] = 'pengiriman/detail/$1';
$route['pengiriman/delete/(:num)'] = 'pengiriman/delete/$1';

$route['pengiriman/cetak/(:num)'] = 'pengiriman/cetak/$1';
$route['pengiriman/cetak_pdf/(:num)'] = 'pengiriman/cetak_pdf/$1';

$route['penerimaan/cetak/(:num)'] = 'penerimaan/cetak/$1';
$route['penerimaan/cetak_pdf/(:num)'] = 'penerimaan/cetak_pdf/$1';