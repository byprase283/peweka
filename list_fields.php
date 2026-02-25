<?php
define('ENVIRONMENT', 'development');
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();
$fields = $CI->db->list_fields('orders');
echo "Fields: " . implode(', ', $fields) . "\n";
