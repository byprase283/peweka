<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate_shipping extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (php_sapi_name() !== 'cli') {
            // die("CLI only");
        }
        $this->load->database();
    }

    public function index()
    {
        $columns = [
            "province_id INT DEFAULT NULL",
            "city_id INT DEFAULT NULL",
            "province VARCHAR(100) DEFAULT NULL",
            "city VARCHAR(100) DEFAULT NULL",
            "courier VARCHAR(50) DEFAULT NULL",
            "service VARCHAR(100) DEFAULT NULL",
            "shipping_cost DECIMAL(10,2) DEFAULT 0",
            "weight INT DEFAULT 1000"
        ];

        foreach ($columns as $col) {
            $parts = explode(' ', $col);
            $name = $parts[0];
            if (!$this->db->field_exists($name, 'orders')) {
                $this->db->query("ALTER TABLE `orders` ADD $col");
                echo "Added column: $name\n";
            } else {
                echo "Column already exists: $name\n";
            }
        }
        echo "Migration complete.";
    }
}
?>