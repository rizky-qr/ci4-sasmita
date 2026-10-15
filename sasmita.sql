-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 15, 2026 at 09:25 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sasmita`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_logs`
--

CREATE TABLE `approval_logs` (
  `id` char(36) NOT NULL,
  `pengajuan_id` char(36) NOT NULL,
  `approved_by_id` char(36) DEFAULT NULL,
  `approved_by_nama` varchar(255) DEFAULT NULL,
  `role` enum('MAHASISWA','STAF_TU_PRODI','KAPRODI','DEKAN','ADMIN') NOT NULL,
  `status` varchar(50) NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `pengajuan_id` char(36) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id` char(36) NOT NULL,
  `nomor_surat` varchar(100) DEFAULT NULL,
  `verify_token` varchar(64) DEFAULT NULL,
  `mahasiswa_id` char(36) NOT NULL,
  `mahasiswa_nama` varchar(255) NOT NULL,
  `mahasiswa_nim` varchar(50) NOT NULL,
  `mahasiswa_program` varchar(100) NOT NULL,
  `jenis_surat` enum('KETERANGAN_AKTIF','TIDAK_ADA_PERKULIAHAN','IZIN_PENELITIAN','PERMOHONAN_PLP_MAGANG','PERMOHONAN_OBSERVASI','REKOMENDASI','KETERANGAN_LULUS') NOT NULL,
  `status` enum('DRAFT','MENUNGGU_TU','MENUNGGU_KAPRODI','MENUNGGU_DEKAN','DISETUJUI','BUTUH_REVISI','DITOLAK','SELESAI') NOT NULL DEFAULT 'MENUNGGU_TU',
  `keperluan` text DEFAULT NULL,
  `catatan_terakhir` text DEFAULT NULL,
  `tanggal_disetujui` datetime DEFAULT NULL,
  `surat_url` varchar(500) DEFAULT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `email_terkirim` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tempat_mitra`
--

CREATE TABLE `tempat_mitra` (
  `id` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `nama_kepala` varchar(255) DEFAULT NULL,
  `kategori` enum('PENELITIAN','OBSERVASI','PLP_MAGANG') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tempat_mitra`
--

INSERT INTO `tempat_mitra` (`id`, `nama`, `alamat`, `telepon`, `nama_kepala`, `kategori`, `is_active`, `created_at`, `updated_at`) VALUES
('27c10597-d270-42b4-9dd7-534ebc94a756', 'SDN Ciomas 1 Bogor', 'Jl. Ciomas No. 1, Kec. Ciomas, Kab. Bogor', '0251-8312456', 'Hj. Tuti Suryati, S.Pd.', 'PLP_MAGANG', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('4a46af47-d144-481b-9c6a-7e7d90b1480f', 'SMP Negeri 1 Bogor', 'Jl. Ir. H. Juanda No. 16, Kota Bogor', '0251-8321893', 'Dra. Sri Mulyani, M.Pd.', 'OBSERVASI', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('6d40e79f-263e-46d0-95a8-b5c8cf1df4a0', 'Dinas Pendidikan Kota Bogor', 'Jl. Pemuda No. 1, Kota Bogor', '0251-8323421', 'Dr. H. Fahrudin, M.Pd.', 'PENELITIAN', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('7cd93cf8-56c1-4d0b-a198-8e1edfb4f46f', 'UIKA Bogor (Laboratorium)', 'Jl. K.H. Sholeh Iskandar Km. 2, Kota Bogor', '0251-8356884', 'Dr. Hj. Siti Aisyah, M.Si.', 'PENELITIAN', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('8893b32f-dfe0-4060-b2de-e5b83e7f8895', 'SMPN 3 Bogor', 'Jl. Raya Pajajaran No. 10, Kota Bogor', '0251-8312000', 'H. Bambang Santoso, S.Pd.', 'OBSERVASI', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('8f9c0975-c4ce-4e65-94c1-ddb3f4a9d9a3', 'SDN Pakuan Bogor', 'Jl. Pakuan No. 5, Kec. Bogor Selatan, Kota Bogor', '0251-8324891', 'Drs. Asep Kusnadi, M.Pd.', 'PLP_MAGANG', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('a4de14fc-d78e-46cc-95a6-fd70739684d9', 'SMK Negeri 1 Bogor', 'Jl. Budi Utomo No. 29, Kota Bogor', '0251-8322867', 'Drs. Agus Widodo, M.T.', 'PLP_MAGANG', 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `nama` varchar(255) NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `role` enum('MAHASISWA','STAF_TU_PRODI','KAPRODI','DEKAN','ADMIN') NOT NULL DEFAULT 'MAHASISWA',
  `program` enum('PENDIDIKAN_MASYARAKAT','PENDIDIKAN_BAHASA_INGGRIS','TEKNOLOGI_PENDIDIKAN','PENDIDIKAN_VOKASIONAL_DESAIN_FASHION','PENDIDIKAN_MATEMATIKA','PENDIDIKAN_KEPELATIHAN_OLAHRAGA','PENDIDIKAN_PROFESI_GURU') DEFAULT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `nama`, `nim`, `nip`, `role`, `program`, `angkatan`, `semester`, `no_hp`, `foto`, `is_active`, `created_at`, `updated_at`) VALUES
('00000001-0000-0000-0000-000000000001', 'ahmad.fauzan@student.uika-bogor.ac.id', '$2a$10$XsGht.vfPFxTzxbfVVN9Y.Askqsc3hSEMBAk/khViyPVolQ7M5XqS', 'Ahmad Fauzan Ramadhan', '2021010001', NULL, 'MAHASISWA', 'PENDIDIKAN_MASYARAKAT', '2021', 7, '081234567890', NULL, 1, '2026-06-03 16:13:39', '2026-10-07 15:02:45'),
('00000002-0000-0000-0000-000000000002', 'dewi.rahayu@uika-bogor.ac.id', '$2a$10$XsGht.vfPFxTzxbfVVN9Y.Askqsc3hSEMBAk/khViyPVolQ7M5XqS', 'Dewi Rahayu, S.Pd.', NULL, '198505152010012001', 'STAF_TU_PRODI', NULL, NULL, NULL, NULL, NULL, 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('00000003-0000-0000-0000-000000000003', 'hendra.gunawan@uika-bogor.ac.id', '$2a$10$XsGht.vfPFxTzxbfVVN9Y.Askqsc3hSEMBAk/khViyPVolQ7M5XqS', 'Dr. Hendra Gunawan, M.Pd.', NULL, '197803202005011002', 'KAPRODI', 'PENDIDIKAN_MASYARAKAT', NULL, NULL, NULL, NULL, 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('00000004-0000-0000-0000-000000000004', 'mulyadi.santoso@uika-bogor.ac.id', '$2a$10$XsGht.vfPFxTzxbfVVN9Y.Askqsc3hSEMBAk/khViyPVolQ7M5XqS', 'Prof. Dr. Ir. Mulyadi Santoso, M.Ed.', NULL, '196504101990031003', 'DEKAN', NULL, NULL, NULL, NULL, NULL, 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39'),
('00000005-0000-0000-0000-000000000005', 'admin@fkip.uika-bogor.ac.id', '$2a$10$XsGht.vfPFxTzxbfVVN9Y.Askqsc3hSEMBAk/khViyPVolQ7M5XqS', 'Rudi Hartono, S.Kom.', NULL, '199001012015031001', 'ADMIN', NULL, NULL, NULL, NULL, NULL, 1, '2026-06-03 16:13:39', '2026-06-03 16:13:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_logs`
--
ALTER TABLE `approval_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`),
  ADD KEY `approved_by_id` (`approved_by_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`);

--
-- Indexes for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_verify_token` (`verify_token`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `tempat_mitra`
--
ALTER TABLE `tempat_mitra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`),
  ADD UNIQUE KEY `uq_nim` (`nim`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_logs`
--
ALTER TABLE `approval_logs`
  ADD CONSTRAINT `approval_logs_ibfk_1` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_surat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_logs_ibfk_2` FOREIGN KEY (`approved_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_surat` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `pengajuan_surat_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
