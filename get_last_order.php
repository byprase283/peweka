<?php
define('BASEPATH', true);
require 'application/config/database.php';
$db_config = $db['default'];

try {
    $dsn = "mysql:host={$db_config['hostname']};dbname={$db_config['database']}";
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password']);
    $stmt = $pdo->query("SELECT order_code FROM orders ORDER BY id DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row['order_code'] ?? 'No orders found';
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
