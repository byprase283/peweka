<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Public Routes
$route['produk'] = 'Shop/index';
$route['produk/(:num)'] = 'Product/detail/$1';
$route['checkout'] = 'Order/checkout';
$route['order/store'] = 'Order/store';
$route['order/success/(:any)'] = 'Order/success/$1';
$route['order/track/(:any)'] = 'Order/track/$1';
$route['order/get_cities/(:num)'] = 'Order/get_cities/$1';
$route['order/get_districts/(:num)'] = 'Order/get_districts/$1';
$route['order/get_subdistricts/(:num)'] = 'Order/get_subdistricts/$1';
$route['order/get_shipping_cost'] = 'Order/get_shipping_cost';
$route['order/apply-voucher'] = 'Order/apply_voucher';
$route['order/notification'] = 'Order/notification';

// Admin Auth
$route['admin/login'] = 'Auth/login';
$route['admin/authenticate'] = 'Auth/authenticate';
$route['admin/logout'] = 'Auth/logout';

// Admin Dashboard
$route['admin'] = 'Admin/index';
$route['admin/orders'] = 'Admin/orders';
$route['admin/orders/(:any)'] = 'Admin/orders/$1';
$route['admin/order/(:num)'] = 'Admin/order_detail/$1';
$route['admin/order/confirm/(:num)'] = 'Admin/confirm/$1';
$route['admin/order/reject/(:num)'] = 'Admin/reject/$1';
$route['admin/order/ship/(:num)'] = 'Admin/ship/$1';
$route['admin/order/deliver/(:num)'] = 'Admin/deliver/$1';
$route['admin/order/update-shipping/(:num)'] = 'Admin/update_shipping/$1';
$route['admin/order/get-shipping-estimate/(:num)'] = 'Admin/get_shipping_estimate/$1';
$route['admin/order/send-wa/(:num)'] = 'Admin/send_wa/$1';

// Admin Products
$route['admin/products'] = 'Admin/products';
$route['admin/product/create'] = 'Admin/product_create';
$route['admin/product/store'] = 'Admin/product_store';
$route['admin/product/edit/(:num)'] = 'Admin/product_edit/$1';
$route['admin/product/update/(:num)'] = 'Admin/product_update/$1';
$route['admin/product/delete/(:num)'] = 'Admin/product_delete/$1';

// Admin Vouchers
$route['admin/vouchers'] = 'Admin/vouchers';
$route['admin/voucher/create'] = 'Admin/voucher_create';
$route['admin/voucher/store'] = 'Admin/voucher_store';
$route['admin/voucher/edit/(:num)'] = 'Admin/voucher_edit/$1';
$route['admin/voucher/update/(:num)'] = 'Admin/voucher_update/$1';
$route['admin/voucher/delete/(:num)'] = 'Admin/voucher_delete/$1';

// Admin Categories
$route['admin/categories'] = 'Admin/categories';
$route['admin/category/create'] = 'Admin/category_create';
$route['admin/category/store'] = 'Admin/category_store';
$route['admin/category/edit/(:num)'] = 'Admin/category_edit/$1';
$route['admin/category/update/(:num)'] = 'Admin/category_update/$1';
$route['admin/category/delete/(:num)'] = 'Admin/category_delete/$1';

// Admin Stores
$route['admin/stores'] = 'Admin/stores';
$route['admin/store/edit/(:num)'] = 'Admin/store_edit/$1';
$route['admin/store/update/(:num)'] = 'Admin/store_update/$1';
