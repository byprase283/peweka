-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 07:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peweka_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `name`, `created_at`) VALUES
(1, 'admin', '$2y$10$JCozEzevAFYLTIc300QOoef/xUZebbShJji3AWL8Ofbs5cnAYE9Vm', 'Admin Peweka', '2026-02-15 15:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Kategori Kurta Pria', 'kategori-kurta-pria', '2026-02-17 02:38:32'),
(2, 'Kategori Kurta Anak', 'kategori-kurta-anak', '2026-02-17 02:38:32'),
(3, 'Kategori Tunik Wanita', 'kategori-tunik-wanita', '2026-02-17 02:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'transfer',
  `payment_status` varchar(50) DEFAULT 'pending',
  `snap_token` varchar(255) DEFAULT NULL,
  `order_code` varchar(20) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_address` text NOT NULL,
  `subtotal` decimal(12,0) NOT NULL DEFAULT 0,
  `discount` decimal(12,0) NOT NULL DEFAULT 0,
  `total` decimal(12,0) NOT NULL DEFAULT 0,
  `voucher_code` varchar(50) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','shipped','delivered','rejected') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `province_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `subdistrict_id` int(11) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `subdistrict` varchar(100) DEFAULT NULL,
  `courier` varchar(50) DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `weight` int(11) DEFAULT 1000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `store_id`, `payment_method`, `payment_status`, `snap_token`, `order_code`, `customer_name`, `customer_phone`, `customer_address`, `subtotal`, `discount`, `total`, `voucher_code`, `payment_proof`, `status`, `notes`, `created_at`, `updated_at`, `province_id`, `city_id`, `district_id`, `subdistrict_id`, `province`, `city`, `district`, `subdistrict`, `courier`, `service`, `shipping_cost`, `weight`) VALUES
(1, NULL, 'transfer', 'pending', NULL, 'TEST-1771174561', 'Debug User', '08123456789', 'Jl Test', 100000, 0, 100000, NULL, NULL, 'pending', NULL, '2026-02-15 10:56:01', '2026-02-15 16:56:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 1000),
(2, NULL, 'transfer', 'pending', NULL, 'PWK-B88D4BF5', 'Debug Curl User', '08999999999', 'Jl Debugging 123', 125000, 0, 125000, NULL, '4ed1d514762ffc344e7e469bca91b3f9.png', 'pending', NULL, '2026-02-15 11:01:38', '2026-02-15 17:01:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 1000),
(3, NULL, 'transfer', 'pending', NULL, 'PWK-580A3042', 'Debug Curl User', '08999999999', 'Jl Debugging 123', 285000, 0, 285000, NULL, '91691d264bdbcefc5b6ba7f5c7ac3bd6.png', 'rejected', 'Pembayaran ditolak oleh admin.', '2026-02-15 11:02:44', '2026-02-15 11:04:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 1000),
(4, NULL, 'transfer', 'pending', NULL, 'PWK-EA650653', 'Gita Yulianti', '085603581533', 'gld', 250000, 25000, 225000, 'PEWEKA100', '1945070e4e9b41a82f93fcec841b1934.jpeg', 'shipped', NULL, '2026-02-15 11:05:55', '2026-02-15 11:13:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 1000),
(5, 1, 'midtrans', 'pending', NULL, 'PWK-304CF626', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 125000, 0, 220000, NULL, NULL, 'pending', NULL, '2026-02-17 00:21:55', '2026-02-17 06:21:55', 5, 57, 502, 5430, 'JAWA BARAT', 'SUMEDANG', 'TANJUNGSARI', 'PASIGARAN', 'jne', 'JTR', 95000.00, 1000),
(6, 1, 'midtrans', 'pending', '2f1df992-9b44-414c-bc98-2180647ee2ff', 'PWK-9D6BB388', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 125000, 0, 140000, NULL, NULL, 'pending', NULL, '2026-02-17 00:49:04', '2026-02-17 06:49:04', 9, 115, 2, 101, 'Jawa Barat', 'Purwakarta', 'Purwakarta (Kecamatan)', 'Desa Galudra', 'jne', 'REG', 15000.00, 1000),
(7, 1, 'midtrans', 'pending', '4e1617e5-065a-4e1f-9fe8-8266c90f72bf', 'PWK-28E1B296', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 500000, 0, 507000, NULL, NULL, 'pending', NULL, '2026-02-20 04:12:35', '2026-02-20 10:12:35', 5, 532, 5116, 60293, 'JAWA BARAT', 'PURWAKARTA', 'PONDOKSALAM', 'GALUDRA', 'tiki', 'ECO', 7000.00, 1000),
(8, 1, 'midtrans', 'pending', 'https://pay-sandbox.komerce.my.id/d4061707532563d1d31d87e735b440e3', 'PWK-9363D1B6', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 250000, 0, 259000, NULL, NULL, 'pending', NULL, '2026-02-20 05:04:39', '2026-02-20 11:04:39', 5, 532, 5116, 60293, 'JAWA BARAT', 'PURWAKARTA', 'PONDOKSALAM', 'GALUDRA', 'tiki', 'ONS', 9000.00, 1000),
(9, 1, 'midtrans', 'pending', 'https://pay-sandbox.komerce.my.id/a63a1ba3c873a549afea52832050d3fd', 'PWK-B1A82E1B', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 125000, 0, 132000, NULL, NULL, 'pending', NULL, '2026-02-20 05:10:41', '2026-02-20 11:10:41', 5, 532, 5116, 60293, 'JAWA BARAT', 'PURWAKARTA', 'PONDOKSALAM', 'GALUDRA', 'jnt', 'EZ', 7000.00, 1000),
(10, 1, 'midtrans', 'pending', 'd1f1ce7d-f3db-4b94-b0a5-5cf9b3a18e98', 'PWK-0F597EBA', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 125000, 0, 132000, NULL, NULL, 'pending', NULL, '2026-02-20 05:52:47', '2026-02-20 11:52:47', 5, 532, 5116, 60293, 'JAWA BARAT', 'PURWAKARTA', 'PONDOKSALAM', 'GALUDRA', 'jnt', 'EZ', 7000.00, 1000),
(11, 1, 'midtrans', 'pending', '920f1dbc-0040-4cd3-b683-04b60b17fb5c', 'PWK-8D676085', 'Bayu Prasetio', '085603581533', 'Kp. Galudra Lebak RT 06 RW 03 Desa Galudra, Kecamatan Pondoksalam, Kabupaten Purwakarta, Jawabarat', 250000, 0, 257000, NULL, NULL, 'confirmed', NULL, '2026-02-20 23:41:07', '2026-02-21 00:05:36', 5, 532, 5116, 60293, 'JAWA BARAT', 'PURWAKARTA', 'PONDOKSALAM', 'GALUDRA', 'jnt', 'EZ', 7000.00, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `size` varchar(10) NOT NULL,
  `color` varchar(50) NOT NULL,
  `price` decimal(12,0) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(12,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `size`, `color`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 2, 9, 'Debug Product', 'M', 'Hitam', 100000, 1, 100000),
(2, 3, 2, 9, 'Peweka Streetwear Hoodie', 'M', 'Hitam', 285000, 1, 285000),
(3, 4, 1, 37, 'Peweka Classic Tees', 'XL', 'Merah', 125000, 2, 250000),
(4, 6, 1, 72, 'Peweka Classic Tees', 'S', 'Hitam', 125000, 1, 125000),
(5, 7, 1, 73, 'Peweka Classic Tees', 'M', 'Hitam', 125000, 2, 250000),
(6, 7, 1, 78, 'Peweka Classic Tees', 'XL', 'Merah', 125000, 2, 250000),
(7, 8, 1, 72, 'Peweka Classic Tees', 'S', 'Hitam', 125000, 2, 250000),
(8, 9, 1, 72, 'Peweka Classic Tees', 'S', 'Hitam', 125000, 1, 125000),
(9, 10, 1, 72, 'Peweka Classic Tees', 'S', 'Hitam', 125000, 1, 125000),
(10, 11, 1, 72, 'Peweka Classic Tees', 'S', 'Hitam', 125000, 2, 250000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,0) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT 'default.jpg',
  `category_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Peweka Classic Tees', 'Kaos classic dengan logo Peweka. Bahan cotton combed 30s, nyaman dipakai sehari-hari.', 125000, '9cf8545f72b1a7da523809fd005c7840.jpeg', 2, 1, '2026-02-15 15:58:29', '2026-02-17 03:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`) VALUES
(1, 1, '1c6ed5d6a819c015f1c5e167d1e5280c.jpeg', '2026-02-17 10:39:56'),
(2, 1, 'cc51bace26f0733e655692afee28f9d6.jpeg', '2026-02-17 10:39:56'),
(3, 1, '283b43fcbc9523e76193312ea18e05c5.jpeg', '2026-02-17 10:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL DEFAULT 'M',
  `color` varchar(50) NOT NULL DEFAULT 'Hitam',
  `color_hex` varchar(10) NOT NULL DEFAULT '#1a1a1a',
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `color`, `color_hex`, `stock`, `created_at`) VALUES
(72, 1, 'S', 'Hitam', '#1a1a1a', 8, '2026-02-17 04:40:59'),
(73, 1, 'M', 'Hitam', '#1a1a1a', 18, '2026-02-17 04:40:59'),
(74, 1, 'L', 'Hitam', '#1a1a1a', 20, '2026-02-17 04:40:59'),
(75, 1, 'XL', 'Hitam', '#1a1a1a', 10, '2026-02-17 04:40:59'),
(76, 1, 'M', 'Putih', '#ffffff', 18, '2026-02-17 04:40:59'),
(77, 1, 'L', 'Putih', '#ffffff', 15, '2026-02-17 04:40:59'),
(78, 1, 'XL', 'Merah', '#ff0000', 4, '2026-02-17 04:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) DEFAULT 'Peweka',
  `site_logo` varchar(255) DEFAULT 'logo.png',
  `site_about` text DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `theme_color` varchar(20) DEFAULT '#FFD700',
  `theme_font_heading` varchar(100) DEFAULT 'Outfit',
  `theme_font_body` varchar(100) DEFAULT 'Inter',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_logo`, `site_about`, `instagram_url`, `facebook_url`, `whatsapp_number`, `email`, `phone`, `address`, `theme_color`, `theme_font_heading`, `theme_font_body`, `updated_at`) VALUES
(1, 'Peweka', 'logo.png', 'Peweka hadir dengan koleksi streetwear premium yang menggabungkan budaya lokal dan gaya masa depan. Setiap piece dibuat dengan bahan berkualitas tinggi dan desain yang unik.', 'https://www.instagram.com/peweka.cloth', '', '', '', NULL, '', '#FFD700', 'Outfit', 'Inter', '2026-02-17 14:18:00');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `province_name` varchar(255) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `city_name` varchar(255) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `district_name` varchar(255) DEFAULT NULL,
  `subdistrict_id` int(11) DEFAULT NULL,
  `subdistrict_name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `phone`, `address`, `province_id`, `province_name`, `city_id`, `city_name`, `district_id`, `district_name`, `subdistrict_id`, `subdistrict_name`, `is_default`) VALUES
(1, 'Gudang Induk', '085603581533', 'Pasawahan, Purwakarta', 5, 'JAWA BARAT', 532, 'PURWAKARTA', 5127, 'PASAWAHAN', 60422, 'LEBAK ANYAR', 1),
(2, 'Ciseureuh', '085215956400', 'Desa Ciseureuh, Purwakarta', 5, 'JAWA BARAT', 532, 'PURWAKARTA', 5112, 'PURWAKARTA', 60249, 'CISEUREUH', 0),
(3, 'Cikopak', '081113804594', 'Desa Cikopak, Purwakarta', 5, 'JAWA BARAT', 532, 'PURWAKARTA', 5126, 'BABAKANCIKAO', 60417, 'MULYAMEKAR', 0);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(12,0) DEFAULT NULL,
  `min_order` decimal(12,0) NOT NULL DEFAULT 0,
  `quota` int(11) NOT NULL DEFAULT 100,
  `used` int(11) NOT NULL DEFAULT 0,
  `expired_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `type`, `value`, `max_discount`, `min_order`, `quota`, `used`, `expired_at`, `is_active`, `created_at`) VALUES
(1, 'PEWEKA100', 'percentage', 10.00, 50000, 100000, 100, 1, '2026-12-31', 1, '2026-02-15 15:58:29'),
(2, 'DISKON20K', 'fixed', 20000.00, NULL, 150000, 50, 0, '2026-06-30', 1, '2026-02-15 15:58:29'),
(3, 'WELCOME15', 'percentage', 15.00, 75000, 0, 200, 0, '2026-12-31', 1, '2026-02-15 15:58:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
