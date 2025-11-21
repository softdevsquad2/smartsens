-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2025 at 02:46 AM
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
-- Table structure for table `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'Teknik Komputer dan Jaringan'),
(2, 'Rekayasa Perangkat Lunak'),
(3, 'Multimedia');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `nama_kelas` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `id_jurusan`, `nama_kelas`) VALUES
(1, 1, 'X TKJ 1'),
(2, 2, 'XI RPL 1'),
(3, 3, 'XII MM 1'),
(4, 2, 'XII RPL '),
(5, 2, 'X RPL');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_konfirmasi_sholat`
--

CREATE TABLE `tbl_konfirmasi_sholat` (
  `id_konfirmasi` int(11) NOT NULL,
  `id_wali_kelas` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `sudah_dilihat` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_konfirmasi_sholat`
--

INSERT INTO `tbl_konfirmasi_sholat` (`id_konfirmasi`, `id_wali_kelas`, `tanggal`, `waktu`, `sudah_dilihat`) VALUES
(2, 2, '2025-08-31', '16:05:00', 1),
(5, 11, '2025-09-27', '15:13:43', 1),
(6, 11, '0000-00-00', '20:27:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sholat`
--

CREATE TABLE `tbl_sholat` (
  `id_sholat` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `card_code` bigint(100) DEFAULT NULL,
  `masuk` time DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `dzuhur_masuk` time DEFAULT NULL,
  `dzuhur_keluar` time DEFAULT NULL,
  `ashar_masuk` time DEFAULT NULL,
  `ashar_keluar` time DEFAULT NULL,
  `status_dzuhur` enum('sholat','haid','sakit','tidak sekolah') DEFAULT NULL,
  `status_ashar` enum('sholat','haid','sakit','tidak sekolah') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sholat`
--

INSERT INTO `tbl_sholat` (`id_sholat`, `id_siswa`, `card_code`, `masuk`, `tanggal`, `dzuhur_masuk`, `dzuhur_keluar`, `ashar_masuk`, `ashar_keluar`, `status_dzuhur`, `status_ashar`) VALUES
(1, 1, 200001, '07:00:00', '2025-08-31', '12:05:00', '12:20:00', '15:05:00', '15:20:00', 'sholat', 'sholat'),
(2, 2, 200002, '07:10:00', '2025-08-31', '12:15:00', '12:25:00', '15:10:00', '15:25:00', 'sholat', 'sholat'),
(4, 1, 519654371, '06:19:40', '2025-09-04', '06:19:40', '06:19:49', NULL, NULL, 'sholat', NULL),
(5, 1, 519654371, '06:21:10', '2025-09-06', '06:21:10', '06:21:11', NULL, NULL, 'sholat', NULL),
(6, 13, 504090339, '18:41:58', '2025-09-28', NULL, NULL, '18:41:58', '18:41:58', 'haid', 'haid');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `nisn` bigint(20) DEFAULT NULL,
  `card_code` bigint(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id_siswa`, `id_kelas`, `nama`, `jenis_kelamin`, `nisn`, `card_code`) VALUES
(1, 5, 'Andi Pratama B', 'L', 1234567890, 519654371),
(2, 1, 'Rina Marlina', 'L', 1234567891, 200002),
(4, 3, 'Sari Wulandari', 'L', 1234567893, 200004),
(13, 5, 'Arfan Fakhrudin', 'L', 1234567, 504090339),
(14, 5, 'Keyza Zaki Arkana', 'L', 3847384738, 346376473),
(15, 5, 'Muhammad Afdhal Rasidt', 'P', 38473847, 504090339),
(16, 5, 'Muhamamd Farhan', 'L', 3473874, 3847384),
(17, 5, 'sdjskdjs', 'P', 3948398, 39849384);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `id_wali_kelas` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','guru','operator','siswa','ketua') DEFAULT NULL,
  `card_code` bigint(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `id_wali_kelas`, `id_siswa`, `username`, `password`, `role`, `card_code`) VALUES
(21, NULL, NULL, 'k', '$2y$10$H3MloUQwMln3.nplJO2VAupkXloc9bpb8TLDPq.1mevli0L7ndMHi', 'admin', 1),
(22, NULL, NULL, 'o', '$2y$10$YDDXEMNqLft3wk8ToIYlfug6EeNYnKREJytTiC7CtMcxU2fpOp1mO', 'operator', 1),
(23, 11, NULL, 'zaki', '$2y$10$cS4gS1Qv7p3BtPysbM.NheFMOhOwBjWdZGEyHenhO8cO7ivI6bTre', 'guru', 123),
(27, NULL, NULL, 'admin', '$2y$10$KzMfkGMsgl7IzOCTw0eTcO.wHKtOMa7EpOPvcv1.9wiQZ80UetsLC', 'admin', 123),
(28, NULL, 13, 'fan', '$2y$10$avXOkjJWffcujPHO3uq3pevZoTFUoogtTDSadFrIVi7b4JM1FLq2K', 'ketua', NULL),
(29, NULL, 14, '3847384738', '$2y$10$VF7aiVdldmi8zSMJs4FHxu/418f1pem8BDuZGunRGW0sfTwXJOfH.', 'siswa', 346376473),
(30, NULL, 15, '38473847', '$2y$10$I3wJ/i4Xu.2lHo4qN6LQR.gf3SnUvUWdSxWiU3AZBnnL/FbmzJY9W', 'siswa', 38473847),
(31, NULL, 16, '3473874', '$2y$10$bDf7tdIW0vsFpJH0X8S0nOrTHOnTNFlwN.QbSS6qruLCo/5PvzmrG', 'siswa', 3847384),
(32, NULL, 17, '3948398', '$2y$10$VDf4YKMehVVYKhGx6zT5G.VwDw387.Nt5MChsDMoJBWpXUT7aXs/e', 'siswa', 39849384),
(33, 12, NULL, '9898', '$2y$10$dBO2D0RnEIqA2bjuhVqUm.RZoD96SnBNicG7qJG6G2vOK1dT3kHDS', 'guru', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waktu_sholat`
--

CREATE TABLE `tbl_waktu_sholat` (
  `id_waktu_sholat` int(11) NOT NULL,
  `dzuhur` time DEFAULT NULL,
  `akhir_dzuhur` time DEFAULT NULL,
  `ashar` time DEFAULT NULL,
  `akhir_ashar` time DEFAULT NULL,
  `selang_dzuhur` int(11) DEFAULT NULL,
  `selang_ashar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_waktu_sholat`
--

INSERT INTO `tbl_waktu_sholat` (`id_waktu_sholat`, `dzuhur`, `akhir_dzuhur`, `ashar`, `akhir_ashar`, `selang_dzuhur`, `selang_ashar`) VALUES
(1, '10:27:00', '12:30:00', '18:00:00', '19:30:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wali_kelas`
--

CREATE TABLE `tbl_wali_kelas` (
  `id_wali_kelas` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nip` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_wali_kelas`
--

INSERT INTO `tbl_wali_kelas` (`id_wali_kelas`, `id_kelas`, `nama`, `nip`) VALUES
(2, 1, 'Siti Aminah Nugroho', 1987654322),
(11, 5, 'dr zaki', 123),
(12, 3, 'jhjhj', 9898);

--
-- Indexes for dumped tables
--

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
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indexes for table `tbl_konfirmasi_sholat`
--
ALTER TABLE `tbl_konfirmasi_sholat`
  ADD PRIMARY KEY (`id_konfirmasi`),
  ADD KEY `id_wali_kelas` (`id_wali_kelas`);

--
-- Indexes for table `tbl_sholat`
--
ALTER TABLE `tbl_sholat`
  ADD PRIMARY KEY (`id_sholat`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_wali_kelas` (`id_wali_kelas`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `tbl_waktu_sholat`
--
ALTER TABLE `tbl_waktu_sholat`
  ADD PRIMARY KEY (`id_waktu_sholat`);

--
-- Indexes for table `tbl_wali_kelas`
--
ALTER TABLE `tbl_wali_kelas`
  ADD PRIMARY KEY (`id_wali_kelas`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_konfirmasi_sholat`
--
ALTER TABLE `tbl_konfirmasi_sholat`
  MODIFY `id_konfirmasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_sholat`
--
ALTER TABLE `tbl_sholat`
  MODIFY `id_sholat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_waktu_sholat`
--
ALTER TABLE `tbl_waktu_sholat`
  MODIFY `id_waktu_sholat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_wali_kelas`
--
ALTER TABLE `tbl_wali_kelas`
  MODIFY `id_wali_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD CONSTRAINT `tbl_kelas_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `tbl_jurusan` (`id_jurusan`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_konfirmasi_sholat`
--
ALTER TABLE `tbl_konfirmasi_sholat`
  ADD CONSTRAINT `tbl_konfirmasi_sholat_ibfk_1` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tbl_wali_kelas` (`id_wali_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_sholat`
--
ALTER TABLE `tbl_sholat`
  ADD CONSTRAINT `tbl_sholat_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD CONSTRAINT `tbl_siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `fk_user_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tbl_wali_kelas` (`id_wali_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_wali_kelas`
--
ALTER TABLE `tbl_wali_kelas`
  ADD CONSTRAINT `tbl_wali_kelas_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tbl_kelas` (`id_kelas`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
