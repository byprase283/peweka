<?php
define('BASEPATH', true);
require 'application/config/database.php';
$conn = mysqli_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if (!$conn)
    die('Connect Error');
$code = 'PWK-4429E604';
$result = mysqli_query($conn, "SELECT courier, tracking_number FROM orders WHERE order_code = '$code'");
$row = mysqli_fetch_assoc($result);
echo json_encode($row);
mysqli_close($conn);
