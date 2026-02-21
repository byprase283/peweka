<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_settings()
    {
        // Auto-create table if not exists to avoid errors
        if (!$this->db->table_exists('site_settings')) {
            $this->db->query("CREATE TABLE `site_settings` (
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `site_name` VARCHAR(255) DEFAULT 'Peweka',
                `site_logo` VARCHAR(255) DEFAULT 'logo.png',
                `site_about` TEXT,
                `instagram_url` VARCHAR(255),
                `facebook_url` VARCHAR(255),
                `whatsapp_number` VARCHAR(20),
                `email` VARCHAR(255),
                `phone` VARCHAR(20),
                `address` TEXT,
                `theme_color` VARCHAR(20) DEFAULT '#FFD700',
                `theme_font_heading` VARCHAR(100) DEFAULT 'Outfit',
                `theme_font_body` VARCHAR(100) DEFAULT 'Inter',
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
            $this->db->insert('site_settings', ['id' => 1, 'site_name' => 'Peweka']);
        }

        // Also check if new columns exist (for migration)
        if (!$this->db->field_exists('theme_color', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `theme_color` VARCHAR(20) DEFAULT '#FFD700' AFTER `address` ");
        }
        if (!$this->db->field_exists('theme_font_heading', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `theme_font_heading` VARCHAR(100) DEFAULT 'Outfit' AFTER `theme_color` ");
        }
        if (!$this->db->field_exists('theme_font_body', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `theme_font_body` VARCHAR(100) DEFAULT 'Inter' AFTER `theme_font_heading` ");
        }

        $query = $this->db->get_where('site_settings', ['id' => 1]);
        return $query->row();
    }

    public function update_settings($data)
    {
        return $this->db->update('site_settings', $data, ['id' => 1]);
    }
}
