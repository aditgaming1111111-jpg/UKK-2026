-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2026 at 04:14 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ukk_2026`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int UNSIGNED NOT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '123456789', 'Guru', 'guru@gmail.com', 1, 2, '2026-09-29 02:08:43', '2026-10-01 02:55:21'),
(4, '987654321', 'Ali', 'guru3@gmail.com', 1, 3, '2026-10-01 02:54:20', '2026-10-01 02:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama`, `tingkat`, `jurusan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(22, 'X RPL 1', 'X', 'RPL', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(23, 'X RPL 2', 'X', 'RPL', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(24, 'XI RPL 1', 'XI', 'RPL', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(25, 'XI RPL 2', 'XI', 'RPL', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(26, 'XII RPL 1', 'XII', 'RPL', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(27, 'XII RPL 2', 'XII', 'RPL', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int UNSIGNED NOT NULL,
  `siswa_id` int UNSIGNED NOT NULL,
  `tahun_ajaran_id` int UNSIGNED NOT NULL,
  `kelas_id` int UNSIGNED NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `tahun_ajaran_id`, `kelas_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(46, 1, 4, 22, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(47, 2, 4, 22, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(48, 3, 4, 22, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(49, 4, 4, 22, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(50, 5, 4, 23, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(51, 6, 4, 23, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(52, 7, 4, 23, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(53, 8, 4, 23, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(54, 9, 4, 24, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(55, 10, 4, 24, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(56, 11, 4, 24, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(57, 12, 4, 24, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int UNSIGNED NOT NULL,
  `pelanggaran_kategori_id` int UNSIGNED NOT NULL,
  `kode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poin` int UNSIGNED NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `pelanggaran_kategori_id`, `kode`, `nama`, `poin`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(46, 22, 'PLG001', 'Terlambat masuk sekolah', 5, 'Datang melewati waktu masuk sekolah', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(47, 25, 'PLG004', 'Membolos', 15, 'Meninggalkan pelajaran tanpa izin', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(48, 25, 'PLG005', 'Tidak masuk tanpa keterangan', 10, 'Tidak hadir tanpa keterangan', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(49, 23, 'PLG002', 'Atribut tidak lengkap', 5, 'Atribut seragam tidak lengkap', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(50, 23, 'PLG003', 'Seragam tidak sesuai', 10, 'Menggunakan seragam tidak sesuai ketentuan', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(51, 24, 'PLG006', 'Merokok di lingkungan sekolah', 30, 'Merokok di area sekolah', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(52, 24, 'PLG007', 'Berkelahi', 40, 'Terlibat perkelahian', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(53, 24, 'PLG008', 'Merusak fasilitas sekolah', 25, 'Merusak fasilitas sekolah', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(22, 'Kedisiplinan', 'Pelanggaran kedisiplinan siswa', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(23, 'Kerapian', 'Pelanggaran kerapian dan atribut', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(24, 'Ketertiban', 'Pelanggaran ketertiban sekolah', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(25, 'Kehadiran', 'Pelanggaran terkait kehadiran siswa', 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int UNSIGNED NOT NULL,
  `tahun_ajaran_id` int UNSIGNED NOT NULL,
  `siswa_id` int UNSIGNED NOT NULL,
  `nama_siswa` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas_id` int UNSIGNED DEFAULT NULL,
  `nama_kelas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pelanggaran_id` int UNSIGNED NOT NULL,
  `guru_id` int UNSIGNED DEFAULT NULL,
  `nama_guru` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `poin` int UNSIGNED NOT NULL DEFAULT '0',
  `tindakan` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tercatat',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `tahun_ajaran_id`, `siswa_id`, `nama_siswa`, `kelas_id`, `nama_kelas`, `pelanggaran_id`, `guru_id`, `nama_guru`, `tanggal`, `keterangan`, `poin`, `tindakan`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'Andi Saputra', 22, 'X RPL 1', 46, 1, 'Guru', '2026-07-15', 'Terlambat masuk sekolah', 5, 'Teguran lisan', 'tercatat', '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(2, 4, 2, 'Siti Nurhaliza', 22, 'X RPL 1', 49, 1, 'Guru', '2026-07-16', 'Atribut seragam tidak lengkap', 5, 'Teguran lisan', 'tercatat', '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(3, 4, 3, 'Budi Setiawan', 22, 'X RPL 1', 48, 1, 'Guru', '2026-07-17', 'Tidak masuk tanpa keterangan', 10, 'Pemanggilan siswa', 'tercatat', '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(4, 4, 4, 'Dewi Anggraini', 22, 'X RPL 1', 46, 1, 'Guru', '2026-07-18', 'Terlambat masuk sekolah', 5, 'Teguran lisan', 'tercatat', '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(5, 4, 5, 'Rizky Ramadhan', 23, 'X RPL 2', 50, 1, 'Guru', '2026-07-20', 'Seragam tidak sesuai ketentuan', 10, 'Teguran tertulis', 'tercatat', '2026-10-01 00:46:26', '2026-10-01 00:46:26'),
(6, 4, 6, 'Putri Amelia', 23, 'X RPL 2', 49, 1, 'Guru', '2026-07-21', 'Atribut seragam tidak lengkap', 5, 'Teguran lisan', 'tercatat', '2026-10-01 00:46:26', '2026-10-01 00:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int UNSIGNED NOT NULL,
  `nis` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '2026001', '0061000001', 'Andi Saputra', 'L', '2009-01-15', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:42:48'),
(2, '2026002', '0061000002', 'Siti Nurhaliza', 'P', '2009-02-10', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(3, '2026003', '0061000003', 'Budi Setiawan', 'L', '2009-03-12', 'Ciamis', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(4, '2026004', '0061000004', 'Dewi Anggraini', 'P', '2009-04-20', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(5, '2026005', '0061000005', 'Rizky Ramadhan', 'L', '2009-05-11', 'Ciamis', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(6, '2026006', '0061000006', 'Putri Amelia', 'P', '2009-06-18', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(7, '2026007', '0061000007', 'Fajar Nugraha', 'L', '2009-07-21', 'Garut', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(8, '2026008', '0061000008', 'Nadia Safitri', 'P', '2009-08-14', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(9, '2026009', '0061000009', 'Dimas Pratama', 'L', '2008-09-17', 'Garut', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(10, '2026010', '0061000010', 'Aulia Rahma', 'P', '2008-10-25', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(11, '2026011', '0061000011', 'Rian Firmansyah', 'L', '2008-11-09', 'Ciamis', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34'),
(12, '2026012', '0061000012', 'Intan Permatasari', 'P', '2008-12-13', 'Tasikmalaya', 1, '2026-10-01 00:46:26', '2026-10-01 01:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(4, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-10-01 00:46:25', '2026-10-01 00:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guru',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '2026-09-29 02:08:43', '2026-09-29 02:08:43'),
(2, 'Guru', 'guru@gmail.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'guru', '2026-09-29 02:08:43', '2026-09-29 02:08:43'),
(3, 'Ali', 'guru3@gmail.com', NULL, '$2y$10$E1urOsLUpYJV.UcFWC4tBeJbFJPTsnzCih4malno2eFMiJUGqfFNq', NULL, 'guru', '2026-10-01 02:54:20', '2026-10-01 02:54:20'),
(4, 'sdad', 'guru4@gmail.com', NULL, '$2y$10$RE0LxHnpHhNI6B5horzGSOIC9ixsAp/iSV3wmxJYRx07WVFAcm51K', NULL, 'guru', '2026-10-01 02:56:24', '2026-10-01 02:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int UNSIGNED NOT NULL,
  `tahun_ajaran_id` int UNSIGNED NOT NULL,
  `kelas_id` int UNSIGNED NOT NULL,
  `guru_id` int UNSIGNED NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `tahun_ajaran_id`, `kelas_id`, `guru_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(4, 4, 22, 1, '2026-07-01', NULL, 1, '2026-10-01 00:46:26', '2026-10-01 00:46:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kelas_nama` (`nama`);

--
-- Indexes for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ks_siswa` (`siswa_id`),
  ADD KEY `idx_ks_tahun` (`tahun_ajaran_id`),
  ADD KEY `idx_ks_kelas` (`kelas_id`);

--
-- Indexes for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `idx_pelanggaran_kategori` (`pelanggaran_kategori_id`);

--
-- Indexes for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ps_tahun` (`tahun_ajaran_id`),
  ADD KEY `idx_ps_siswa` (`siswa_id`),
  ADD KEY `idx_ps_kelas` (`kelas_id`),
  ADD KEY `idx_ps_pelanggaran` (`pelanggaran_id`),
  ADD KEY `idx_ps_guru` (`guru_id`),
  ADD KEY `idx_ps_tanggal` (`tanggal`);

--
-- Indexes for table `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indexes for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_wali_kelas_tahun_kelas` (`tahun_ajaran_id`,`kelas_id`),
  ADD KEY `idx_wk_guru` (`guru_id`),
  ADD KEY `fk_wk_kelas` (`kelas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `fk_guru_user` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `fk_ks_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ks_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ks_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `fk_pelanggaran_kategori` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `fk_ps_guru` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_pelanggaran` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `fk_wk_guru` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wk_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wk_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
