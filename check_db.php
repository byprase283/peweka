<?php
define('BASE_PATH', 'c:/xampp/htdocs/pewekav2/');
require_once BASE_PATH . 'index.php';
$CI =& get_instance();
$CI->load->database();
$fields = $CI->db->list_fields('orders');
echo json_encode($fields);
