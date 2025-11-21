-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2025 at 09:15 AM
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
-- Database: `stmsmart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_05_023009_create_tbl_jurusan_table', 1),
(5, '2025_10_05_023016_create_tbl_kelas_table', 1),
(6, '2025_10_05_023021_create_tbl_siswa_table', 1),
(7, '2025_10_05_023028_create_tbl_wali_kelas_table', 1),
(8, '2025_10_05_023035_create_tbl_user_table', 1),
(9, '2025_10_05_023437_create_tbl_absensi_table', 1),
(10, '2025_10_05_023444_create_tbl_settings_table', 1),
(11, '2025_10_06_075753_add_photo_columns_to_tbl_absensi_table', 1),
(12, '2025_10_17_091941_add_no_hp_ortu_to_tbl_siswa_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8422Es8HU9kEYBOE6U9kYWCVw3Ouc85jnTdlwlTK', 133, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmpNNTVueklGQnhMNWZlS2t2SkF3V1VyNVlsUFRmZDg1Z2JDRE4yWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly80NWZkYWFiNzIyYzUubmdyb2stZnJlZS5hcHAvc2lzd2EvYWJzZW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMzM7fQ==', 1760685252),
('cyeEDT3cSUgfgZ6XltDjludYs79eVlJhpEsaT7G7', NULL, '127.0.0.1', 'WhatsApp/2.2539.2 W', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVZWV3JlOTdBRG1PNm53aVFwSFR0bkYzY3BtOEs1TmY3ZjhDQ21XZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly80NWZkYWFiNzIyYzUubmdyb2stZnJlZS5hcHAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1760684483),
('EGVxoXgP2e3ViCvxoT5YrUDSXLdCisA2N3SB02fL', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSUpwY3gza0lzNWJhc2s4ZWFzUzdZNUcwenRtcXkzd2tqSE5VU2k3bCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Npc3dhIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zaXN3YT9wYWdlPTQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1760684809),
('FwVf9c8UEeWB1VIlSryPb5KAmy7pkiWQKxV88W79', 156, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 12; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.7339.208 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiamFaT01id2ZjYTNZTGxwd0djR0FTNVN4ZmxIZjVncjlVbnZJRElXMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly80NWZkYWFiNzIyYzUubmdyb2stZnJlZS5hcHAvYXBpL3NldHRpbmdzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTU2O30=', 1760684814),
('GupIEbYthVjuvzxPQhD20bOIeVJIQnCjfoIqWf6K', NULL, '127.0.0.1', 'TelegramBot (like TwitterBot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFlUVWhlOGVlamgyTGhQejM0WThrV1BBTVBQUEpHTlN1ZjhCSkJvbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly80NWZkYWFiNzIyYzUubmdyb2stZnJlZS5hcHAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1760684505),
('hgKK7HvqzSV25qggN8pMYLuJp93bvDkWaU1LkV4p', 133, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoia1I2MnRmZEg0QUNuMWszaUdRcnhrbkxJTHNYbFVlb3lXeHN4dlhzWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaXN3YS9hYnNlbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvc2V0dGluZ3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMzM7fQ==', 1760683240),
('kxkEF953sYTdCmtInFqyxkjrs9oFGpArCmW6RuL8', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiY0xkV3IyQXlGZE9hRWZESm9nTDJNb3Z4ZlhtYXVpTjhtSFB3eUpiQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3NldHRpbmdzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zaXN3YSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1760674138);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` bigint(20) UNSIGNED NOT NULL,
  `id_siswa` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_pulang` time DEFAULT NULL,
  `longitude_masuk` decimal(10,7) DEFAULT NULL,
  `latitude_masuk` decimal(10,7) DEFAULT NULL,
  `foto_masuk` varchar(255) DEFAULT NULL,
  `longitude_pulang` decimal(10,7) DEFAULT NULL,
  `latitude_pulang` decimal(10,7) DEFAULT NULL,
  `foto_pulang` varchar(255) DEFAULT NULL,
  `status_masuk` varchar(50) DEFAULT NULL,
  `status_pulang` enum('pulang','tidak_pulang') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `id_siswa`, `tanggal`, `waktu_masuk`, `waktu_pulang`, `longitude_masuk`, `latitude_masuk`, `foto_masuk`, `longitude_pulang`, `latitude_pulang`, `foto_pulang`, `status_masuk`, `status_pulang`, `created_at`, `updated_at`) VALUES
(38, 135, '2025-10-17', '14:13:20', '14:14:10', 108.2298364, -7.3578570, 'attendance_photos/1760685200_135_masuk.jpg', 108.2298332, -7.3578856, 'attendance_photos/1760685250_135_pulang.jpg', 'terlambat', 'pulang', '2025-10-17 07:13:20', '2025-10-17 07:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id_jurusan` bigint(20) UNSIGNED NOT NULL,
  `nama_jurusan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id_jurusan`, `nama_jurusan`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Komputer dan Jaringan', '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(2, 'Rekayasa Perangkat Lunak', '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(3, 'Multimedia', '2025-10-16 11:30:50', '2025-10-16 11:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `id_jurusan` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `id_jurusan`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(1, 1, 'X TKJ 1', '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(2, 2, 'XI RPL 1', '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(3, 3, 'XII MM 1', '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(4, 2, 'XII RPL', '2025-10-16 11:30:50', '2025-10-16 13:34:39'),
(5, 2, 'X RPL', '2025-10-16 11:30:50', '2025-10-16 11:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id_setting` bigint(20) UNSIGNED NOT NULL,
  `nama_setting` varchar(100) NOT NULL,
  `nilai_setting` text NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id_setting`, `nama_setting`, `nilai_setting`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'school_latitude', '-7.357950', 'Latitude sekolah', '2025-10-16 11:30:56', '2025-10-16 11:42:52'),
(2, 'school_longitude', '108.229187', 'Longitude sekolah', '2025-10-16 11:30:56', '2025-10-16 11:42:52'),
(3, 'attendance_radius', '100', 'Radius absensi dalam meter', '2025-10-16 11:30:56', '2025-10-17 02:13:12'),
(4, 'jam_masuk', '05:00', 'Jam masuk sekolah', '2025-10-16 11:30:56', '2025-10-16 23:03:22'),
(5, 'jam_terlambat', '06:31', 'Batas waktu masuk sebelum dianggap terlambat', '2025-10-16 11:30:56', '2025-10-17 02:03:17'),
(6, 'jam_pulang', '14:00', 'Jam pulang sekolah', '2025-10-16 11:30:56', '2025-10-16 23:03:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id_siswa` bigint(20) UNSIGNED NOT NULL,
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `nisn` varchar(225) DEFAULT NULL,
  `card_code` bigint(20) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id_siswa`, `id_kelas`, `nama`, `jenis_kelamin`, `nisn`, `card_code`, `no_hp_ortu`, `created_at`, `updated_at`) VALUES
(131, 4, 'ADIL KHOERUSSABIL', 'L', '12326094', 86028252, NULL, '2025-10-16 13:57:29', '2025-10-16 13:57:29'),
(132, 4, 'ADRIAN FIRSYAH MAULANA', 'L', '12326095', 81186483, NULL, '2025-10-16 13:57:30', '2025-10-16 13:57:30'),
(133, 4, 'ALVARO PRASETYA DJAYA SUHARYANTO', 'L', '12326096', 3086191262, NULL, '2025-10-16 13:57:30', '2025-10-16 13:57:30'),
(134, 4, 'ALYA ALAWIYAH', 'P', '12326097', 75640103, NULL, '2025-10-16 13:57:31', '2025-10-16 13:57:31'),
(135, 4, 'ARIEF MAULANA RIZKI', 'L', '12326098', 76548998, '081990706575', '2025-10-16 13:57:31', '2025-10-17 02:38:55'),
(136, 4, 'BINTANG CAESAR PRATAMA PUTRA', 'L', '12326099', 82543870, '085173312550', '2025-10-16 13:57:32', '2025-10-17 04:01:09'),
(137, 4, 'DAFFA ABIYYU NASRULLOH', 'L', '12326100', 81287438, NULL, '2025-10-16 13:57:32', '2025-10-16 13:57:32'),
(138, 4, 'ESA PUTRI PAMUNGKAS', 'P', '12326101', 72535720, NULL, '2025-10-16 13:57:33', '2025-10-16 13:57:33'),
(139, 4, 'FAIZ MAULANA', 'L', '12326102', 77547273, NULL, '2025-10-16 13:57:33', '2025-10-16 13:57:33'),
(140, 4, 'FAUZAN RADIF SYAHDAN', 'L', '12326103', 84244568, NULL, '2025-10-16 13:57:33', '2025-10-16 13:57:33'),
(141, 4, 'FITRI NURAINI', 'P', '12326104', 74860194, NULL, '2025-10-16 13:57:34', '2025-10-16 13:57:34'),
(142, 4, 'HARIS ABDUL AZIZ', 'L', '12326105', 85974177, NULL, '2025-10-16 13:57:34', '2025-10-16 13:57:34'),
(143, 4, 'HAYLA MUFLIHAH', 'P', '12326106', 78840420, NULL, '2025-10-16 13:57:35', '2025-10-16 13:57:35'),
(144, 4, 'HELIANDRA AUDREY ATHA FAHREZI', 'L', '12326107', 83593200, NULL, '2025-10-16 13:57:35', '2025-10-16 13:57:35'),
(145, 4, 'HERLINA WULANDARI', 'P', '12326108', 84391387, NULL, '2025-10-16 13:57:36', '2025-10-16 13:57:36'),
(146, 4, 'JIHAN KHOERUNNISA', 'P', '12326109', 71401148, NULL, '2025-10-16 13:57:36', '2025-10-16 13:57:36'),
(147, 4, 'LARAS AYU NOVIANTI', 'P', '12326110', 74068644, NULL, '2025-10-16 13:57:37', '2025-10-16 13:57:37'),
(148, 4, 'MAULINDA NUR APRILIANTI', 'P', '12326111', 71031178, NULL, '2025-10-16 13:57:37', '2025-10-16 13:57:37'),
(149, 4, 'MAUZA ZULFA AULIA', 'P', '12326112', 82188285, NULL, '2025-10-16 13:57:38', '2025-10-16 13:57:38'),
(150, 4, 'MOCHAMMAD BALQIS RAMADHAN', 'L', '12326113', 76644212, NULL, '2025-10-16 13:57:38', '2025-10-16 13:57:38'),
(151, 4, 'MORRIS MALONDA', 'L', '12326114', 73232194, NULL, '2025-10-16 13:57:39', '2025-10-16 13:57:39'),
(152, 4, 'MUHAMMAD AMIN AL FARUQ', 'L', '12326115', 78532572, NULL, '2025-10-16 13:57:39', '2025-10-16 13:57:39'),
(153, 4, 'MUHAMMAD IRSYAD MUBAROK', 'L', '12326116', 62132055, NULL, '2025-10-16 13:57:40', '2025-10-16 13:57:40'),
(154, 4, 'MUHAMMAD RIDWAN MAULANA', 'L', '12326117', 89622016, NULL, '2025-10-16 13:57:40', '2025-10-16 13:57:40'),
(155, 4, 'MUHAMMAD TEGUH ALLAMSYAH', 'L', '12326118', 86608769, NULL, '2025-10-16 13:57:41', '2025-10-16 13:57:41'),
(156, 4, 'NAILA AKMALA', 'P', '12326119', 86374442, NULL, '2025-10-16 13:57:41', '2025-10-16 13:57:41'),
(157, 4, 'RAKA PUTRA UTAMA', 'L', '12326120', 84919337, NULL, '2025-10-16 13:57:41', '2025-10-16 13:57:41'),
(158, 4, 'RANGGA ALEXSANDRA', 'L', '12326121', 79162571, '085722133958', '2025-10-16 13:57:42', '2025-10-17 07:02:58'),
(159, 4, 'RATIH RUSMIATI', 'P', '12326122', 72179928, NULL, '2025-10-16 13:57:42', '2025-10-16 13:57:42'),
(160, 4, 'RAZWA KASTA HAMDANI', 'L', '12326123', 86849638, NULL, '2025-10-16 13:57:43', '2025-10-16 13:57:43'),
(161, 4, 'RISNA ANJANI PATMAWATI', 'P', '12326124', 86518172, NULL, '2025-10-16 13:57:43', '2025-10-16 13:57:43'),
(162, 4, 'RIZKY RIDWAN NUGRAHA', 'L', '12326125', 88473464, NULL, '2025-10-16 13:57:44', '2025-10-16 13:57:44'),
(163, 4, 'SATRIA ARYA PRADITYA', 'L', '12326126', 81040495, NULL, '2025-10-16 13:57:44', '2025-10-16 13:57:44'),
(164, 4, 'TASYA PAUZIAH', 'P', '12326127', 73010478, NULL, '2025-10-16 13:57:45', '2025-10-16 13:57:45'),
(165, 4, 'WULAN PURNAMA', 'P', '12326128', 73034157, NULL, '2025-10-16 13:57:45', '2025-10-16 13:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_wali_kelas` bigint(20) UNSIGNED DEFAULT NULL,
  `id_siswa` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','guru','operator','siswa','ketua') DEFAULT NULL,
  `card_code` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `id_wali_kelas`, `id_siswa`, `username`, `password`, `role`, `card_code`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'admin', '$2y$12$t9UJp9wtdK8pOczVF5Wx/.8STqANWEui8FM2hM5mfTjzqk4pQQVe.', 'admin', 1001, '2025-10-16 11:30:56', '2025-10-16 11:30:56'),
(2, NULL, NULL, 'superadmin', '$2y$12$.E8MXfhjukSzskPCf/1Tjur3Th.qn6e0qcoeiOekhYu6oBKf9038a', 'admin', 1002, '2025-10-16 11:30:56', '2025-10-16 11:30:56'),
(3, NULL, NULL, 'operator', '$2y$12$HX8Q19x7EXXEVdqmnd0kdeukSYEJcHtQ8bXeti9PzpmLNJcFxmgYS', 'operator', 2001, '2025-10-16 11:30:56', '2025-10-16 11:30:56'),
(4, 2, NULL, 'guru1', '$2y$12$rPlj6WSa1FL9uwnmD1EO1e8TzgwIEXpbNVmBFM5AfYX1DqH7j0OzS', 'guru', 3001, '2025-10-16 11:30:56', '2025-10-16 11:30:56'),
(5, 11, NULL, 'guru2', '$2y$12$uVBwrjuxPd3RBONokDIfjOYKQx6cTMtOeERkHSRGf9qBVlGzT22.2', 'guru', 3002, '2025-10-16 11:30:56', '2025-10-16 11:30:56'),
(6, 12, NULL, 'guru3', '$2y$12$X0/xgF62y2cs9Vk8axmr5u7ISrBnXD3KdnABRw.kwdMRPJUMhb71m', 'guru', 3003, '2025-10-16 11:30:56', '2025-10-16 11:30:56'),
(129, NULL, 131, 'ADIL KHOERUSSABIL', '$2y$12$c2HXcprGxthNEUc6jkp5H.vSmjOXwKRgV04kOih3vT4QLIg89ZYRi', 'siswa', 86028252, '2025-10-16 13:57:30', '2025-10-16 13:57:30'),
(130, NULL, 132, 'ADRIAN FIRSYAH MAULANA', '$2y$12$kYPrYwr/6keQ6Pca3.RnKejHBE/y9z2LLRiIFwfpGmq.Qh3Y0gBPy', 'siswa', 81186483, '2025-10-16 13:57:30', '2025-10-16 13:57:30'),
(131, NULL, 133, 'ALVARO PRASETYA DJAYA SUHARYANTO', '$2y$12$8EIgSkXW1JR.Ls/x2TD7RegxhVcCIRPIJqgW5Lpjll1oiLdrtiRii', 'siswa', 3086191262, '2025-10-16 13:57:31', '2025-10-16 13:57:31'),
(132, NULL, 134, 'ALYA ALAWIYAH', '$2y$12$OWv3vYQLUoA28s/Jtg7X..12gFRmk/GVeasP22Oet9k0jOPAoXrL6', 'siswa', 75640103, '2025-10-16 13:57:31', '2025-10-16 13:57:31'),
(133, NULL, 135, 'ARIEF MAULANA RIZKI', '$2y$12$ECo0/YrQVsjzaThq7HkaU.uHsIcD9wwNwub.R3ZbUf64aKx82LLdG', 'siswa', 76548998, '2025-10-16 13:57:32', '2025-10-16 13:57:32'),
(134, NULL, 136, 'BINTANG CAESAR PRATAMA PUTRA', '$2y$12$ber1SXgU5S54950h9uKxGOc0uHsh7jPy0PJMVH/3SaeN9MkGn3lJq', 'siswa', 82543870, '2025-10-16 13:57:32', '2025-10-16 13:57:32'),
(135, NULL, 137, 'DAFFA ABIYYU NASRULLOH', '$2y$12$eXxfqeSg/4xsStQoO5Ptb.0rmk0VZ1aat7x2PuPWXzLg1sO5wBAaS', 'siswa', 81287438, '2025-10-16 13:57:33', '2025-10-16 13:57:33'),
(136, NULL, 138, 'ESA PUTRI PAMUNGKAS', '$2y$12$XqtLXZNm3n3P0iZ2t1BRveP0bCrO5Z2fJql2qwHfel5QtlgfyoAIa', 'siswa', 72535720, '2025-10-16 13:57:33', '2025-10-16 13:57:33'),
(137, NULL, 139, 'FAIZ MAULANA', '$2y$12$JZbdBxT3j0yIllXkZyS4aOqAoU94AjqILkmN.fPAfR9lEzoyf7pdW', 'siswa', 77547273, '2025-10-16 13:57:33', '2025-10-16 13:57:33'),
(138, NULL, 140, 'FAUZAN RADIF SYAHDAN', '$2y$12$S3jo9QhVxxsQO3YJvNvfxumlKgrqQF1.rHX187yUQ27GUDbeWVxg.', 'siswa', 84244568, '2025-10-16 13:57:34', '2025-10-16 13:57:34'),
(139, NULL, 141, 'FITRI NURAINI', '$2y$12$MQypXyFtJYwgfMgWosdQDuQGFxApIf7m8PxXsDAXi5xKFBcIJUl8u', 'siswa', 74860194, '2025-10-16 13:57:34', '2025-10-16 13:57:34'),
(140, NULL, 142, 'HARIS ABDUL AZIZ', '$2y$12$Wkfr86X0WriamjDa.GXpjeu0xKRyJHcpOJXhDT9Bag1iAY3wlW3RC', 'siswa', 85974177, '2025-10-16 13:57:35', '2025-10-16 13:57:35'),
(141, NULL, 143, 'HAYLA MUFLIHAH', '$2y$12$xtUxzaMQ590H2UC/PYARJepmzJoZSKET1z7w3K/ANFoSc2frymZI2', 'siswa', 78840420, '2025-10-16 13:57:35', '2025-10-16 13:57:35'),
(142, NULL, 144, 'HELIANDRA AUDREY ATHA FAHREZI', '$2y$12$PetVgQRhG5UY763Tj9o00uIFIViD67yT0upRCzf/kW6k86m8lSb6K', 'siswa', 83593200, '2025-10-16 13:57:36', '2025-10-16 13:57:36'),
(143, NULL, 145, 'HERLINA WULANDARI', '$2y$12$/jsxf1UrfvKxx8U3n6Avbu8VX/o.k3guZ.nnm4vvyF.xSG75jo6PG', 'siswa', 84391387, '2025-10-16 13:57:36', '2025-10-16 13:57:36'),
(144, NULL, 146, 'JIHAN KHOERUNNISA', '$2y$12$2ugTVJL3i./yiUy50zQj8epO4ATvlDClJwCLwU7OLxiogb6vFlSj2', 'siswa', 71401148, '2025-10-16 13:57:37', '2025-10-16 13:57:37'),
(145, NULL, 147, 'LARAS AYU NOVIANTI', '$2y$12$rE/HOaN8KSGmwXG7zffpfO6hEq5o29v5huwVT1YjlhsMasKazxADm', 'siswa', 74068644, '2025-10-16 13:57:37', '2025-10-16 13:57:37'),
(146, NULL, 148, 'MAULINDA NUR APRILIANTI', '$2y$12$xb1YRMVFj6tsmHW9Be93keI/vovY1JuV0q/yd/PXBkW5SJ1QT1Ray', 'siswa', 71031178, '2025-10-16 13:57:38', '2025-10-16 13:57:38'),
(147, NULL, 149, 'MAUZA ZULFA AULIA', '$2y$12$a0zjZtjGIGGA/GRo.cL73u4zXtuETAUeOC2zrM7t0GzMTJLUM51kW', 'siswa', 82188285, '2025-10-16 13:57:38', '2025-10-16 13:57:38'),
(148, NULL, 150, 'MOCHAMMAD BALQIS RAMADHAN', '$2y$12$izAKbxEVKMEYIKb4Yzr7Pu9s42IFkYiHMgu0HJZB0rM4I4mPjMFnG', 'siswa', 76644212, '2025-10-16 13:57:39', '2025-10-16 13:57:39'),
(149, NULL, 151, 'MORRIS MALONDA', '$2y$12$0rLj2tOoGtOWx6imjRx64.jXURSpTsxLk7EpgqoK/ZGNE9.xEkmza', 'siswa', 73232194, '2025-10-16 13:57:39', '2025-10-16 13:57:39'),
(150, NULL, 152, 'MUHAMMAD AMIN AL FARUQ', '$2y$12$1YMeViDPPBoNamq6FUzvMOjX3vN7fSFiujFEctrrVlbzB0sQikfJ6', 'siswa', 78532572, '2025-10-16 13:57:40', '2025-10-16 13:57:40'),
(151, NULL, 153, 'MUHAMMAD IRSYAD MUBAROK', '$2y$12$1SMwY1PusFCVLk329ztP/u1uq/DO1HjLzX7oVQAYAePtcLTDiMhhi', 'siswa', 62132055, '2025-10-16 13:57:40', '2025-10-16 13:57:40'),
(152, NULL, 154, 'MUHAMMAD RIDWAN MAULANA', '$2y$12$YGRmLuWhkP98GKSTdcZz/.wm89BYVNTdjpw8o5ngNTGgRgjS9A62S', 'siswa', 89622016, '2025-10-16 13:57:40', '2025-10-16 13:57:40'),
(153, NULL, 155, 'MUHAMMAD TEGUH ALLAMSYAH', '$2y$12$FT3qJLfi9uX9SsD3jbs69.O72l.mXmO8rDSwzE6W.hPPKA8Fqt.ni', 'siswa', 86608769, '2025-10-16 13:57:41', '2025-10-16 13:57:41'),
(154, NULL, 156, 'NAILA AKMALA', '$2y$12$7kSobWOjTULjLATypzrfQ.lSnEWQSasZTAvXGqAQgLfRaX16zHEGW', 'siswa', 86374442, '2025-10-16 13:57:41', '2025-10-16 13:57:41'),
(155, NULL, 157, 'RAKA PUTRA UTAMA', '$2y$12$ENLv4g9IAVsCx3sHWVWgYuGRI/0XBSGfRINNyR7KMBNdT5RJyQ71W', 'siswa', 84919337, '2025-10-16 13:57:42', '2025-10-16 13:57:42'),
(156, NULL, 158, 'RANGGA ALEXSANDRA', '$2y$12$9hks3Co8uXjr.VazjBhLjef65pIgDw7SVtiAOeN5kL5tkqwxhB6Kq', 'siswa', 79162571, '2025-10-16 13:57:42', '2025-10-16 13:57:42'),
(157, NULL, 159, 'RATIH RUSMIATI', '$2y$12$pfRtMzZ7oifvTCBytoNzh..VZarOn.X1nhEVnHi35575lA2SfzK9.', 'siswa', 72179928, '2025-10-16 13:57:43', '2025-10-16 13:57:43'),
(158, NULL, 160, 'RAZWA KASTA HAMDANI', '$2y$12$fkaHTUbNyJBHucf8oL8JQupof9lJrQWtLX6V0IZFFIkbauffB8Fi.', 'siswa', 86849638, '2025-10-16 13:57:43', '2025-10-16 13:57:43'),
(159, NULL, 161, 'RISNA ANJANI PATMAWATI', '$2y$12$SHmI4/nYpd9hVE2jpuxI5eYJQwjSCpc5uzXAVtjjmWondukBfaFle', 'siswa', 86518172, '2025-10-16 13:57:44', '2025-10-16 13:57:44'),
(160, NULL, 162, 'RIZKY RIDWAN NUGRAHA', '$2y$12$m4p8bIa2jxNe8b0Iei/sb.GbIKkg3vR3bB94BZcLInECzZ3KnSMGi', 'siswa', 88473464, '2025-10-16 13:57:44', '2025-10-16 13:57:44'),
(161, NULL, 163, 'SATRIA ARYA PRADITYA', '$2y$12$X6/XZ4lTZlsN3yZ3LQdXfuugsgTNTaTfLB2.3078FkqDZ0ifxugR6', 'siswa', 81040495, '2025-10-16 13:57:45', '2025-10-16 13:57:45'),
(162, NULL, 164, 'TASYA PAUZIAH', '$2y$12$4U5zF3dXAvX85wLFQT94UeNTT1yexNG37YAwg4WiGAAx//vwdT6Rq', 'siswa', 73010478, '2025-10-16 13:57:45', '2025-10-16 13:57:45'),
(163, NULL, 165, 'WULAN PURNAMA', '$2y$12$0ADpd/y.WUVRer9dx71tW.z1TAJhxeukiem20veTJ7RjqXt2Qo.MO', 'siswa', 73034157, '2025-10-16 13:57:46', '2025-10-16 13:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wali_kelas`
--

CREATE TABLE `tbl_wali_kelas` (
  `id_wali_kelas` bigint(20) UNSIGNED NOT NULL,
  `id_kelas` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nip` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_wali_kelas`
--

INSERT INTO `tbl_wali_kelas` (`id_wali_kelas`, `id_kelas`, `nama`, `nip`, `created_at`, `updated_at`) VALUES
(2, 1, 'Siti Aminah Nugroho', 1987654322, '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(11, 5, 'dr zaki', 123, '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(12, 3, 'jhjhj', 9898, '2025-10-16 11:30:50', '2025-10-16 11:30:50'),
(13, 4, 'Ai Siti Hasanah, S.Pd.', NULL, '2025-10-16 13:36:43', '2025-10-16 13:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `tbl_absensi_id_siswa_foreign` (`id_siswa`);

--
-- Indexes for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `tbl_kelas_id_jurusan_foreign` (`id_jurusan`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id_setting`),
  ADD UNIQUE KEY `tbl_settings_nama_setting_unique` (`nama_setting`);

--
-- Indexes for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD KEY `tbl_siswa_id_kelas_foreign` (`id_kelas`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `tbl_user_id_wali_kelas_foreign` (`id_wali_kelas`),
  ADD KEY `tbl_user_id_siswa_foreign` (`id_siswa`);

--
-- Indexes for table `tbl_wali_kelas`
--
ALTER TABLE `tbl_wali_kelas`
  ADD PRIMARY KEY (`id_wali_kelas`),
  ADD KEY `tbl_wali_kelas_id_kelas_foreign` (`id_kelas`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `id_jurusan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id_setting` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id_siswa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `tbl_wali_kelas`
--
ALTER TABLE `tbl_wali_kelas`
  MODIFY `id_wali_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD CONSTRAINT `tbl_absensi_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD CONSTRAINT `tbl_kelas_id_jurusan_foreign` FOREIGN KEY (`id_jurusan`) REFERENCES `tbl_jurusan` (`id_jurusan`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD CONSTRAINT `tbl_siswa_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `tbl_user_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_user_id_wali_kelas_foreign` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tbl_wali_kelas` (`id_wali_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_wali_kelas`
--
ALTER TABLE `tbl_wali_kelas`
  ADD CONSTRAINT `tbl_wali_kelas_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
