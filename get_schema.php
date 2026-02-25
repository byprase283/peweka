<?php
define('BASEPATH', 'true');
require_once 'application/config/database.php';
$db_info = $db['default'];
$dsn = "mysql:host={$db_info['hostname']};dbname={$db_info['database']};charset={$db_info['char_set']}";
try {
    $pdo = new PDO($dsn, $db_info['username'], $db_info['password']);
    $stmt = $pdo->query('DESCRIBE orders');
    $fields = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $fields[] = $row['Field'];
    }
    echo "Fields: " . implode(', ', $fields) . "\n";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
