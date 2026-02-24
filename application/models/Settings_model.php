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
                `site_favicon` VARCHAR(255) DEFAULT 'favicon.png',
                `site_description` TEXT,
                `theme_preset` VARCHAR(50) DEFAULT 'peweka-gold',
                `theme_bg_color` VARCHAR(20) DEFAULT '#0a0a0a',
                `theme_text_color` VARCHAR(20) DEFAULT '#ffffff',
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
        if (!$this->db->field_exists('site_favicon', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `site_favicon` VARCHAR(255) DEFAULT 'favicon.png' AFTER `theme_font_body` ");
        }
        if (!$this->db->field_exists('site_description', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `site_description` TEXT AFTER `site_favicon` ");
        }
        if (!$this->db->field_exists('theme_preset', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `theme_preset` VARCHAR(50) DEFAULT 'peweka-gold' AFTER `site_description` ");
        }
        if (!$this->db->field_exists('theme_bg_color', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `theme_bg_color` VARCHAR(20) DEFAULT '#0a0a0a' AFTER `theme_preset` ");
        }
        if (!$this->db->field_exists('theme_text_color', 'site_settings')) {
            $this->db->query("ALTER TABLE `site_settings` ADD `theme_text_color` VARCHAR(20) DEFAULT '#ffffff' AFTER `theme_bg_color` ");
        }

        $query = $this->db->get_where('site_settings', ['id' => 1]);
        return $query->row();
    }

    public function update_settings($data)
    {
        return $this->db->update('site_settings', $data, ['id' => 1]);
    }
}
