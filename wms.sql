-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 12, 2025 at 04:32 AM
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
(9, 'Logout', '/inventory/api/logout', 1, '2025-11-06 14:17:28', '2025-11-07 17:13:40');

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
(1, 'app_name', 'WMS', 'Nama singkat aplikasi', 'false', '2025-11-06 13:35:17'),
(2, 'app_fullname', 'Warehouse Management System', 'Nama lengkap aplikasi', 'false', '2025-11-06 13:35:17'),
(3, 'app_logo', 'uploads/ac663cfb277e6cf8a4bbe04e5851ea0a.png', 'Path logo aplikasi', 'true', '2025-11-07 10:34:24'),
(4, 'app_footer_text', 'All Rights Reserved', 'Teks footer aplikasi', 'false', '2025-11-07 09:39:07'),
(5, 'api_base_url', 'http://200.192.100.214', 'Base URL utama API eksternal', 'false', '2025-11-06 13:38:19'),
(6, 'api_timeout', '30', 'Timeout API (detik)', 'false', '2025-11-06 13:38:19'),
(7, 'app_logo_blue', 'uploads/d1433fc11025a5f363a2854a6b6feadd.png', 'Path logo aplikasi', 'true', '2025-11-11 14:21:16'),
(8, 'app_pt_name', 'PT. Usaha Jayamas Bhakti', 'Nama perusahaan aplikasi', 'false', '2025-11-06 13:35:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`id_api`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`),
  ADD UNIQUE KEY `nama_pengaturan_unique` (`nama_pengaturan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api`
--
ALTER TABLE `api`
  MODIFY `id_api` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
