<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Rajaongkir API Configuration (Komerce)
|--------------------------------------------------------------------------
|
| api_key      : Your Komerce API Key
| use_mock     : set to true to use mock_data instead of real API calls
|
*/
// $config['rajaongkir_api_key'] = 'xHbSamtYd0015fecb38950e4iLbj2gZO';
$config['rajaongkir_api_key'] = 'VFHeFWlcd82989b508f6c6bbd6nu5etI';
$config['rajaongkir_payment_api_key'] = 'JCExVWwEd82989b508f6c6bbx4PRHJrM';
$config['rajaongkir_use_mock'] = false;
$config['rajaongkir_payment_sandbox'] = true; // Use true for testing

/*
|--------------------------------------------------------------------------
| Mock Data
|--------------------------------------------------------------------------
*/
$config['rajaongkir_mock_provinces'] = [
    ["id" => "9", "name" => "Jawa Barat"],
    ["id" => "10", "name" => "Jawa Tengah"],
    ["id" => "11", "name" => "Jawa Timur"],
    ["id" => "6", "name" => "DKI Jakarta"]
];

$config['rajaongkir_mock_cities'] = [
    "9" => [ // Jawa Barat
        ["id" => "115", "name" => "Purwakarta"],
        ["id" => "22", "name" => "Bandung"],
        ["id" => "23", "name" => "Bekasi"]
    ]
];

$config['rajaongkir_mock_districts'] = [
    "115" => [ // Purwakarta
        ["id" => "1", "name" => "Babakancikao"],
        ["id" => "2", "name" => "Purwakarta (Kecamatan)"],
        ["id" => "3", "name" => "Ciseureuh"]
    ]
];

$config['rajaongkir_mock_subdistricts'] = [
    ["id" => "101", "name" => "Desa Galudra"],
    ["id" => "102", "name" => "Desa Ciseureuh"],
    ["id" => "103", "name" => "Desa Cikopak"]
];
