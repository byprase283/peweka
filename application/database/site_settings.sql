CREATE TABLE IF NOT EXISTS `site_settings` (
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
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default record if not exists
INSERT INTO `site_settings` (id, site_name) 
SELECT 1, 'Peweka' 
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE id = 1);
