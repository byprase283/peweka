-- ==============================================
-- Peweka - Database Schema
-- ==============================================

CREATE DATABASE IF NOT EXISTS `peweka_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `peweka_db`;

-- Admin table
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `image` VARCHAR(255) DEFAULT 'default.jpg',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Product Variants (size, color, stock)
CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `size` VARCHAR(10) NOT NULL DEFAULT 'M',
    `color` VARCHAR(50) NOT NULL DEFAULT 'Hitam',
    `color_hex` VARCHAR(10) NOT NULL DEFAULT '#1a1a1a',
    `stock` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Vouchers table
CREATE TABLE IF NOT EXISTS `vouchers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    `value` DECIMAL(12,2) NOT NULL DEFAULT 0,
    `max_discount` DECIMAL(12,0) DEFAULT NULL,
    `min_order` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `quota` INT NOT NULL DEFAULT 100,
    `used` INT NOT NULL DEFAULT 0,
    `expired_at` DATE DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Orders table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_code` VARCHAR(20) NOT NULL UNIQUE,
    `customer_name` VARCHAR(255) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `customer_address` TEXT NOT NULL,
    `subtotal` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `discount` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `total` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `voucher_code` VARCHAR(50) DEFAULT NULL,
    `payment_proof` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('pending','confirmed','shipped','delivered','rejected') NOT NULL DEFAULT 'pending',
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Order Items table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `variant_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `size` VARCHAR(10) NOT NULL,
    `color` VARCHAR(50) NOT NULL,
    `price` DECIMAL(12,0) NOT NULL DEFAULT 0,
    `quantity` INT NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(12,0) NOT NULL DEFAULT 0,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==============================================
-- Default Data
-- ==============================================

-- Default Admin (password: admin123)
INSERT INTO `admin` (`username`, `password`, `name`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Peweka');

-- Sample Products
INSERT INTO `products` (`name`, `description`, `price`, `image`) VALUES
('Peweka Classic Tee', 'Kaos classic dengan logo Peweka. Bahan cotton combed 30s, nyaman dipakai sehari-hari.', 125000, 'classic-tee.jpg'),
('Peweka Streetwear Hoodie', 'Hoodie streetwear premium dengan desain eksklusif Peweka. Bahan fleece tebal, cocok untuk gaya casual.', 285000, 'streetwear-hoodie.jpg'),
('Peweka Culture Oversize', 'Kaos oversize bertema budaya. Bahan cotton combed 24s premium, print DTF berkualitas tinggi.', 165000, 'culture-oversize.jpg'),
('Peweka Future Jacket', 'Jaket windbreaker dengan sentuhan futuristik. Bahan parasut premium, water resistant.', 350000, 'future-jacket.jpg'),
('Peweka Urban Cap', 'Topi baseball dengan bordir logo Peweka. Bahan twill premium, adjustable strap.', 89000, 'urban-cap.jpg'),
('Peweka Graphic Tee Black', 'Kaos hitam dengan graphic print eksklusif. Bahan cotton combed 30s, limited edition.', 145000, 'graphic-tee-black.jpg');

-- Sample Variants
INSERT INTO `product_variants` (`product_id`, `size`, `color`, `color_hex`, `stock`) VALUES
-- Classic Tee
(1, 'S', 'Hitam', '#1a1a1a', 15), (1, 'M', 'Hitam', '#1a1a1a', 20), (1, 'L', 'Hitam', '#1a1a1a', 20),
(1, 'XL', 'Hitam', '#1a1a1a', 10), (1, 'S', 'Putih', '#FFFFFF', 12), (1, 'M', 'Putih', '#FFFFFF', 18),
(1, 'L', 'Putih', '#FFFFFF', 15), (1, 'XL', 'Putih', '#FFFFFF', 8),
-- Streetwear Hoodie
(2, 'M', 'Hitam', '#1a1a1a', 10), (2, 'L', 'Hitam', '#1a1a1a', 12), (2, 'XL', 'Hitam', '#1a1a1a', 8),
(2, 'M', 'Abu-abu', '#6B7280', 10), (2, 'L', 'Abu-abu', '#6B7280', 10),
-- Culture Oversize
(3, 'M', 'Hitam', '#1a1a1a', 15), (3, 'L', 'Hitam', '#1a1a1a', 15), (3, 'XL', 'Hitam', '#1a1a1a', 10),
(3, 'M', 'Cream', '#F5F0E1', 12), (3, 'L', 'Cream', '#F5F0E1', 12),
-- Future Jacket
(4, 'M', 'Hitam', '#1a1a1a', 8), (4, 'L', 'Hitam', '#1a1a1a', 8), (4, 'XL', 'Hitam', '#1a1a1a', 5),
-- Urban Cap
(5, 'ALL', 'Hitam', '#1a1a1a', 25), (5, 'ALL', 'Putih', '#FFFFFF', 15), (5, 'ALL', 'Kuning', '#FFD700', 10),
-- Graphic Tee
(6, 'S', 'Hitam', '#1a1a1a', 10), (6, 'M', 'Hitam', '#1a1a1a', 15), (6, 'L', 'Hitam', '#1a1a1a', 15),
(6, 'XL', 'Hitam', '#1a1a1a', 8), (6, 'XXL', 'Hitam', '#1a1a1a', 5);

-- Sample Vouchers
INSERT INTO `vouchers` (`code`, `type`, `value`, `max_discount`, `min_order`, `quota`, `expired_at`) VALUES
('PEWEKA10', 'percentage', 10, 50000, 100000, 100, '2026-12-31'),
('DISKON20K', 'fixed', 20000, NULL, 150000, 50, '2026-06-30'),
('WELCOME15', 'percentage', 15, 75000, 0, 200, '2026-12-31');
