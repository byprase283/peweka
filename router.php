<?php
// Router for PHP Built-in Server
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (file_exists(__DIR__ . $path) && $path !== '/') {
    return false;
}
require_once 'index.php';
