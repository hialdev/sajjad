-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 02, 2025 at 09:02 PM
-- Server version: 10.11.14-MariaDB
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sajjadza_osano`
--

-- --------------------------------------------------------

--
-- Table structure for table `bals`
--

CREATE TABLE `bals` (
  `id` char(36) NOT NULL,
  `nowin_type` enum('warehouse','store') NOT NULL,
  `nowin_id` char(36) DEFAULT NULL,
  `purchase_receive_id` char(36) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_unpack` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bal_products`
--

CREATE TABLE `bal_products` (
  `id` char(36) NOT NULL,
  `bal_id` char(36) NOT NULL,
  `product_id` char(36) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bal_unpacks`
--

CREATE TABLE `bal_unpacks` (
  `id` char(36) NOT NULL,
  `bal_id` char(36) DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bal_unpack_products`
--

CREATE TABLE `bal_unpack_products` (
  `id` char(36) NOT NULL,
  `product_id` char(36) NOT NULL,
  `bal_unpack_id` char(36) NOT NULL,
  `qty` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `npwp` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `image`, `name`, `npwp`, `email`, `phone`, `fax`, `description`, `address`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
('3332b6e2-02d1-46bf-94a8-2821bf2de051', NULL, 'Umum', NULL, NULL, NULL, NULL, NULL, 'Bandung', 'BANDUNG', 573930, '2025-09-21 06:49:28', '2025-09-21 06:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `client_addresses`
--

CREATE TABLE `client_addresses` (
  `id` char(36) NOT NULL,
  `client_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_addresses`
--

INSERT INTO `client_addresses` (`id`, `client_id`, `name`, `address`, `city`, `postal_code`, `description`, `created_at`, `updated_at`) VALUES
('88af1e16-f492-4b69-b477-e9ad65ea34f7', '3332b6e2-02d1-46bf-94a8-2821bf2de051', 'utama', 'Jl buntu', 'JAKARTA BARAT', 172632, NULL, '2025-10-01 06:50:59', '2025-10-01 06:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `client_pics`
--

CREATE TABLE `client_pics` (
  `id` char(36) NOT NULL,
  `client_id` char(36) NOT NULL,
  `parent_pic_id` char(36) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_pics`
--

INSERT INTO `client_pics` (`id`, `client_id`, `parent_pic_id`, `image`, `name`, `email`, `phone`, `description`, `created_at`, `updated_at`) VALUES
('0ee55835-7064-4066-b14e-aa7fa7cf668c', '3332b6e2-02d1-46bf-94a8-2821bf2de051', NULL, 'clients/pics/FpDVHKHka2sKo5miHsCgTAjZwQIGAH6W4MtS4Tsh.jpg', 'Pak asdasd', 'email@mail.com', '12312421321', NULL, '2025-10-01 06:51:29', '2025-10-01 06:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `description`, `address`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
('45c0f8bd-9f34-43a8-917b-f340f9b22eff', 'ALIve', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 07:05:01', '2025-10-01 07:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` char(36) NOT NULL,
  `date` date NOT NULL,
  `code` varchar(255) NOT NULL,
  `customer_id` char(36) NOT NULL,
  `payment_id` char(36) DEFAULT NULL,
  `user_id` char(36) NOT NULL,
  `description` text DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `total_price_taxed` decimal(15,2) DEFAULT NULL,
  `payment_receipt` text DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `date`, `code`, `customer_id`, `payment_id`, `user_id`, `description`, `total_price`, `tax`, `total_price_taxed`, `payment_receipt`, `is_finished`, `created_at`, `updated_at`) VALUES
('a75e3c80-733f-434e-abed-0a404c7c32ef', '2025-10-01', 'ORDER/001/SJZ/X/2025', '45c0f8bd-9f34-43a8-917b-f340f9b22eff', 'b7cbd247-ef9d-4cba-8f5e-19e5876b5d04', '4', 'asdasdqwe', 1050000.00, 11, 1165500.00, 'payments/FS9LF6couc3C92rHL6zH8z6j8Q1rFuL5IkP2V8Jr.jpg', 1, '2025-10-01 07:05:45', '2025-10-01 07:11:42');

-- --------------------------------------------------------

--
-- Table structure for table `customer_order_products`
--

CREATE TABLE `customer_order_products` (
  `id` char(36) NOT NULL,
  `customer_order_id` char(36) NOT NULL,
  `product_id` char(36) DEFAULT NULL,
  `store_id` char(36) DEFAULT NULL,
  `partner_id` char(36) DEFAULT NULL,
  `is_from_partner` tinyint(1) NOT NULL DEFAULT 0,
  `qty` bigint(20) DEFAULT NULL,
  `length` decimal(12,2) DEFAULT NULL,
  `price_sale` decimal(15,2) NOT NULL,
  `price_buy` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_order_products`
--

INSERT INTO `customer_order_products` (`id`, `customer_order_id`, `product_id`, `store_id`, `partner_id`, `is_from_partner`, `qty`, `length`, `price_sale`, `price_buy`, `description`, `created_at`, `updated_at`) VALUES
('3169fb15-8f32-4c96-bff7-80774d138a4f', 'a75e3c80-733f-434e-abed-0a404c7c32ef', 'f255388b-b6f8-4d1d-ad49-080bb806ac09', NULL, '3d207ceb-6f24-48ab-9ca3-1313a25edf8f', 1, 3, NULL, 250000.00, 200000.00, NULL, '2025-10-01 07:10:26', '2025-10-01 07:10:26'),
('b668eea1-e34f-421e-b490-e81517c15995', 'a75e3c80-733f-434e-abed-0a404c7c32ef', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', '8cebd496-7492-4691-9f0e-ba3baab17e21', NULL, 0, 2, NULL, 150000.00, 0.00, 'mantap', '2025-10-01 07:10:26', '2025-10-01 07:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `logistics`
--

CREATE TABLE `logistics` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `npwp` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `cp_name` varchar(255) DEFAULT NULL,
  `cp_email` varchar(255) DEFAULT NULL,
  `cp_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logistic_addresses`
--

CREATE TABLE `logistic_addresses` (
  `id` char(36) NOT NULL,
  `logistic_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packs`
--

CREATE TABLE `packs` (
  `id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `capacity` bigint(20) DEFAULT NULL,
  `unit_id` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `npwp` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `image`, `name`, `npwp`, `email`, `phone`, `fax`, `description`, `address`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
('3d207ceb-6f24-48ab-9ca3-1313a25edf8f', 'partners/DG8GAQgNNJQzuO1qlfW8MRrj8b4U3fzCwsOYjV8E.jpg', 'PT Toko Ayah', NULL, NULL, '-3', NULL, NULL, 'Jl Pesantren', 'TANGERANG', 141232, '2025-10-01 07:07:36', '2025-10-01 07:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `partner_addresses`
--

CREATE TABLE `partner_addresses` (
  `id` char(36) NOT NULL,
  `partner_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_addresses`
--

INSERT INTO `partner_addresses` (`id`, `partner_id`, `name`, `address`, `city`, `postal_code`, `description`, `created_at`, `updated_at`) VALUES
('5aea997e-8f3c-462b-b0ad-ad83d2fc0f85', '3d207ceb-6f24-48ab-9ca3-1313a25edf8f', 'utama', 'Jl Tanah Adik', 'JAKARTA PUSAT', 123798, NULL, '2025-10-01 07:08:01', '2025-10-01 07:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `partner_debts`
--

CREATE TABLE `partner_debts` (
  `id` char(36) NOT NULL,
  `customer_order_id` char(36) DEFAULT NULL,
  `customer_order_product_id` char(36) DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `image` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_debt` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_debts`
--

INSERT INTO `partner_debts` (`id`, `customer_order_id`, `customer_order_product_id`, `is_paid`, `image`, `description`, `total_debt`, `created_at`, `updated_at`) VALUES
('928998e8-6a14-4830-8abe-e13aa6c52ccd', 'a75e3c80-733f-434e-abed-0a404c7c32ef', '3169fb15-8f32-4c96-bff7-80774d138a4f', 1, 'partner_debts/N5JlUfhi02ZXeE8F4365h3tOb0nLESNWA0CRjdmn.jpg', NULL, 600000.00, '2025-10-01 07:11:42', '2025-10-01 07:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `partner_pics`
--

CREATE TABLE `partner_pics` (
  `id` char(36) NOT NULL,
  `partner_id` char(36) NOT NULL,
  `parent_pic_id` char(36) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_pics`
--

INSERT INTO `partner_pics` (`id`, `partner_id`, `parent_pic_id`, `image`, `name`, `email`, `phone`, `description`, `created_at`, `updated_at`) VALUES
('b8d9aaba-17bf-4d46-812f-1dcc0a16f38c', '3d207ceb-6f24-48ab-9ca3-1313a25edf8f', NULL, 'partners/pics/k1uK6dLgCJHjIRXZDDhxsCpb1sBu8x9BFbGRBxXe.jpg', 'Ayah', NULL, NULL, NULL, '2025-10-01 07:08:20', '2025-10-01 07:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `bank_name` text DEFAULT NULL,
  `account_name` text DEFAULT NULL,
  `account_number` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `type` enum('bank','qris') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `image`, `bank_name`, `account_name`, `account_number`, `description`, `is_active`, `type`, `created_at`, `updated_at`) VALUES
('b7cbd247-ef9d-4cba-8f5e-19e5876b5d04', 'bals/QfnQf5uEbEdWEJrPC6CGw5pgAPXNnEMI5jKX3j7J.webp', 'Bank Central Asia (BCA)', 'Arief Budiman', '123456789182', NULL, 1, 'bank', '2025-10-01 07:11:10', '2025-10-01 07:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `principals`
--

CREATE TABLE `principals` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `npwp` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `principals`
--

INSERT INTO `principals` (`id`, `image`, `name`, `npwp`, `email`, `phone`, `fax`, `description`, `address`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
('39a82dbf-00aa-4110-b7ca-278c75a446b4', NULL, 'CV CITRA ABADI', NULL, NULL, NULL, NULL, NULL, 'JAKARTA PUSAT', 'JAKARTA UTARA', 10240, '2025-07-21 17:23:57', '2025-07-21 17:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `principal_addresses`
--

CREATE TABLE `principal_addresses` (
  `id` char(36) NOT NULL,
  `principal_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `principal_pics`
--

CREATE TABLE `principal_pics` (
  `id` char(36) NOT NULL,
  `principal_id` char(36) NOT NULL,
  `parent_pic_id` char(36) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `principal_pics`
--

INSERT INTO `principal_pics` (`id`, `principal_id`, `parent_pic_id`, `image`, `name`, `email`, `phone`, `description`, `created_at`, `updated_at`) VALUES
('08551f9e-6084-4ba8-9aed-6da666da893b', '39a82dbf-00aa-4110-b7ca-278c75a446b4', NULL, NULL, 'ARIEF BUDIMAN', NULL, NULL, NULL, '2025-07-21 17:28:39', '2025-07-21 17:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `height` bigint(20) DEFAULT NULL,
  `width` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit_id` char(36) NOT NULL,
  `price_per_unit` decimal(15,2) DEFAULT NULL,
  `product_type_id` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `image`, `code`, `name`, `slug`, `height`, `width`, `description`, `unit_id`, `price_per_unit`, `product_type_id`, `created_at`, `updated_at`) VALUES
('0c06fc3b-c78b-491f-87ed-74ea5201876d', NULL, 'GZM/007/SJZ/VII/2025', 'GIZEM 2026 GREEN', 'gizem-2026-green', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:42:39', '2025-07-22 07:50:39'),
('1433c1ba-ab51-46ba-8eb3-c460934ba87d', NULL, 'ALM115/002/SJZ/VII/2025', 'AL MAYA 115X155', 'al-maya-115x155', 115, 155, NULL, 'dc02889e-600b-4103-b5a0-949e30e6eee9', 150000.00, '6b7acffe-0e44-42e1-ae77-6aa1180021a3', '2025-07-21 17:11:58', '2025-07-21 17:11:58'),
('3409e1b4-edc0-4ee4-9e75-68697a9dbe76', NULL, 'AQD/018/SJZ/VII/2025', 'AL QUDS 2046 NAVY', 'al-quds-2046-navy', NULL, NULL, 'MEREK : AL QUDS\r\nBAHAN : BCF POLYPROPYLENE\r\nKERAPATAN : 420 POINT\r\nKETEBALAN : 12 MM', '4877726d-6547-4114-8811-7783f8cccd3c', 250000.00, 'ac2bb624-b8e4-4170-a73b-38940894132d', '2025-07-23 15:04:52', '2025-07-23 15:04:52'),
('42eb9ce5-82c3-4673-8c35-2ac52db6b37b', NULL, 'GZM/012/SJZ/VII/2025', 'GIZEM1918 NEVY', 'gizem1918-nevy', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:47:28', '2025-07-22 07:47:28'),
('478d68c8-1a7f-4afa-929e-a3d25ec0306c', NULL, 'GZM/009/SJZ/VII/2025', 'GIZEM 2037 c', 'gizem-2037-c', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:44:20', '2025-07-22 07:44:53'),
('601c2618-1612-4f84-bec1-296c109cd32e', NULL, 'AQD/016/SJZ/VII/2025', 'AL QUDS 2046GREEN', 'al-quds-2046green', 120, 3000, 'MEREK : BCF PROPOLYENE\r\nBAHAN : 420 POINT\r\nKETEBALAN : 12 MM', '4877726d-6547-4114-8811-7783f8cccd3c', 250000.00, 'ac2bb624-b8e4-4170-a73b-38940894132d', '2025-07-22 08:18:39', '2025-07-22 08:18:39'),
('61d4bf2f-3063-49ce-90f7-52f9c1ae3df0', NULL, 'GZM/005/SJZ/VII/2025', 'GIZEM 1453 RED', 'gizem-1453-red', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:37:05', '2025-07-22 07:54:47'),
('64caa2ba-2c89-4d67-be4d-53f3cf1908d0', NULL, 'ALM210/001/SJZ/VII/2025', 'ALMAYA210X310 002', 'almaya210x310-002', 210, 310, NULL, 'dc02889e-600b-4103-b5a0-949e30e6eee9', 450000.00, '5d2b1d42-4e42-4273-a57a-a67ecfd6dae1', '2025-07-21 14:09:56', '2025-07-21 23:59:01'),
('731b8de6-e133-46b3-ab14-265f620a4b23', NULL, 'GZM/011/SJZ/VII/2025', 'GIZEM 1918 RED', 'gizem-1918-red', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:46:40', '2025-07-22 07:46:40'),
('8c3fa605-4728-4b45-b473-9619c9329eae', NULL, 'GZM/004/SJZ/VII/2025', 'GIZEM 1453 GREEN', 'gizem-1453-green', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:36:03', '2025-07-22 07:54:24'),
('8e6f5144-9cab-4a78-86d4-9c8633c306b8', NULL, 'GZM/013/SJZ/VII/2025', 'GIZEM 2027 GREEN', 'gizem-2027-green', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:48:08', '2025-07-22 07:48:08'),
('9f6e8744-f2d9-43d2-b157-6d1baea08bbc', NULL, 'GZM/015/SJZ/VII/2025', 'GIZEM 2027d', 'gizem-2027d', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:50:18', '2025-07-22 07:50:18'),
('b5dc7c75-dee3-449b-8e0a-ec5242ee751f', NULL, 'GZM/008/SJZ/VII/2025', 'GIZEM 2026 RED', 'gizem-2026-red', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:43:34', '2025-07-22 07:43:34'),
('ca877fc3-8a96-43d0-a760-bd1046e2f860', NULL, 'GZM/006/SJZ/VII/2025', 'GIZEM 1453 NAVY', 'gizem-1453-navy', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:39:24', '2025-07-22 07:41:32'),
('d8de13ce-9d62-44d5-b7bb-1e4f27546548', NULL, 'GZM/014/SJZ/VII/2025', 'GIZEM 2027 RED', 'gizem-2027-red', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:49:12', '2025-07-22 07:49:12'),
('dbfc838a-9326-4191-83ca-c6fe110aaf92', NULL, 'AQD/017/SJZ/VII/2025', 'AL QUDS 2046 RED', 'al-quds-2046-red', 120, 3000, 'MEREK : AL QUDS\r\nBAHAN : BCF POLYPROPYLENE\r\nKERAPATAN : 420 POINT\r\nKETEBALAN : 12 MM', '4877726d-6547-4114-8811-7783f8cccd3c', 250000.00, 'ac2bb624-b8e4-4170-a73b-38940894132d', '2025-07-23 15:04:11', '2025-07-23 15:04:11'),
('e829318f-3d52-4005-8e14-a9b253bcaf9c', NULL, 'GZM/010/SJZ/VII/2025', 'GIZEM 1918 GREEN', 'gizem-1918-green', 120, 3000, NULL, '4877726d-6547-4114-8811-7783f8cccd3c', 150000.00, 'a82c3b40-b757-4ae9-b342-b94d472544f0', '2025-07-22 07:46:03', '2025-07-22 07:46:03'),
('f255388b-b6f8-4d1d-ad49-080bb806ac09', NULL, 'ALM160/003/SJZ/VII/2025', 'ALMAYA 160X210', 'almaya-160x210', 160, 210, NULL, '41960517-c14b-4406-876d-ac7ac36015bc', 250000.00, '5d2b1d42-4e42-4273-a57a-a67ecfd6dae1', '2025-07-21 17:15:57', '2025-09-20 06:12:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `type` enum('satuan','meteran') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `code`, `name`, `image`, `type`, `created_at`, `updated_at`) VALUES
('02b26304-cb76-406d-811f-b75c98a67992', 'PDSH', 'ROYAL PADISAH', NULL, 'meteran', '2025-07-24 05:43:07', '2025-07-24 05:43:07'),
('0323dd3f-3b9f-4609-b1a2-78281a81e2c0', 'ARC250', 'AYNUR CLASSIC 250X330', NULL, 'satuan', '2025-07-21 16:54:04', '2025-07-21 16:54:04'),
('04e83f17-ca13-431b-bda9-e829804bc555', 'ARV250', 'AYNUR VINTAGE 250X330', NULL, 'satuan', '2025-07-21 16:57:30', '2025-07-21 16:57:30'),
('056440bc-d168-43be-b5e0-af7d89b839fd', 'MLN', 'MAULANA', NULL, 'meteran', '2025-07-23 15:06:30', '2025-07-23 15:06:30'),
('098bc89b-7799-41f1-aeba-8744aea385d4', 'RRD', 'ROYAL RAUDAH', NULL, 'meteran', '2025-07-24 05:41:36', '2025-07-24 05:41:36'),
('13edbc09-e8a6-458a-aced-fe096a3bed63', 'DST', 'DYNASTY', NULL, 'meteran', '2025-07-24 05:46:42', '2025-07-24 05:46:42'),
('16c8a9e8-75cc-49f1-9c10-1c5791a2d063', 'SRZD', 'SHAHRAZAD', NULL, 'meteran', '2025-07-23 15:07:09', '2025-07-23 15:07:09'),
('1702282c-d6d9-4419-86a4-0cbc57efc90c', 'SFR', 'SHAFIRA 115', NULL, 'meteran', '2025-07-24 05:47:01', '2025-07-24 05:47:01'),
('19102ecc-d4ea-4e72-9e61-9057d868ade9', 'AUR115', 'AURORA 115X155', NULL, 'satuan', '2025-09-20 06:18:05', '2025-09-20 06:20:27'),
('19cb77ae-9097-47a8-abc8-32829d55c697', 'ARC125', 'AYNUR CLASSIC 125X160', NULL, 'satuan', '2025-07-21 16:52:35', '2025-07-21 16:52:35'),
('1b05f62e-b4f8-42fd-a730-17308fbfecfb', 'CRW', 'CROWN', NULL, 'meteran', '2025-07-24 05:48:34', '2025-07-24 05:48:34'),
('1e2fdcb7-74ca-4825-b50e-5c5216ca72c9', 'AQF', 'AL QATIF', NULL, 'meteran', '2025-07-21 17:09:44', '2025-07-21 17:09:44'),
('24300a73-820c-4381-9e74-06519b802efe', 'EXP210', 'EXPRESSION 210X310', NULL, 'satuan', '2025-07-21 16:48:48', '2025-07-21 16:48:48'),
('2499c2e1-3248-4e37-a086-b2c6de95a0b2', 'RJK', 'RAJAKHAN', NULL, 'meteran', '2025-07-21 17:06:50', '2025-07-21 17:06:50'),
('363883f5-a32d-4902-8237-1b0ae11adc26', 'AMM', 'AL IMAM', NULL, 'meteran', '2025-07-24 05:47:15', '2025-07-24 05:47:15'),
('44102c9a-d176-46fa-a341-84d3dc0039ac', 'AHJZ', 'AL HIJAZ', NULL, 'meteran', '2025-07-23 15:08:00', '2025-07-23 15:08:00'),
('489c62dd-e9da-4a02-8bbf-feae48675e8b', 'ALM300', 'AL MAYA 300X400', NULL, 'satuan', '2025-07-21 16:41:16', '2025-07-21 16:41:16'),
('4d5ffa78-803f-4b37-ba9e-d0b8f39dad7d', 'SRZ250', 'SHIRAZ 250X330', NULL, 'satuan', '2025-07-21 16:50:22', '2025-07-21 16:50:22'),
('4e4c5d7d-6fc4-425d-9cda-516d0458d107', 'AUR160', 'AURORA 160X210', NULL, 'satuan', '2025-09-20 06:20:57', '2025-09-20 06:20:57'),
('50e92777-5ee0-4fea-9318-dd45808bd65f', 'CNC210', 'CONCORD 210X310', NULL, 'satuan', '2025-07-21 16:45:58', '2025-07-21 16:45:58'),
('57ec38de-8097-4228-93dc-ddc3c3b81be7', 'IST', 'ISTANBUL', NULL, 'meteran', '2025-07-23 15:08:24', '2025-07-24 05:42:46'),
('5d2b1d42-4e42-4273-a57a-a67ecfd6dae1', 'ALM210', 'AL MAYA 210X310', NULL, 'satuan', '2025-07-21 14:08:31', '2025-07-21 14:08:31'),
('5feda9cc-49cf-454b-82eb-db515ec277a8', 'CNC250', 'CNC 250X330', NULL, 'satuan', '2025-07-21 16:46:40', '2025-07-21 16:46:40'),
('60533384-9125-4bf5-a8ad-d150a5b2225e', 'ALM160', 'AL MAYA 160X210', NULL, 'satuan', '2025-07-21 16:40:05', '2025-07-21 16:40:05'),
('6146cb35-8e62-4fe7-a486-f7b3bc1b4de4', 'MNC', 'MONACO', NULL, 'meteran', '2025-07-24 05:49:21', '2025-07-24 05:49:21'),
('6b11a4bc-b4f0-4d3a-835f-5529a97280c7', 'ADLH07', 'AL AADIL HIJAU BINTIK BATU BINTANG (1707)', NULL, 'meteran', '2025-07-21 17:10:32', '2025-09-20 09:07:34'),
('6b7acffe-0e44-42e1-ae77-6aa1180021a3', 'ALM115', 'AL MAYA 115X155', NULL, 'satuan', '2025-07-21 16:40:45', '2025-07-21 16:40:45'),
('6bf8f39f-c2ea-4037-b179-2dba6686e450', 'ARC120', 'AYNUR CLASSIC 120X160', NULL, 'satuan', '2025-07-21 16:51:53', '2025-07-21 16:51:53'),
('72214ed9-b2fb-4a0a-aaee-0f26bbb3b76a', 'BLI', 'BALI', NULL, 'meteran', '2025-07-24 05:48:47', '2025-07-24 05:48:47'),
('75b5de61-e451-45b7-a365-a03f158c30b2', 'MRB250', 'MARBELLA 250X330', NULL, 'satuan', '2025-07-21 17:02:08', '2025-07-21 17:02:08'),
('7a3aafff-2c05-4fce-b4af-f3fdf1839881', 'ARV170', 'AYNUR VINTAGE 170X230', NULL, 'satuan', '2025-07-21 16:56:36', '2025-07-21 16:56:36'),
('7c29ad48-9c5e-4c5c-886e-8eae548f8a3d', 'SRZ300', 'SHIRAZ 300X400', NULL, 'satuan', '2025-07-21 16:50:49', '2025-07-21 16:50:49'),
('7cbf9b81-f5ac-4d5d-8ceb-c4b21566f7c6', 'ARC210', 'AYNUR CLASSIC 210X310', NULL, 'satuan', '2025-07-21 16:53:33', '2025-07-21 16:53:33'),
('7dd32597-87b6-4d42-a2a0-ab9c68dd4955', 'KHR', 'KOHINOOR', NULL, 'satuan', '2025-07-24 05:49:49', '2025-07-24 05:49:49'),
('7e57d2b0-af16-4a12-8fc2-9e84f192f37a', 'MRC', 'MIRAC', NULL, 'meteran', '2025-07-24 05:41:17', '2025-07-24 05:41:17'),
('7fa26d34-a094-45cd-958a-beba5d5121d4', 'TBZ250', 'TABRIZ 250X330', NULL, 'satuan', '2025-07-21 17:00:00', '2025-07-21 17:00:00'),
('86627bf7-efdc-4355-bf31-7a1ce949ad66', 'KGM', 'KINGDOM', NULL, 'meteran', '2025-07-21 17:09:03', '2025-07-21 17:09:03'),
('86a69cdc-47ca-4e62-91ce-710af2ad68ee', 'MRB210', 'MARBELLA 210X310', NULL, 'satuan', '2025-07-21 17:01:46', '2025-07-21 17:01:46'),
('8a1f46a4-9050-4d77-809f-fd85fe2af5b4', 'MCA', 'MECCA', NULL, 'meteran', '2025-07-21 17:05:59', '2025-07-21 17:05:59'),
('92a3c73e-3459-48a8-bea4-3fd777904a8d', 'ARV120', 'AYNUR VINTAGE 120X160', NULL, 'satuan', '2025-07-21 16:55:22', '2025-07-21 16:55:22'),
('967c4109-5087-48d5-b72d-787a5904f4e3', 'REF210', 'REFLECTION 210X310', NULL, 'satuan', '2025-09-20 06:29:11', '2025-09-20 06:29:11'),
('9a65709f-5996-48ba-a375-847dddc2d1bd', 'ADL', 'AL AADIL', NULL, 'meteran', '2025-09-20 09:11:19', '2025-10-01 06:09:49'),
('9c46727d-f3ad-4299-a553-3399851c55ed', 'OSK', 'OSAKA', NULL, 'meteran', '2025-07-24 05:49:02', '2025-07-24 05:49:02'),
('9ff2f032-4200-4330-b4ee-b40f1d1fde06', 'BNC210', 'BIANCO 210X310', NULL, 'satuan', '2025-07-21 16:43:31', '2025-07-21 16:43:31'),
('a2ca8280-04f1-4c50-81f0-ee6e38c298ca', 'CNC300', 'CONCORD 300X400', NULL, 'satuan', '2025-07-21 16:47:02', '2025-07-21 16:47:02'),
('a685e4bb-9684-4016-bdf8-245bd0e75e55', 'MRB170', 'MARBELLA 170X230', NULL, 'satuan', '2025-07-21 17:01:22', '2025-07-21 17:01:22'),
('a73218e5-2120-4af4-a1d4-5e6abc607c26', 'ELT210', 'ELITE 210X310', NULL, 'satuan', '2025-09-20 06:26:15', '2025-09-20 06:26:15'),
('a82c3b40-b757-4ae9-b342-b94d472544f0', 'GZM', 'GIZEM', NULL, 'meteran', '2025-07-22 07:33:55', '2025-07-22 07:33:55'),
('abc4f318-3c07-4d54-818a-21d6ae02e4b8', 'ALF', 'SAJADAH ALIF 70X110', NULL, 'satuan', '2025-07-24 05:50:11', '2025-07-24 05:50:11'),
('ac2bb624-b8e4-4170-a73b-38940894132d', 'AQD', 'AL QUDS', NULL, 'meteran', '2025-07-22 08:14:48', '2025-07-22 08:14:48'),
('ad9d16ed-c04a-485e-a9e5-1700c13697cd', 'ARV210', 'AYNUR VINTAGE 210X310', NULL, 'satuan', '2025-07-21 16:57:07', '2025-07-21 16:57:07'),
('af259252-a877-495d-a508-0cd66a647880', 'ARC170', 'AYNUR CLASSIC 170X230', NULL, 'satuan', '2025-07-21 16:53:06', '2025-07-21 16:53:06'),
('b0a3a55b-a4a4-4442-ab66-481b4c9f04ff', 'ARV125', 'AYNUR VINTAGE 125X160', NULL, 'satuan', '2025-07-21 16:56:00', '2025-07-21 16:56:00'),
('b1c54212-c9e3-48fd-9e20-13da4c25524e', 'AUR210', 'AURORA 210X310', NULL, 'satuan', '2025-09-20 06:21:32', '2025-09-20 06:21:32'),
('b6a0a941-5171-4467-90dc-9b31d6846e09', 'RYLSHR', 'ROYAL SAHARA', NULL, 'meteran', '2025-07-24 05:42:36', '2025-07-24 05:42:36'),
('ba99fc37-fa0d-44bb-bbcb-140ffd582ed2', 'TBZ210', 'TABRIZ 210X310', NULL, 'satuan', '2025-07-21 16:59:33', '2025-07-21 16:59:33'),
('be17e959-1f7a-4eea-b0f7-07a2e105ff8d', 'MRB125', 'MARBELLA 125X160', NULL, 'satuan', '2025-07-21 17:01:01', '2025-07-21 17:01:01'),
('be499f62-910a-4f0d-a560-82833fff3538', 'SVH170', 'SAVEH 170X230', NULL, 'satuan', '2025-09-20 06:30:06', '2025-09-20 06:30:06'),
('bed74bc2-49d5-4840-98b3-6bf8344ca912', 'ANZ', 'AL NAMAZ', NULL, 'meteran', '2025-07-21 17:10:09', '2025-07-21 17:10:09'),
('bef1b68e-0e79-4c66-a727-c144d0e24459', 'BNC160', 'BIANCO 160X210', NULL, 'satuan', '2025-07-21 16:43:06', '2025-07-21 16:43:06'),
('c08422b3-117a-40b7-bf60-4ba8ae8e62f4', 'TBZ170', 'TABRIZ 170X230', NULL, 'satuan', '2025-07-21 16:59:05', '2025-07-21 16:59:05'),
('c1367dfd-7f53-45b2-9fcc-fd5986e4bd96', 'CNC170', 'CONCORD 170X230', NULL, 'satuan', '2025-07-21 16:44:53', '2025-07-21 16:44:53'),
('c5bfa04c-30c6-4063-b1d4-acc9e95e48d6', 'PIST', 'PREMIUM ISTANBUL', NULL, 'meteran', '2025-07-24 05:40:59', '2025-07-24 05:40:59'),
('ceaf5b4b-b97a-493b-9a0c-0a2cf6f28847', 'ARC300', 'AYNUR CLASSIC 300X400', NULL, 'satuan', '2025-07-21 16:54:35', '2025-07-21 16:54:35'),
('d26b1604-584e-4ffa-bd38-955e4efde38e', 'TBZ125', 'TABRIZ 125X160', NULL, 'satuan', '2025-07-21 16:58:41', '2025-07-21 16:58:41'),
('d7fc60ed-ed6c-4641-a1b6-2ea30c1a9b5f', 'MLNM', 'MILLENIUM KARPET', NULL, 'meteran', '2025-07-23 15:07:33', '2025-07-23 15:07:33'),
('da967cb3-f797-484d-a537-9cec90d8d9f0', 'MDN105', 'MADEENA 105', NULL, 'meteran', '2025-07-21 17:03:01', '2025-07-21 17:03:01'),
('db3c2eb1-9331-4cd4-8266-1b2ec93592dd', 'ARV300', 'AYNUR VINTAGE 300X400', NULL, 'satuan', '2025-07-21 16:57:47', '2025-07-21 16:57:47'),
('ddd2e630-6aac-4471-9436-54e65225d58b', 'MBK', 'MITRA BUANA KARPET', NULL, 'satuan', '2025-09-20 06:23:12', '2025-09-20 06:23:12'),
('e0215178-ccfd-4b35-baf2-6f2731458449', 'RMA', 'RASPUR MALAYSIA 200X300', NULL, 'satuan', '2025-07-24 05:50:53', '2025-09-20 06:03:30'),
('e12e06ad-f104-4c6e-b0d0-52a12fc7a741', 'SVH210', 'SAVEH 210X310', NULL, 'satuan', '2025-09-20 06:30:38', '2025-09-20 06:30:38'),
('e1f67637-017c-4532-b789-6a64b70e6f2f', 'SNT210', 'SANTORINI 210X310', NULL, 'satuan', '2025-09-20 06:31:14', '2025-09-20 06:31:14'),
('e645a7fd-e6e0-41e5-b7b4-ce4e2435dc04', 'SRZ210', 'SHIRAZ 210X310', NULL, 'satuan', '2025-07-21 16:49:46', '2025-07-21 16:49:46'),
('ee640e19-5cd7-4535-a360-eea4beee6631', 'AMR', 'AL MIRA', NULL, 'meteran', '2025-07-21 17:06:20', '2025-07-21 17:06:20'),
('f1aade54-380d-4da5-a36c-2e2eb5687efa', 'BDG', 'BANDUNG', NULL, 'meteran', '2025-07-24 05:48:14', '2025-07-24 05:48:14'),
('f585349a-1e72-4cfd-869c-1d40eac02740', 'SRZ170', 'SHIRAZ 170X230', NULL, 'satuan', '2025-07-21 16:49:23', '2025-07-21 16:49:23'),
('f84385fc-dc54-4733-b084-c22cf722a5c2', 'EXP170', 'EXPRESSION 170X230', NULL, 'satuan', '2025-07-21 16:48:24', '2025-07-21 16:48:24'),
('fa859c3c-139f-413a-a364-eeca5e4900a4', 'ANM', 'AL NIMA', NULL, 'meteran', '2025-07-24 05:47:36', '2025-07-24 05:47:36'),
('fb3d0756-7b31-4214-8969-025a8906b5ae', 'MDR105', 'MADEENA 105 ROL', NULL, 'satuan', '2025-07-21 17:05:20', '2025-07-21 17:05:20'),
('fc2139e6-a8bf-40f7-8ad1-6f143bb5f4fb', 'ASRY', 'GIZEM AL SARAY', NULL, 'meteran', '2025-07-24 05:42:10', '2025-07-24 05:42:10'),
('fe4fe1e6-6eb5-4622-8840-14d6c5170f7b', 'EXP125', 'EXPRESSION 125X160', NULL, 'satuan', '2025-07-21 16:48:00', '2025-07-21 16:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `principal_id` char(36) NOT NULL,
  `principal_pic_id` char(36) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `warehouse_id` char(36) DEFAULT NULL,
  `pickup_address_id` char(36) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `total_price_taxed` decimal(15,2) DEFAULT NULL,
  `is_handle_logistic` enum('0','1') NOT NULL DEFAULT '0',
  `transport_id` char(36) DEFAULT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `generate_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `code`, `date`, `principal_id`, `principal_pic_id`, `description`, `warehouse_id`, `pickup_address_id`, `total_price`, `tax`, `total_price_taxed`, `is_handle_logistic`, `transport_id`, `status`, `generate_invoice`, `created_at`, `updated_at`) VALUES
('4d6d20e0-59b6-42a3-9ee0-e40649f8f372', 'PO-CPAL/001/SJZ/VIII/2025', '2025-08-24', '39a82dbf-00aa-4110-b7ca-278c75a446b4', '08551f9e-6084-4ba8-9aed-6da666da893b', NULL, 'b43f7f3f-b94f-45dc-9fbe-a74362089949', NULL, 3797500.00, 11, 4215225.00, '0', NULL, '1', 0, '2025-08-24 06:04:21', '2025-08-24 06:07:39'),
('5ebbf09e-073a-4d26-bcfc-a66aa8d43683', 'PO-CPAL/003/SJZ/X/2025', '2025-10-01', '39a82dbf-00aa-4110-b7ca-278c75a446b4', '08551f9e-6084-4ba8-9aed-6da666da893b', 'apalah g-brand plongo', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', NULL, 3032000.00, 11, 3365520.00, '0', NULL, '2', 1, '2025-10-01 06:24:30', '2025-10-01 06:39:26'),
('80fc3205-e4ba-4d98-b974-88c24230b087', 'PO-CPAL/002/SJZ/IX/2025', '2025-09-21', '39a82dbf-00aa-4110-b7ca-278c75a446b4', '08551f9e-6084-4ba8-9aed-6da666da893b', NULL, 'b43f7f3f-b94f-45dc-9fbe-a74362089949', NULL, 1150000.00, 11, 1276500.00, '0', NULL, '1', 0, '2025-09-21 06:54:36', '2025-09-21 06:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_files`
--

CREATE TABLE `purchase_order_files` (
  `id` char(36) NOT NULL,
  `purchase_order_id` char(36) NOT NULL,
  `name` text DEFAULT NULL,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_files`
--

INSERT INTO `purchase_order_files` (`id`, `purchase_order_id`, `name`, `file`, `created_at`, `updated_at`) VALUES
('20f972af-ba5a-414b-9816-b3f91140ed3b', '80fc3205-e4ba-4d98-b974-88c24230b087', 'Inv 21-09-2025', 'purchase-orders/files/u7FL3xV0MkdumDXnESxcWs0mw9RHCiFR60JgGqnt.jpg', '2025-09-21 06:58:25', '2025-09-21 06:58:25'),
('49ed7dc9-6f6b-419a-ad85-8f2f510dd86d', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', 'Surat Jalan', 'purchase-orders/files/8IkJKLyFrZUEgohlmDJGdWwKoCfqHfijeMzzFAhw.jpg', '2025-10-01 06:30:42', '2025-10-01 06:30:42'),
('7f3bb1b6-9c37-4ab6-b603-31eb3392ce28', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', 'Surat Pembelian / Invoice', 'purchase-orders/files/J1HDhnKStb9bMLlXbBul1h6Glm38W7R6p7vxSorX.pdf', '2025-10-01 06:31:12', '2025-10-01 06:31:12');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_invoices`
--

CREATE TABLE `purchase_order_invoices` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `purchase_order_id` char(36) NOT NULL,
  `payment_status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_invoices`
--

INSERT INTO `purchase_order_invoices` (`id`, `code`, `purchase_order_id`, `payment_status`, `created_at`, `updated_at`) VALUES
('b215c282-aab2-4799-ab7d-5d853eba04cd', 'INV-CPAL/001/SJZ/X/2025', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', '2', '2025-10-01 06:39:26', '2025-10-01 06:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_payments`
--

CREATE TABLE `purchase_order_payments` (
  `id` char(36) NOT NULL,
  `date` date NOT NULL,
  `code` varchar(255) NOT NULL,
  `purchase_order_invoice_id` char(36) NOT NULL,
  `paid_total` decimal(15,2) NOT NULL,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_payments`
--

INSERT INTO `purchase_order_payments` (`id`, `date`, `code`, `purchase_order_invoice_id`, `paid_total`, `file`, `created_at`, `updated_at`) VALUES
('985e86b4-439e-46a4-a5a7-25b258298c99', '2025-10-01', 'PAID-CPAL/002/SJZ/X/2025', 'b215c282-aab2-4799-ab7d-5d853eba04cd', 1365520.00, 'purchase_orders/invoices/payment/M2y4dmXw53OyIB85mA51Qnl7PHIKs6qsdyCt2muU.jpg', '2025-10-01 06:41:09', '2025-10-01 06:41:09'),
('9bf87c77-ed0d-4d76-8bd8-ff64b622db85', '2025-10-01', 'PAID-CPAL/001/SJZ/X/2025', 'b215c282-aab2-4799-ab7d-5d853eba04cd', 2000000.00, 'purchase_orders/invoices/payment/TZIK4UHc3uKWsFy7s0dLu4SHVe2FXv6pAKyVVc1i.jpg', '2025-10-01 06:40:42', '2025-10-01 06:40:42');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_products`
--

CREATE TABLE `purchase_order_products` (
  `id` char(36) NOT NULL,
  `purchase_order_id` char(36) NOT NULL,
  `product_id` char(36) DEFAULT NULL,
  `qty` bigint(20) NOT NULL,
  `price_buy` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_products`
--

INSERT INTO `purchase_order_products` (`id`, `purchase_order_id`, `product_id`, `qty`, `price_buy`, `created_at`, `updated_at`) VALUES
('0d9a567d-4331-4fd8-b142-cca10142e832', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', '601c2618-1612-4f84-bec1-296c109cd32e', 10, 290000.00, '2025-10-01 06:29:39', '2025-10-01 06:29:39'),
('1d861704-327e-4203-9647-4d6f8471659b', '80fc3205-e4ba-4d98-b974-88c24230b087', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 10, 115000.00, '2025-09-21 06:56:50', '2025-09-21 06:56:50'),
('5e49b3ee-6ca3-486c-8841-35652ac3d63a', '4d6d20e0-59b6-42a3-9ee0-e40649f8f372', '64caa2ba-2c89-4d67-be4d-53f3cf1908d0', 10, 379750.00, '2025-08-24 06:07:08', '2025-08-24 06:07:08'),
('a263a018-bcbb-437a-9d92-9857450a964d', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 4, 33000.00, '2025-10-01 06:29:39', '2025-10-01 06:29:39');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receives`
--

CREATE TABLE `purchase_receives` (
  `id` char(36) NOT NULL,
  `image` text NOT NULL,
  `code` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `purchase_order_id` char(36) NOT NULL,
  `warehouse_id` char(36) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `is_lock` tinyint(1) NOT NULL DEFAULT 0,
  `is_stocked` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_receives`
--

INSERT INTO `purchase_receives` (`id`, `image`, `code`, `date`, `purchase_order_id`, `warehouse_id`, `user_id`, `is_lock`, `is_stocked`, `description`, `created_at`, `updated_at`) VALUES
('917cdb33-5f88-4f47-9e9a-9dd14741f81c', 'purchase_receives/bv8d1v817wRTJHpHP8q6q9dVZBKMYKvk9UZr3c3Y.webp', 'PO-RCV/001/SJZ/X/2025', '2025-10-01', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 5, 1, 1, 'jokowow mulyono', '2025-10-01 06:33:29', '2025-10-01 06:36:21'),
('92b00269-2423-4b74-9095-777ed9700d46', 'purchase_receives/PCPRxhBOpwQDdfiwsHG4blSk8dE2627TXyJwQeX7.jpg', 'PO-RCV/002/SJZ/X/2025', '2025-10-01', '5ebbf09e-073a-4d26-bcfc-a66aa8d43683', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 5, 1, 1, NULL, '2025-10-01 06:37:54', '2025-10-01 06:38:57');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receive_products`
--

CREATE TABLE `purchase_receive_products` (
  `id` char(36) NOT NULL,
  `purchase_receive_id` char(36) NOT NULL,
  `purchase_product_id` char(36) NOT NULL,
  `receive_qty` bigint(20) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_receive_products`
--

INSERT INTO `purchase_receive_products` (`id`, `purchase_receive_id`, `purchase_product_id`, `receive_qty`, `description`, `created_at`, `updated_at`) VALUES
('065c3d67-ea1b-45c9-90c8-a3651979e702', '917cdb33-5f88-4f47-9e9a-9dd14741f81c', 'a263a018-bcbb-437a-9d92-9857450a964d', 1, NULL, '2025-10-01 06:34:11', '2025-10-01 06:34:11'),
('261a0a17-aab3-4e21-bcb1-13621a96e5c1', '92b00269-2423-4b74-9095-777ed9700d46', 'a263a018-bcbb-437a-9d92-9857450a964d', 3, NULL, '2025-10-01 06:38:50', '2025-10-01 06:38:50'),
('b09b032e-6cf3-4eef-9ec1-8ff5ee13f483', '917cdb33-5f88-4f47-9e9a-9dd14741f81c', '0d9a567d-4331-4fd8-b142-cca10142e832', 8, NULL, '2025-10-01 06:34:12', '2025-10-01 06:34:12'),
('bb0c07f4-f4ea-4f9d-aafb-586217e09b4c', '92b00269-2423-4b74-9095-777ed9700d46', '0d9a567d-4331-4fd8-b142-cca10142e832', 2, NULL, '2025-10-01 06:38:50', '2025-10-01 06:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `request_orders`
--

CREATE TABLE `request_orders` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `client_id` char(36) NOT NULL,
  `client_pic_id` char(36) DEFAULT NULL,
  `no_refrence` varchar(255) DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `total_price_taxed` decimal(15,2) DEFAULT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `generate_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_orders`
--

INSERT INTO `request_orders` (`id`, `code`, `date`, `client_id`, `client_pic_id`, `no_refrence`, `attachment`, `description`, `total_price`, `tax`, `total_price_taxed`, `status`, `generate_invoice`, `created_at`, `updated_at`) VALUES
('6d6cf21f-7180-4934-803e-081e9ceb8ffa', 'PO-CL/001/SJZ/X/2025', '2025-10-01', '3332b6e2-02d1-46bf-94a8-2821bf2de051', '0ee55835-7064-4066-b14e-aa7fa7cf668c', 'QT/001/RSM/I/2025', 'request-orders/f5ro35rqw4b9I83sIYROpSQSjGsSBs7aeBGFgibF.jpg', NULL, 8112500.00, 11, 9004875.00, '1', 0, '2025-10-01 06:52:07', '2025-10-01 07:03:12');

-- --------------------------------------------------------

--
-- Table structure for table `request_order_files`
--

CREATE TABLE `request_order_files` (
  `id` char(36) NOT NULL,
  `request_order_id` char(36) NOT NULL,
  `name` text DEFAULT NULL,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_order_invoices`
--

CREATE TABLE `request_order_invoices` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `request_order_id` char(36) NOT NULL,
  `purchase_order_id` char(36) DEFAULT NULL,
  `payment_status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_order_payments`
--

CREATE TABLE `request_order_payments` (
  `id` char(36) NOT NULL,
  `date` date NOT NULL,
  `code` varchar(255) NOT NULL,
  `request_order_invoice_id` char(36) NOT NULL,
  `paid_total` decimal(15,2) NOT NULL,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_order_products`
--

CREATE TABLE `request_order_products` (
  `id` char(36) NOT NULL,
  `request_order_id` char(36) NOT NULL,
  `product_id` char(36) DEFAULT NULL,
  `length` decimal(12,2) DEFAULT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `price_sale` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_order_products`
--

INSERT INTO `request_order_products` (`id`, `request_order_id`, `product_id`, `length`, `qty`, `price_sale`, `created_at`, `updated_at`) VALUES
('5f4ddc97-3add-42ef-95c2-7be25fe533f9', '6d6cf21f-7180-4934-803e-081e9ceb8ffa', '601c2618-1612-4f84-bec1-296c109cd32e', 3000.00, 5, 2500.00, '2025-10-01 06:58:59', '2025-10-01 06:58:59'),
('f3f23fb6-ac5d-4a40-b855-da52d8e1aa5c', '6d6cf21f-7180-4934-803e-081e9ceb8ffa', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', NULL, 4, 150000.00, '2025-10-01 06:58:59', '2025-10-01 06:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `request_process`
--

CREATE TABLE `request_process` (
  `id` char(36) NOT NULL,
  `date` date NOT NULL,
  `code` varchar(255) NOT NULL,
  `request_order_id` char(36) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `description` text DEFAULT NULL,
  `is_handle_logistic` enum('0','1') NOT NULL DEFAULT '0',
  `transport_id` char(36) DEFAULT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_process`
--

INSERT INTO `request_process` (`id`, `date`, `code`, `request_order_id`, `user_id`, `description`, `is_handle_logistic`, `transport_id`, `status`, `created_at`, `updated_at`) VALUES
('9f26dcce-12c0-4586-b253-87efb996dc1f', '2025-10-01', 'PROCESS/002/SJZ/X/2025', '6d6cf21f-7180-4934-803e-081e9ceb8ffa', 6, 'asdasd', '0', NULL, '0', '2025-10-01 07:04:01', '2025-10-01 07:04:01'),
('cd1ef96b-44be-4f12-96d3-e22659d4d3b9', '2025-10-01', 'PROCESS/001/SJZ/X/2025', '6d6cf21f-7180-4934-803e-081e9ceb8ffa', 6, 'asdasdasd', '0', NULL, '2', '2025-10-01 06:59:47', '2025-10-01 07:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `request_process_products`
--

CREATE TABLE `request_process_products` (
  `id` char(36) NOT NULL,
  `request_process_id` char(36) NOT NULL,
  `product_id` char(36) DEFAULT NULL,
  `from_type` char(36) DEFAULT NULL,
  `from_id` char(36) DEFAULT NULL,
  `qty` bigint(20) NOT NULL,
  `length` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_process_products`
--

INSERT INTO `request_process_products` (`id`, `request_process_id`, `product_id`, `from_type`, `from_id`, `qty`, `length`, `created_at`, `updated_at`) VALUES
('08340a57-7523-4045-8df4-e6929cf3a6f7', 'cd1ef96b-44be-4f12-96d3-e22659d4d3b9', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 5, 3000.00, '2025-10-01 07:03:05', '2025-10-01 07:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` char(36) NOT NULL,
  `product_id` char(36) NOT NULL,
  `nowin_type` enum('warehouse','store') NOT NULL,
  `nowin_id` char(36) DEFAULT NULL,
  `qty` bigint(20) NOT NULL,
  `trx_type` enum('in','out','onway') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `nowin_type`, `nowin_id`, `qty`, `trx_type`, `created_at`, `updated_at`) VALUES
('5f839244-3f67-4f61-8b3d-c7a7b4ca1988', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 'store', '8cebd496-7492-4691-9f0e-ba3baab17e21', 2, 'out', '2025-10-01 07:11:42', '2025-10-01 07:11:42'),
('861e62a9-58d9-40a4-9a89-5b64c071d743', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 3, 'in', '2025-10-01 06:38:57', '2025-10-01 06:38:57'),
('a9f58b48-8f48-492d-a931-f37dd13b59aa', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 4, 'out', '2025-10-01 06:49:11', '2025-10-01 06:49:28'),
('f53a7793-c5c9-47f8-a967-ea0857900ce9', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 'store', '8cebd496-7492-4691-9f0e-ba3baab17e21', 4, 'in', '2025-10-01 06:49:28', '2025-10-01 06:49:28'),
('f9b12a8d-0260-47c7-9cb1-b1d0f01fa099', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 1, 'in', '2025-10-01 06:36:21', '2025-10-01 06:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `stock_meters`
--

CREATE TABLE `stock_meters` (
  `id` char(36) NOT NULL,
  `product_id` char(36) NOT NULL,
  `nowin_type` enum('warehouse','store') NOT NULL,
  `nowin_id` char(36) DEFAULT NULL,
  `is_onway` tinyint(1) NOT NULL DEFAULT 0,
  `sold_length` bigint(20) DEFAULT NULL,
  `length` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_meters`
--

INSERT INTO `stock_meters` (`id`, `product_id`, `nowin_type`, `nowin_id`, `is_onway`, `sold_length`, `length`, `created_at`, `updated_at`) VALUES
('1f2961d1-5d8d-4c73-a270-906d6dc81a28', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 3000, 3000, '2025-10-01 06:36:21', '2025-10-01 07:03:18'),
('2305223d-7d3f-4dfd-8fad-3684d500c514', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 3000, 3000, '2025-10-01 06:36:21', '2025-10-01 07:03:18'),
('6684196f-33a9-4148-b703-96ea80f996c2', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 3000, 3000, '2025-10-01 06:36:21', '2025-10-01 07:03:18'),
('78c774e8-3f4f-45d8-815e-7e63b32fe5c3', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 3000, 3000, '2025-10-01 06:36:21', '2025-10-01 07:03:18'),
('85552b1d-8ecf-4055-a9ce-f46dd0f52dd4', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 3000, 3000, '2025-10-01 06:38:57', '2025-10-01 07:03:18'),
('9a0882d2-4420-4f1e-aca0-e8f617097596', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 0, 3000, '2025-10-01 06:36:21', '2025-10-01 06:36:21'),
('baa9b075-54f4-4c9b-852c-989ea3989f02', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 0, 3000, '2025-10-01 06:36:21', '2025-10-01 06:36:21'),
('d04d009b-ecff-4355-bba8-3f50abd07c89', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 0, 3000, '2025-10-01 06:38:57', '2025-10-01 06:38:57'),
('ede7c5bc-3232-44f2-957d-0c95b8d3b2d2', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 0, 3000, '2025-10-01 06:36:21', '2025-10-01 06:36:21'),
('f4192f5d-41cc-43ae-8b0b-91fd86e78464', '601c2618-1612-4f84-bec1-296c109cd32e', 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 0, 0, 3000, '2025-10-01 06:36:21', '2025-10-01 06:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `image` text DEFAULT NULL,
  `transport_id` char(36) DEFAULT NULL,
  `from_type` enum('store','warehouse') NOT NULL,
  `from_id` char(36) NOT NULL,
  `to_type` enum('store','warehouse') NOT NULL,
  `to_id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `code`, `date`, `image`, `transport_id`, `from_type`, `from_id`, `to_type`, `to_id`, `user_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
('6abc2510-5ebe-4c8b-befb-aa2d74fb1d1e', 'MOVE/001/SJZ/X/2025', '2025-10-01', 'stock_movements/yb8KFrolZ5fN3Qff3QF1HaVgBZZ01cXSvHgijfsv.jpg', NULL, 'warehouse', 'b43f7f3f-b94f-45dc-9fbe-a74362089949', 'store', '8cebd496-7492-4691-9f0e-ba3baab17e21', '4', 'asdasdasd', '2', '2025-10-01 06:47:52', '2025-10-01 06:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement_files`
--

CREATE TABLE `stock_movement_files` (
  `id` char(36) NOT NULL,
  `movement_id` char(36) NOT NULL,
  `name` text DEFAULT NULL,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement_products`
--

CREATE TABLE `stock_movement_products` (
  `id` char(36) NOT NULL,
  `movement_id` char(36) NOT NULL,
  `product_id` char(36) NOT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movement_products`
--

INSERT INTO `stock_movement_products` (`id`, `movement_id`, `product_id`, `qty`, `description`, `created_at`, `updated_at`) VALUES
('bda941dc-b8f5-46e2-8f28-060b0c3a3fed', '6abc2510-5ebe-4c8b-befb-aa2d74fb1d1e', '1433c1ba-ab51-46ba-8eb3-c460934ba87d', 4, '', '2025-10-01 06:48:57', '2025-10-01 06:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `image`, `name`, `email`, `phone`, `fax`, `description`, `address`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
('8cebd496-7492-4691-9f0e-ba3baab17e21', NULL, 'SAJJAD ZAMZAMI CARPET', 'sajjadzamzamicarpet@gmail.com', '085156546132', NULL, NULL, 'JL KH MAS MANSYUR 14, KB.KACANG, TANAH ABANG, JAKARTA PUSAT', 'JAKARTA PUSAT', 10240, '2025-07-21 17:18:36', '2025-07-21 17:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `transports`
--

CREATE TABLE `transports` (
  `id` char(36) NOT NULL,
  `date` date DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `spk_code` varchar(255) NOT NULL,
  `surjal_code` varchar(255) NOT NULL,
  `logistic_id` char(36) NOT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `total_price_taxed` decimal(15,2) DEFAULT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `generate_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_invoices`
--

CREATE TABLE `transport_invoices` (
  `id` char(36) NOT NULL,
  `code` varchar(255) NOT NULL,
  `transport_id` char(36) NOT NULL,
  `payment_status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_payments`
--

CREATE TABLE `transport_payments` (
  `id` char(36) NOT NULL,
  `date` date NOT NULL,
  `code` varchar(255) NOT NULL,
  `transport_invoice_id` char(36) NOT NULL,
  `paid_total` decimal(15,2) NOT NULL,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
('41960517-c14b-4406-876d-ac7ac36015bc', 'ROLL', 'ROLL', '2025-07-21 14:04:53', '2025-07-21 14:04:53'),
('4877726d-6547-4114-8811-7783f8cccd3c', 'METERAN', 'M', '2025-07-21 17:14:16', '2025-07-21 17:14:16'),
('dc02889e-600b-4103-b5a0-949e30e6eee9', 'ROLL', 'ROL', '2025-07-21 14:09:19', '2025-07-21 14:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` char(36) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `image`, `name`, `email`, `phone`, `fax`, `description`, `address`, `city`, `postal_code`, `created_at`, `updated_at`) VALUES
('b43f7f3f-b94f-45dc-9fbe-a74362089949', NULL, 'SAJJAD ZAMZAMI CARPET', 'sajjadzamzamicarpet@gmail.com', '085156546132', NULL, NULL, 'JL KH MAS MANSYUR NO 14, KB.KACANG, TANAH ABANG, JAKARTA PUSAT', 'JAKARTA PUSAT', 10240, '2025-07-21 17:20:49', '2025-07-21 17:20:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bals`
--
ALTER TABLE `bals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bals_purchase_receive_id_foreign` (`purchase_receive_id`);

--
-- Indexes for table `bal_products`
--
ALTER TABLE `bal_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bal_products_bal_id_foreign` (`bal_id`),
  ADD KEY `bal_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `bal_unpacks`
--
ALTER TABLE `bal_unpacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bal_unpacks_bal_id_foreign` (`bal_id`);

--
-- Indexes for table `bal_unpack_products`
--
ALTER TABLE `bal_unpack_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bal_unpack_products_bal_unpack_id_foreign` (`bal_unpack_id`),
  ADD KEY `bal_unpack_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_addresses`
--
ALTER TABLE `client_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_addresses_client_id_foreign` (`client_id`);

--
-- Indexes for table `client_pics`
--
ALTER TABLE `client_pics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_pics_client_id_foreign` (`client_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_orders_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_orders_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `customer_order_products`
--
ALTER TABLE `customer_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_order_products_customer_order_id_foreign` (`customer_order_id`),
  ADD KEY `customer_order_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `logistics`
--
ALTER TABLE `logistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logistic_addresses`
--
ALTER TABLE `logistic_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logistic_addresses_logistic_id_foreign` (`logistic_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packs`
--
ALTER TABLE `packs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packs_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partner_addresses`
--
ALTER TABLE `partner_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partner_addresses_partner_id_foreign` (`partner_id`);

--
-- Indexes for table `partner_debts`
--
ALTER TABLE `partner_debts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partner_debts_customer_order_id_foreign` (`customer_order_id`);

--
-- Indexes for table `partner_pics`
--
ALTER TABLE `partner_pics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partner_pics_partner_id_foreign` (`partner_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `principals`
--
ALTER TABLE `principals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `principal_addresses`
--
ALTER TABLE `principal_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `principal_addresses_principal_id_foreign` (`principal_id`);

--
-- Indexes for table `principal_pics`
--
ALTER TABLE `principal_pics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `principal_pics_principal_id_foreign` (`principal_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_unit_id_foreign` (`unit_id`),
  ADD KEY `products_product_type_id_foreign` (`product_type_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_types_code_unique` (`code`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_principal_id_foreign` (`principal_id`),
  ADD KEY `purchase_orders_principal_pic_id_foreign` (`principal_pic_id`),
  ADD KEY `purchase_orders_transport_id_foreign` (`transport_id`),
  ADD KEY `purchase_orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_orders_pickup_address_id_foreign` (`pickup_address_id`);

--
-- Indexes for table `purchase_order_files`
--
ALTER TABLE `purchase_order_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_files_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `purchase_order_invoices`
--
ALTER TABLE `purchase_order_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_invoices_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `purchase_order_payments`
--
ALTER TABLE `purchase_order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_payments_purchase_order_invoice_id_foreign` (`purchase_order_invoice_id`);

--
-- Indexes for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_products_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_receives`
--
ALTER TABLE `purchase_receives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_receives_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_receives_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `purchase_receive_products`
--
ALTER TABLE `purchase_receive_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_receive_products_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `purchase_receive_products_purchase_receive_id_foreign` (`purchase_receive_id`);

--
-- Indexes for table `request_orders`
--
ALTER TABLE `request_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_orders_client_id_foreign` (`client_id`),
  ADD KEY `request_orders_client_pic_id_foreign` (`client_pic_id`);

--
-- Indexes for table `request_order_files`
--
ALTER TABLE `request_order_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_order_files_request_order_id_foreign` (`request_order_id`);

--
-- Indexes for table `request_order_invoices`
--
ALTER TABLE `request_order_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_order_invoices_request_order_id_foreign` (`request_order_id`),
  ADD KEY `request_order_invoices_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `request_order_payments`
--
ALTER TABLE `request_order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_order_payments_request_order_invoice_id_foreign` (`request_order_invoice_id`);

--
-- Indexes for table `request_order_products`
--
ALTER TABLE `request_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_order_products_request_order_id_foreign` (`request_order_id`),
  ADD KEY `request_order_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `request_process`
--
ALTER TABLE `request_process`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_process_request_order_id_foreign` (`request_order_id`),
  ADD KEY `request_process_transport_id_foreign` (`transport_id`);

--
-- Indexes for table `request_process_products`
--
ALTER TABLE `request_process_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_process_products_request_process_id_foreign` (`request_process_id`),
  ADD KEY `request_process_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_meters`
--
ALTER TABLE `stock_meters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_meters_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_transport_id_foreign` (`transport_id`);

--
-- Indexes for table `stock_movement_files`
--
ALTER TABLE `stock_movement_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movement_files_movement_id_foreign` (`movement_id`);

--
-- Indexes for table `stock_movement_products`
--
ALTER TABLE `stock_movement_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movement_products_movement_id_foreign` (`movement_id`),
  ADD KEY `stock_movement_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transports_logistic_id_foreign` (`logistic_id`);

--
-- Indexes for table `transport_invoices`
--
ALTER TABLE `transport_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transport_invoices_transport_id_foreign` (`transport_id`);

--
-- Indexes for table `transport_payments`
--
ALTER TABLE `transport_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transport_payments_transport_invoice_id_foreign` (`transport_invoice_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bals`
--
ALTER TABLE `bals`
  ADD CONSTRAINT `bals_purchase_receive_id_foreign` FOREIGN KEY (`purchase_receive_id`) REFERENCES `purchase_receives` (`id`);

--
-- Constraints for table `bal_products`
--
ALTER TABLE `bal_products`
  ADD CONSTRAINT `bal_products_bal_id_foreign` FOREIGN KEY (`bal_id`) REFERENCES `bals` (`id`),
  ADD CONSTRAINT `bal_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `bal_unpacks`
--
ALTER TABLE `bal_unpacks`
  ADD CONSTRAINT `bal_unpacks_bal_id_foreign` FOREIGN KEY (`bal_id`) REFERENCES `bals` (`id`);

--
-- Constraints for table `bal_unpack_products`
--
ALTER TABLE `bal_unpack_products`
  ADD CONSTRAINT `bal_unpack_products_bal_unpack_id_foreign` FOREIGN KEY (`bal_unpack_id`) REFERENCES `bal_unpacks` (`id`),
  ADD CONSTRAINT `bal_unpack_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `client_addresses`
--
ALTER TABLE `client_addresses`
  ADD CONSTRAINT `client_addresses_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_pics`
--
ALTER TABLE `client_pics`
  ADD CONSTRAINT `client_pics_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `customer_orders_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`);

--
-- Constraints for table `customer_order_products`
--
ALTER TABLE `customer_order_products`
  ADD CONSTRAINT `customer_order_products_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `logistic_addresses`
--
ALTER TABLE `logistic_addresses`
  ADD CONSTRAINT `logistic_addresses_logistic_id_foreign` FOREIGN KEY (`logistic_id`) REFERENCES `logistics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packs`
--
ALTER TABLE `packs`
  ADD CONSTRAINT `packs_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `partner_addresses`
--
ALTER TABLE `partner_addresses`
  ADD CONSTRAINT `partner_addresses_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `partner_debts`
--
ALTER TABLE `partner_debts`
  ADD CONSTRAINT `partner_debts_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`);

--
-- Constraints for table `partner_pics`
--
ALTER TABLE `partner_pics`
  ADD CONSTRAINT `partner_pics_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `principal_addresses`
--
ALTER TABLE `principal_addresses`
  ADD CONSTRAINT `principal_addresses_principal_id_foreign` FOREIGN KEY (`principal_id`) REFERENCES `principals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `principal_pics`
--
ALTER TABLE `principal_pics`
  ADD CONSTRAINT `principal_pics_principal_id_foreign` FOREIGN KEY (`principal_id`) REFERENCES `principals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_product_type_id_foreign` FOREIGN KEY (`product_type_id`) REFERENCES `product_types` (`id`),
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_pickup_address_id_foreign` FOREIGN KEY (`pickup_address_id`) REFERENCES `principal_addresses` (`id`),
  ADD CONSTRAINT `purchase_orders_principal_id_foreign` FOREIGN KEY (`principal_id`) REFERENCES `principals` (`id`),
  ADD CONSTRAINT `purchase_orders_principal_pic_id_foreign` FOREIGN KEY (`principal_pic_id`) REFERENCES `principal_pics` (`id`),
  ADD CONSTRAINT `purchase_orders_transport_id_foreign` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`),
  ADD CONSTRAINT `purchase_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `purchase_order_files`
--
ALTER TABLE `purchase_order_files`
  ADD CONSTRAINT `purchase_order_files_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_invoices`
--
ALTER TABLE `purchase_order_invoices`
  ADD CONSTRAINT `purchase_order_invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`);

--
-- Constraints for table `purchase_order_payments`
--
ALTER TABLE `purchase_order_payments`
  ADD CONSTRAINT `purchase_order_payments_purchase_order_invoice_id_foreign` FOREIGN KEY (`purchase_order_invoice_id`) REFERENCES `purchase_order_invoices` (`id`);

--
-- Constraints for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD CONSTRAINT `purchase_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_order_products_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_receives`
--
ALTER TABLE `purchase_receives`
  ADD CONSTRAINT `purchase_receives_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `purchase_receives_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `purchase_receive_products`
--
ALTER TABLE `purchase_receive_products`
  ADD CONSTRAINT `purchase_receive_products_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_order_products` (`id`),
  ADD CONSTRAINT `purchase_receive_products_purchase_receive_id_foreign` FOREIGN KEY (`purchase_receive_id`) REFERENCES `purchase_receives` (`id`);

--
-- Constraints for table `request_orders`
--
ALTER TABLE `request_orders`
  ADD CONSTRAINT `request_orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `request_orders_client_pic_id_foreign` FOREIGN KEY (`client_pic_id`) REFERENCES `client_pics` (`id`);

--
-- Constraints for table `request_order_files`
--
ALTER TABLE `request_order_files`
  ADD CONSTRAINT `request_order_files_request_order_id_foreign` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_order_invoices`
--
ALTER TABLE `request_order_invoices`
  ADD CONSTRAINT `request_order_invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `request_order_invoices_request_order_id_foreign` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`);

--
-- Constraints for table `request_order_payments`
--
ALTER TABLE `request_order_payments`
  ADD CONSTRAINT `request_order_payments_request_order_invoice_id_foreign` FOREIGN KEY (`request_order_invoice_id`) REFERENCES `request_order_invoices` (`id`);

--
-- Constraints for table `request_order_products`
--
ALTER TABLE `request_order_products`
  ADD CONSTRAINT `request_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `request_order_products_request_order_id_foreign` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_process`
--
ALTER TABLE `request_process`
  ADD CONSTRAINT `request_process_request_order_id_foreign` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`),
  ADD CONSTRAINT `request_process_transport_id_foreign` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`);

--
-- Constraints for table `request_process_products`
--
ALTER TABLE `request_process_products`
  ADD CONSTRAINT `request_process_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `request_process_products_request_process_id_foreign` FOREIGN KEY (`request_process_id`) REFERENCES `request_process` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_meters`
--
ALTER TABLE `stock_meters`
  ADD CONSTRAINT `stock_meters_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_transport_id_foreign` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`);

--
-- Constraints for table `stock_movement_files`
--
ALTER TABLE `stock_movement_files`
  ADD CONSTRAINT `stock_movement_files_movement_id_foreign` FOREIGN KEY (`movement_id`) REFERENCES `stock_movements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movement_products`
--
ALTER TABLE `stock_movement_products`
  ADD CONSTRAINT `stock_movement_products_movement_id_foreign` FOREIGN KEY (`movement_id`) REFERENCES `stock_movements` (`id`),
  ADD CONSTRAINT `stock_movement_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transports`
--
ALTER TABLE `transports`
  ADD CONSTRAINT `transports_logistic_id_foreign` FOREIGN KEY (`logistic_id`) REFERENCES `logistics` (`id`);

--
-- Constraints for table `transport_invoices`
--
ALTER TABLE `transport_invoices`
  ADD CONSTRAINT `transport_invoices_transport_id_foreign` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`);

--
-- Constraints for table `transport_payments`
--
ALTER TABLE `transport_payments`
  ADD CONSTRAINT `transport_payments_transport_invoice_id_foreign` FOREIGN KEY (`transport_invoice_id`) REFERENCES `transport_invoices` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
