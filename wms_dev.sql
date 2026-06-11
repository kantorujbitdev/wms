-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2026 at 04:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wms`
--

-- --------------------------------------------------------

--
-- Table structure for table `api`
--

CREATE TABLE `api` (
  `id_api` int(11) NOT NULL,
  `nama_api` varchar(100) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api`
--

INSERT INTO `api` (`id_api`, `nama_api`, `endpoint`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Login', '/inventory/api/auth.php', 1, '2025-11-06 14:17:28', '2025-11-06 14:17:28'),
(2, 'Product', '/inventory/api/product', 1, '2025-11-06 14:17:28', '2025-11-11 11:41:22'),
(3, 'Gudang', '/inventory/api/warehouse', 1, '2025-11-06 14:17:28', '2025-11-07 14:41:36'),
(4, 'Transaksi', '/inventory/api/transaksi.php', 1, '2025-11-06 14:17:28', '2025-11-06 14:17:28'),
(5, 'Laporan', '/inventory/api/laporan.php', 1, '2025-11-06 14:17:28', '2025-11-06 14:17:28'),
(6, 'User', '/inventory/api/user', 1, '2025-11-06 14:17:28', '2025-11-06 14:17:28'),
(7, 'Product Type', '/inventory/api/product_type', 1, '2025-11-06 14:17:28', '2025-11-07 16:11:09'),
(8, 'Unit Type', '/inventory/api/unit_type', 1, '2025-11-06 14:17:28', '2025-11-07 17:13:40'),
(9, 'Logout', '/inventory/api/logout', 1, '2025-11-06 14:17:28', '2025-11-07 17:13:40'),
(10, 'Customer', '/inventory/api/customer', 1, '2025-11-06 14:17:28', '2025-11-07 17:13:40'),
(11, 'Supplier', '/inventory/api/supplier', 1, '2025-11-06 14:17:28', '2025-11-07 17:13:40'),
(12, 'Stok', '/inventory/api/warehouse_stock', 1, '2025-11-06 14:17:28', '2025-11-21 17:28:56'),
(13, 'Penerimaan', '/inventory/api/stockin', 1, '2025-11-06 14:17:28', '2025-11-21 17:28:56'),
(14, 'Pengiriman', '/inventory/api/stockout', 1, '2025-11-06 14:17:28', '2025-11-21 17:28:56'),
(15, 'Edit Penerimaan', '/inventory/api/stockin_edit', 1, '2025-11-06 14:17:28', '2025-12-10 16:43:08'),
(16, 'Edit Pengiriman', '/inventory/api/stockout_edit', 1, '2025-11-06 14:17:28', '2025-12-05 10:07:25'),
(17, 'Card Stock', '/inventory/api/stockmovement', 1, '2025-11-06 14:17:28', '2025-12-05 10:07:25'),
(18, 'List Kode Pengiriman', '/inventory/api/stockin_transfer', 1, '2025-11-06 14:17:28', '2025-12-05 10:07:25'),
(19, 'Pengiriman Details', '/inventory/api/stockin_transfer_getdetail', 1, '2025-11-06 14:17:28', '2025-12-05 10:07:25');

-- --------------------------------------------------------

--
-- Table structure for table `logo_management`
--

CREATE TABLE `logo_management` (
  `id_logo` int(10) UNSIGNED NOT NULL,
  `logo` varchar(255) NOT NULL COMMENT 'Nama file atau path logo',
  `nama_pt` varchar(255) NOT NULL COMMENT 'Nama perusahaan',
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Aktif, 0=Nonaktif',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logo_management`
--

INSERT INTO `logo_management` (`id_logo`, `logo`, `nama_pt`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'uploads/logo/9220d534332f49d0549defe8bd6338be.png', 'PT. USAHA JAYAMAS BHAKTI', 1, '2026-06-09 15:49:50', '2026-06-10 09:44:05'),
(2, 'uploads/logo/f92529c21213d34454f73981c8eb2eaa.png', 'PT. LADANG USAHA JAYA BERSAMA', 1, '2026-06-09 15:09:46', '2026-06-09 15:55:31'),
(3, 'uploads/logo/219a472f7cdcc9e3b6186d0ccbf38dbd.png', 'PT. USAHA JAYAMAS TEKNIK', 1, '2026-06-09 15:54:40', '2026-06-09 15:55:58'),
(4, 'uploads/logo/66be9c736b8463607dc5b0102394cf67.png', 'PT. USAHA JAYA ENGGINERING', 1, '2026-06-09 15:57:07', '2026-06-09 15:57:07');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_pengaturan` int(11) NOT NULL,
  `nama_pengaturan` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `is_image` enum('false','true','','') NOT NULL DEFAULT 'false',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id_pengaturan`, `nama_pengaturan`, `value`, `keterangan`, `is_image`, `updated_at`) VALUES
(1, 'app_name', 'WMS dev', 'Nama singkat aplikasi', 'false', '2026-05-07 16:34:04'),
(2, 'app_fullname', 'Warehouse Management System', 'Nama lengkap aplikasi', 'false', '2025-11-06 13:35:17'),
(3, 'app_logo', 'uploads/c5f0897efd9494392a6bd9d7f573d427.png', 'Path logo aplikasi', 'true', '2025-12-05 14:16:27'),
(4, 'app_footer_text', 'All Rights Reserved', 'Teks footer aplikasi', 'false', '2025-11-07 09:39:07'),
(5, 'api_base_url', 'http://localhost', 'Base URL utama API eksternal', 'false', '2026-04-29 14:49:58'),
(6, 'api_timeout', '30', 'Timeout API (detik)', 'false', '2025-11-06 13:38:19'),
(7, 'app_logo_blue', 'uploads/7318ac9be7a59a2b72464c8434b29c6b.png', 'Path logo aplikasi', 'true', '2025-12-05 14:15:57'),
(8, 'app_pt_name', 'PT. Usaha Jayamas Bhakti', 'Nama perusahaan aplikasi', 'false', '2025-11-06 13:35:17');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `menu_key` varchar(100) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_name`, `menu_key`, `can_view`, `can_edit`, `can_delete`, `created_at`) VALUES
(1, 'superadmin', 'dashboard', 1, 1, 1, '2025-12-02 17:05:59'),
(2, 'superadmin', 'gudang', 1, 1, 1, '2025-12-02 17:05:59'),
(3, 'superadmin', 'gudang_project', 1, 1, 1, '2025-12-02 17:05:59'),
(4, 'superadmin', 'gudang_utama', 1, 1, 1, '2025-12-02 17:05:59'),
(5, 'superadmin', 'barang', 1, 1, 1, '2025-12-02 17:05:59'),
(6, 'superadmin', 'tipe_produk', 1, 1, 1, '2025-12-02 17:05:59'),
(7, 'superadmin', 'tipe_satuan', 1, 1, 1, '2025-12-02 17:05:59'),
(8, 'superadmin', 'produk', 1, 1, 1, '2025-12-02 17:05:59'),
(9, 'superadmin', 'gudang_stok', 1, 1, 1, '2025-12-02 17:05:59'),
(10, 'superadmin', 'penerimaan', 1, 1, 1, '2025-12-02 17:05:59'),
(11, 'superadmin', 'penerimaan_antar_gudang', 1, 1, 1, '2025-12-02 17:05:59'),
(12, 'superadmin', 'supplier_penerimaan', 1, 1, 1, '2025-12-02 17:05:59'),
(13, 'superadmin', 'pengguna_penerimaan', 1, 1, 1, '2025-12-02 17:05:59'),
(14, 'superadmin', 'pengiriman', 1, 1, 1, '2025-12-02 17:05:59'),
(15, 'superadmin', 'pengiriman_antar_gudang', 1, 1, 1, '2025-12-02 17:05:59'),
(16, 'superadmin', 'penggunaan', 1, 1, 1, '2025-12-02 17:05:59'),
(17, 'superadmin', 'laporan', 1, 1, 1, '2025-12-02 17:05:59'),
(18, 'superadmin', 'customer', 1, 1, 1, '2025-12-02 17:05:59'),
(19, 'superadmin', 'supplier', 1, 1, 1, '2025-12-02 17:05:59'),
(20, 'superadmin', 'user', 1, 1, 1, '2025-12-02 17:05:59'),
(21, 'superadmin', 'pengaturan', 1, 1, 1, '2025-12-02 17:05:59'),
(22, 'superadmin', 'web_pengaturan', 1, 1, 1, '2025-12-02 17:05:59'),
(23, 'superadmin', 'api_pengaturan', 1, 1, 1, '2025-12-02 17:05:59'),
(24, 'admin', 'dashboard', 1, 1, 0, '2025-12-02 17:05:59'),
(25, 'admin', 'gudang', 0, 0, 0, '2025-12-02 17:05:59'),
(26, 'admin', 'gudang_project', 0, 0, 0, '2025-12-02 17:05:59'),
(27, 'admin', 'gudang_utama', 0, 0, 0, '2025-12-02 17:05:59'),
(28, 'admin', 'barang', 1, 0, 0, '2025-12-02 17:05:59'),
(29, 'admin', 'tipe_produk', 1, 0, 0, '2025-12-02 17:05:59'),
(30, 'admin', 'tipe_satuan', 1, 0, 0, '2025-12-02 17:05:59'),
(31, 'admin', 'produk', 1, 1, 0, '2025-12-02 17:05:59'),
(32, 'admin', 'gudang_stok', 1, 1, 0, '2025-12-02 17:05:59'),
(33, 'admin', 'penerimaan', 1, 1, 0, '2025-12-02 17:05:59'),
(34, 'admin', 'penerimaan_antar_gudang', 1, 1, 0, '2025-12-02 17:05:59'),
(35, 'admin', 'supplier_penerimaan', 1, 1, 0, '2025-12-02 17:05:59'),
(36, 'admin', 'pengguna_penerimaan', 1, 1, 0, '2025-12-02 17:05:59'),
(37, 'admin', 'pengiriman', 1, 1, 0, '2025-12-02 17:05:59'),
(38, 'admin', 'pengiriman_antar_gudang', 1, 1, 0, '2025-12-02 17:05:59'),
(39, 'admin', 'penggunaan', 1, 1, 0, '2025-12-02 17:05:59'),
(40, 'admin', 'laporan', 1, 1, 0, '2025-12-02 17:05:59'),
(41, 'admin', 'customer', 1, 1, 0, '2025-12-02 17:05:59'),
(42, 'admin', 'supplier', 1, 1, 0, '2025-12-02 17:05:59'),
(43, 'admin', 'user', 1, 1, 0, '2025-12-02 17:05:59'),
(44, 'staff', 'dashboard', 1, 0, 0, '2025-12-02 17:05:59'),
(45, 'staff', 'gudang_stok', 1, 0, 0, '2025-12-02 17:05:59'),
(46, 'staff', 'penerimaan', 1, 1, 0, '2025-12-02 17:05:59'),
(47, 'staff', 'penerimaan_antar_gudang', 1, 1, 0, '2025-12-02 17:05:59'),
(48, 'staff', 'supplier_penerimaan', 1, 1, 0, '2025-12-02 17:05:59'),
(49, 'staff', 'pengguna_penerimaan', 1, 1, 0, '2025-12-02 17:05:59'),
(50, 'staff', 'pengiriman', 1, 1, 0, '2025-12-02 17:05:59'),
(51, 'staff', 'pengiriman_antar_gudang', 1, 1, 0, '2025-12-02 17:05:59'),
(52, 'staff', 'penggunaan', 1, 0, 0, '2025-12-02 17:05:59'),
(53, 'staff', 'laporan', 1, 0, 0, '2025-12-02 17:05:59'),
(55, 'staff', 'supplier', 1, 1, 0, '2025-12-02 17:05:59'),
(56, 'staff', 'customer', 0, 0, 0, '2025-12-02 17:05:59'),
(57, 'superadmin', 'logo_pengaturan', 1, 1, 1, '2025-12-02 17:05:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`id_api`);

--
-- Indexes for table `logo_management`
--
ALTER TABLE `logo_management`
  ADD PRIMARY KEY (`id_logo`),
  ADD KEY `idx_status_aktif` (`status_aktif`),
  ADD KEY `idx_nama_pt` (`nama_pt`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`),
  ADD UNIQUE KEY `nama_pengaturan_unique` (`nama_pengaturan`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_menu` (`role_name`,`menu_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api`
--
ALTER TABLE `api`
  MODIFY `id_api` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `logo_management`
--
ALTER TABLE `logo_management`
  MODIFY `id_logo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
