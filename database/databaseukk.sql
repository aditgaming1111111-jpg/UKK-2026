-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Sep 2026 pada 07.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(254) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'NIP000000000000001', 'Agus Setiawan 1', 'guru1@sekolah.test', 1, 1, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(2, 'NIP000000000000002', 'Dedi Kurniawan 2', 'guru2@sekolah.test', 1, 2, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(3, 'NIP000000000000003', 'Rina Marlina 3', 'guru3@sekolah.test', 1, 3, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(4, 'NIP000000000000004', 'Siti Nurhaliza 4', 'guru4@sekolah.test', 1, 4, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(5, 'NIP000000000000005', 'Andi Wijaya 5', 'guru5@sekolah.test', 1, 5, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(6, 'NIP000000000000006', 'Dewi Lestari 6', 'guru6@sekolah.test', 1, 6, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(7, 'NIP000000000000007', 'Bambang Hidayat 7', 'guru7@sekolah.test', 1, 7, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(8, 'NIP000000000000008', 'Novi Anggraini 8', 'guru8@sekolah.test', 1, 8, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(9, 'NIP000000000000009', 'Hendra Gunawan 9', 'guru9@sekolah.test', 1, 9, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(10, 'NIP000000000000010', 'Yudi Pratama 10', 'guru10@sekolah.test', 1, 10, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(11, 'NIP000000000000011', 'Agus Setiawan 11', 'guru11@sekolah.test', 1, 11, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(12, 'NIP000000000000012', 'Dedi Kurniawan 12', 'guru12@sekolah.test', 1, 12, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(13, 'NIP000000000000013', 'Rina Marlina 13', 'guru13@sekolah.test', 1, 13, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(14, 'NIP000000000000014', 'Siti Nurhaliza 14', 'guru14@sekolah.test', 1, 14, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(15, 'NIP000000000000015', 'Andi Wijaya 15', 'guru15@sekolah.test', 1, 15, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(16, 'NIP000000000000016', 'Dewi Lestari 16', 'guru16@sekolah.test', 1, 16, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(17, 'NIP000000000000017', 'Bambang Hidayat 17', 'guru17@sekolah.test', 1, 17, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(18, 'NIP000000000000018', 'Novi Anggraini 18', 'guru18@sekolah.test', 1, 18, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(19, 'NIP000000000000019', 'Hendra Gunawan 19', 'guru19@sekolah.test', 1, 19, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(20, 'NIP000000000000020', 'Yudi Pratama 20', 'guru20@sekolah.test', 1, 20, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(21, 'NIP000000000000021', 'Agus Setiawan 21', 'guru21@sekolah.test', 1, 21, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(22, 'NIP000000000000022', 'Dedi Kurniawan 22', 'guru22@sekolah.test', 1, 22, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(23, 'NIP000000000000023', 'Rina Marlina 23', 'guru23@sekolah.test', 1, 23, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(24, 'NIP000000000000024', 'Siti Nurhaliza 24', 'guru24@sekolah.test', 1, 24, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(25, 'NIP000000000000025', 'Andi Wijaya 25', 'guru25@sekolah.test', 1, 25, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(26, 'NIP000000000000026', 'Dewi Lestari 26', 'guru26@sekolah.test', 1, 26, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(27, 'NIP000000000000027', 'Bambang Hidayat 27', 'guru27@sekolah.test', 1, 27, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(28, 'NIP000000000000028', 'Novi Anggraini 28', 'guru28@sekolah.test', 1, 28, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(29, 'NIP000000000000029', 'Hendra Gunawan 29', 'guru29@sekolah.test', 1, 29, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(30, 'NIP000000000000030', 'Yudi Pratama 30', 'guru30@sekolah.test', 1, 30, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(31, 'NIP000000000000031', 'Agus Setiawan 31', 'guru31@sekolah.test', 1, 31, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(32, 'NIP000000000000032', 'Dedi Kurniawan 32', 'guru32@sekolah.test', 1, 32, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(33, 'NIP000000000000033', 'Rina Marlina 33', 'guru33@sekolah.test', 1, 33, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(34, 'NIP000000000000034', 'Siti Nurhaliza 34', 'guru34@sekolah.test', 1, 34, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(35, 'NIP000000000000035', 'Andi Wijaya 35', 'guru35@sekolah.test', 1, 35, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(36, 'NIP000000000000036', 'Dewi Lestari 36', 'guru36@sekolah.test', 1, 36, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(37, 'NIP000000000000037', 'Bambang Hidayat 37', 'guru37@sekolah.test', 1, 37, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(38, 'NIP000000000000038', 'Novi Anggraini 38', 'guru38@sekolah.test', 1, 38, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(39, 'NIP000000000000039', 'Hendra Gunawan 39', 'guru39@sekolah.test', 1, 39, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(40, 'NIP000000000000040', 'Yudi Pratama 40', 'guru40@sekolah.test', 1, 40, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(41, 'NIP000000000000041', 'Agus Setiawan 41', 'guru41@sekolah.test', 1, 41, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(42, 'NIP000000000000042', 'Dedi Kurniawan 42', 'guru42@sekolah.test', 1, 42, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(43, 'NIP000000000000043', 'Rina Marlina 43', 'guru43@sekolah.test', 1, 43, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(44, 'NIP000000000000044', 'Siti Nurhaliza 44', 'guru44@sekolah.test', 1, 44, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(45, 'NIP000000000000045', 'Andi Wijaya 45', 'guru45@sekolah.test', 1, 45, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(46, 'NIP000000000000046', 'Dewi Lestari 46', 'guru46@sekolah.test', 1, 46, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(47, 'NIP000000000000047', 'Bambang Hidayat 47', 'guru47@sekolah.test', 1, 47, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(48, 'NIP000000000000048', 'Novi Anggraini 48', 'guru48@sekolah.test', 1, 48, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(49, 'NIP000000000000049', 'Hendra Gunawan 49', 'guru49@sekolah.test', 1, 49, '2026-09-23 02:09:53', '2026-09-23 02:09:53'),
(50, 'NIP000000000000050', 'Yudi Pratama 50', 'guru50@sekolah.test', 1, 50, '2026-09-23 02:09:53', '2026-09-23 02:09:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama`, `tingkat`, `jurusan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'X-A', 'X', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(2, 'X-B', 'X', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(3, 'X-C', 'X', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(4, 'X-D', 'X', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(5, 'X-E', 'X', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(6, 'X-F', 'X', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(7, 'X-G', 'X', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(8, 'X-H', 'X', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(9, 'X-I', 'X', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(10, 'X-J', 'X', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(11, 'XI-A', 'XI', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(12, 'XI-B', 'XI', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(13, 'XI-C', 'XI', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(14, 'XI-D', 'XI', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(15, 'XI-E', 'XI', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(16, 'XI-F', 'XI', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(17, 'XI-G', 'XI', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(18, 'XI-H', 'XI', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(19, 'XI-I', 'XI', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(20, 'XI-J', 'XI', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(21, 'XII-A', 'XII', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(22, 'XII-B', 'XII', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(23, 'XII-C', 'XII', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(24, 'XII-D', 'XII', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(25, 'XII-E', 'XII', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(26, 'XII-F', 'XII', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(27, 'XII-G', 'XII', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(28, 'XII-H', 'XII', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(29, 'XII-I', 'XII', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(30, 'XII-J', 'XII', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(31, 'XII-A', 'XII', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(32, 'XII-B', 'XII', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(33, 'XII-C', 'XII', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(34, 'XII-D', 'XII', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(35, 'XII-E', 'XII', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(36, 'XII-F', 'XII', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(37, 'XII-G', 'XII', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(38, 'XII-H', 'XII', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(39, 'XII-I', 'XII', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(40, 'XII-J', 'XII', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(41, 'XII-A', 'XII', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(42, 'XII-B', 'XII', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(43, 'XII-C', 'XII', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(44, 'XII-D', 'XII', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(45, 'XII-E', 'XII', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(46, 'XII-F', 'XII', 'RPL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(47, 'XII-G', 'XII', 'TKJ', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(48, 'XII-H', 'XII', 'AKL', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(49, 'XII-I', 'XII', 'OTKP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02'),
(50, 'XII-J', 'XII', 'BDP', 1, '2026-09-23 02:10:02', '2026-09-23 02:10:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `tahun_ajaran_id`, `kelas_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 50, 1, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(2, 2, 50, 2, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(3, 3, 50, 3, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(4, 4, 50, 4, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(5, 5, 50, 5, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(6, 6, 50, 6, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(7, 7, 50, 7, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(8, 8, 50, 8, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(9, 9, 50, 9, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(10, 10, 50, 10, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(11, 11, 50, 11, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(12, 12, 50, 12, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(13, 13, 50, 13, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(14, 14, 50, 14, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(15, 15, 50, 15, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(16, 16, 50, 16, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(17, 17, 50, 17, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(18, 18, 50, 18, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(19, 19, 50, 19, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(20, 20, 50, 20, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(21, 21, 50, 21, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(22, 22, 50, 22, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(23, 23, 50, 23, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(24, 24, 50, 24, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(25, 25, 50, 25, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(26, 26, 50, 26, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(27, 27, 50, 27, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(28, 28, 50, 28, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(29, 29, 50, 29, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(30, 30, 50, 30, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(31, 31, 50, 31, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(32, 32, 50, 32, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(33, 33, 50, 33, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(34, 34, 50, 34, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(35, 35, 50, 35, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(36, 36, 50, 36, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(37, 37, 50, 37, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(38, 38, 50, 38, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(39, 39, 50, 39, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(40, 40, 50, 40, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(41, 41, 50, 41, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(42, 42, 50, 42, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(43, 43, 50, 43, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(44, 44, 50, 44, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(45, 45, 50, 45, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(46, 46, 50, 46, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(47, 47, 50, 47, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(48, 48, 50, 48, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(49, 49, 50, 49, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33'),
(50, 50, 50, 50, '2025-07-01', NULL, 1, '2026-09-23 02:10:33', '2026-09-23 02:10:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `poin` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `pelanggaran_kategori_id`, `kode`, `nama`, `poin`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'PLG-001', 'Terlambat datang ke sekolah', 5, 'Deskripsi pelanggaran nomor 1', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(2, 2, 'PLG-002', 'Tidak memakai atribut lengkap', 10, 'Deskripsi pelanggaran nomor 2', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(3, 3, 'PLG-003', 'Tidak masuk tanpa keterangan', 15, 'Deskripsi pelanggaran nomor 3', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(4, 4, 'PLG-004', 'Rambut tidak sesuai ketentuan', 20, 'Deskripsi pelanggaran nomor 4', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(5, 5, 'PLG-005', 'Tidak mengerjakan tugas', 25, 'Deskripsi pelanggaran nomor 5', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(6, 6, 'PLG-006', 'Membuat keributan di kelas', 30, 'Deskripsi pelanggaran nomor 6', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(7, 7, 'PLG-007', 'Membuang sampah sembarangan', 35, 'Deskripsi pelanggaran nomor 7', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(8, 8, 'PLG-008', 'Membawa barang terlarang', 40, 'Deskripsi pelanggaran nomor 8', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(9, 9, 'PLG-009', 'Berbicara tidak sopan', 45, 'Deskripsi pelanggaran nomor 9', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(10, 10, 'PLG-010', 'Melanggar tata tertib sekolah', 50, 'Deskripsi pelanggaran nomor 10', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(11, 11, 'PLG-011', 'Terlambat datang ke sekolah', 5, 'Deskripsi pelanggaran nomor 11', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(12, 12, 'PLG-012', 'Tidak memakai atribut lengkap', 10, 'Deskripsi pelanggaran nomor 12', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(13, 13, 'PLG-013', 'Tidak masuk tanpa keterangan', 15, 'Deskripsi pelanggaran nomor 13', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(14, 14, 'PLG-014', 'Rambut tidak sesuai ketentuan', 20, 'Deskripsi pelanggaran nomor 14', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(15, 15, 'PLG-015', 'Tidak mengerjakan tugas', 25, 'Deskripsi pelanggaran nomor 15', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(16, 16, 'PLG-016', 'Membuat keributan di kelas', 30, 'Deskripsi pelanggaran nomor 16', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(17, 17, 'PLG-017', 'Membuang sampah sembarangan', 35, 'Deskripsi pelanggaran nomor 17', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(18, 18, 'PLG-018', 'Membawa barang terlarang', 40, 'Deskripsi pelanggaran nomor 18', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(19, 19, 'PLG-019', 'Berbicara tidak sopan', 45, 'Deskripsi pelanggaran nomor 19', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(20, 20, 'PLG-020', 'Melanggar tata tertib sekolah', 50, 'Deskripsi pelanggaran nomor 20', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(21, 21, 'PLG-021', 'Terlambat datang ke sekolah', 5, 'Deskripsi pelanggaran nomor 21', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(22, 22, 'PLG-022', 'Tidak memakai atribut lengkap', 10, 'Deskripsi pelanggaran nomor 22', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(23, 23, 'PLG-023', 'Tidak masuk tanpa keterangan', 15, 'Deskripsi pelanggaran nomor 23', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(24, 24, 'PLG-024', 'Rambut tidak sesuai ketentuan', 20, 'Deskripsi pelanggaran nomor 24', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(25, 25, 'PLG-025', 'Tidak mengerjakan tugas', 25, 'Deskripsi pelanggaran nomor 25', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(26, 26, 'PLG-026', 'Membuat keributan di kelas', 30, 'Deskripsi pelanggaran nomor 26', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(27, 27, 'PLG-027', 'Membuang sampah sembarangan', 35, 'Deskripsi pelanggaran nomor 27', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(28, 28, 'PLG-028', 'Membawa barang terlarang', 40, 'Deskripsi pelanggaran nomor 28', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(29, 29, 'PLG-029', 'Berbicara tidak sopan', 45, 'Deskripsi pelanggaran nomor 29', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(30, 30, 'PLG-030', 'Melanggar tata tertib sekolah', 50, 'Deskripsi pelanggaran nomor 30', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(31, 31, 'PLG-031', 'Terlambat datang ke sekolah', 5, 'Deskripsi pelanggaran nomor 31', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(32, 32, 'PLG-032', 'Tidak memakai atribut lengkap', 10, 'Deskripsi pelanggaran nomor 32', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(33, 33, 'PLG-033', 'Tidak masuk tanpa keterangan', 15, 'Deskripsi pelanggaran nomor 33', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(34, 34, 'PLG-034', 'Rambut tidak sesuai ketentuan', 20, 'Deskripsi pelanggaran nomor 34', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(35, 35, 'PLG-035', 'Tidak mengerjakan tugas', 25, 'Deskripsi pelanggaran nomor 35', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(36, 36, 'PLG-036', 'Membuat keributan di kelas', 30, 'Deskripsi pelanggaran nomor 36', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(37, 37, 'PLG-037', 'Membuang sampah sembarangan', 35, 'Deskripsi pelanggaran nomor 37', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(38, 38, 'PLG-038', 'Membawa barang terlarang', 40, 'Deskripsi pelanggaran nomor 38', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(39, 39, 'PLG-039', 'Berbicara tidak sopan', 45, 'Deskripsi pelanggaran nomor 39', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(40, 40, 'PLG-040', 'Melanggar tata tertib sekolah', 50, 'Deskripsi pelanggaran nomor 40', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(41, 41, 'PLG-041', 'Terlambat datang ke sekolah', 5, 'Deskripsi pelanggaran nomor 41', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(42, 42, 'PLG-042', 'Tidak memakai atribut lengkap', 10, 'Deskripsi pelanggaran nomor 42', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(43, 43, 'PLG-043', 'Tidak masuk tanpa keterangan', 15, 'Deskripsi pelanggaran nomor 43', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(44, 44, 'PLG-044', 'Rambut tidak sesuai ketentuan', 20, 'Deskripsi pelanggaran nomor 44', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(45, 45, 'PLG-045', 'Tidak mengerjakan tugas', 25, 'Deskripsi pelanggaran nomor 45', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(46, 46, 'PLG-046', 'Membuat keributan di kelas', 30, 'Deskripsi pelanggaran nomor 46', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(47, 47, 'PLG-047', 'Membuang sampah sembarangan', 35, 'Deskripsi pelanggaran nomor 47', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(48, 48, 'PLG-048', 'Membawa barang terlarang', 40, 'Deskripsi pelanggaran nomor 48', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(49, 49, 'PLG-049', 'Berbicara tidak sopan', 45, 'Deskripsi pelanggaran nomor 49', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22'),
(50, 50, 'PLG-050', 'Melanggar tata tertib sekolah', 50, 'Deskripsi pelanggaran nomor 50', 1, '2026-09-23 02:10:22', '2026-09-23 02:10:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan 1', 'Kategori pelanggaran sekolah nomor 1', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(2, 'Kerapian 2', 'Kategori pelanggaran sekolah nomor 2', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(3, 'Kehadiran 3', 'Kategori pelanggaran sekolah nomor 3', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(4, 'Sikap 4', 'Kategori pelanggaran sekolah nomor 4', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(5, 'Akademik 5', 'Kategori pelanggaran sekolah nomor 5', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(6, 'Ketertiban 6', 'Kategori pelanggaran sekolah nomor 6', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(7, 'Kebersihan 7', 'Kategori pelanggaran sekolah nomor 7', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(8, 'Peraturan Sekolah 8', 'Kategori pelanggaran sekolah nomor 8', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(9, 'Etika 9', 'Kategori pelanggaran sekolah nomor 9', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(10, 'Lainnya 10', 'Kategori pelanggaran sekolah nomor 10', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(11, 'Kedisiplinan 11', 'Kategori pelanggaran sekolah nomor 11', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(12, 'Kerapian 12', 'Kategori pelanggaran sekolah nomor 12', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(13, 'Kehadiran 13', 'Kategori pelanggaran sekolah nomor 13', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(14, 'Sikap 14', 'Kategori pelanggaran sekolah nomor 14', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(15, 'Akademik 15', 'Kategori pelanggaran sekolah nomor 15', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(16, 'Ketertiban 16', 'Kategori pelanggaran sekolah nomor 16', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(17, 'Kebersihan 17', 'Kategori pelanggaran sekolah nomor 17', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(18, 'Peraturan Sekolah 18', 'Kategori pelanggaran sekolah nomor 18', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(19, 'Etika 19', 'Kategori pelanggaran sekolah nomor 19', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(20, 'Lainnya 20', 'Kategori pelanggaran sekolah nomor 20', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(21, 'Kedisiplinan 21', 'Kategori pelanggaran sekolah nomor 21', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(22, 'Kerapian 22', 'Kategori pelanggaran sekolah nomor 22', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(23, 'Kehadiran 23', 'Kategori pelanggaran sekolah nomor 23', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(24, 'Sikap 24', 'Kategori pelanggaran sekolah nomor 24', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(25, 'Akademik 25', 'Kategori pelanggaran sekolah nomor 25', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(26, 'Ketertiban 26', 'Kategori pelanggaran sekolah nomor 26', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(27, 'Kebersihan 27', 'Kategori pelanggaran sekolah nomor 27', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(28, 'Peraturan Sekolah 28', 'Kategori pelanggaran sekolah nomor 28', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(29, 'Etika 29', 'Kategori pelanggaran sekolah nomor 29', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(30, 'Lainnya 30', 'Kategori pelanggaran sekolah nomor 30', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(31, 'Kedisiplinan 31', 'Kategori pelanggaran sekolah nomor 31', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(32, 'Kerapian 32', 'Kategori pelanggaran sekolah nomor 32', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(33, 'Kehadiran 33', 'Kategori pelanggaran sekolah nomor 33', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(34, 'Sikap 34', 'Kategori pelanggaran sekolah nomor 34', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(35, 'Akademik 35', 'Kategori pelanggaran sekolah nomor 35', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(36, 'Ketertiban 36', 'Kategori pelanggaran sekolah nomor 36', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(37, 'Kebersihan 37', 'Kategori pelanggaran sekolah nomor 37', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(38, 'Peraturan Sekolah 38', 'Kategori pelanggaran sekolah nomor 38', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(39, 'Etika 39', 'Kategori pelanggaran sekolah nomor 39', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(40, 'Lainnya 40', 'Kategori pelanggaran sekolah nomor 40', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(41, 'Kedisiplinan 41', 'Kategori pelanggaran sekolah nomor 41', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(42, 'Kerapian 42', 'Kategori pelanggaran sekolah nomor 42', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(43, 'Kehadiran 43', 'Kategori pelanggaran sekolah nomor 43', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(44, 'Sikap 44', 'Kategori pelanggaran sekolah nomor 44', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(45, 'Akademik 45', 'Kategori pelanggaran sekolah nomor 45', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(46, 'Ketertiban 46', 'Kategori pelanggaran sekolah nomor 46', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(47, 'Kebersihan 47', 'Kategori pelanggaran sekolah nomor 47', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(48, 'Peraturan Sekolah 48', 'Kategori pelanggaran sekolah nomor 48', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(49, 'Etika 49', 'Kategori pelanggaran sekolah nomor 49', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11'),
(50, 'Lainnya 50', 'Kategori pelanggaran sekolah nomor 50', 1, '2026-09-23 02:10:11', '2026-09-23 02:10:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `pelanggaran_id` int(11) NOT NULL,
  `nama_pelanggaran` varchar(150) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `poin` int(11) DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `tahun_ajaran_id`, `siswa_id`, `nama_siswa`, `kelas_id`, `nama_kelas`, `pelanggaran_id`, `nama_pelanggaran`, `pelanggaran_kategori_id`, `guru_id`, `nama_guru`, `tanggal`, `keterangan`, `poin`, `tindakan`, `status`, `created_at`, `updated_at`) VALUES
(1, 50, 1, 'Ahmad Fauzan', 1, 'X-A', 1, 'Terlambat datang ke sekolah', 1, 1, 'Agus Setiawan 1', '2025-07-01', 'Diberikan teguran oleh guru', 5, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(2, 50, 2, 'Budi Setiawan', 2, 'X-B', 2, 'Tidak memakai atribut lengkap', 2, 2, 'Dedi Kurniawan 2', '2025-07-02', 'Sudah diberikan pembinaan', 10, 'Pembinaan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(3, 50, 3, 'Citra Lestari', 3, 'X-C', 3, 'Tidak masuk tanpa keterangan', 3, 3, 'Rina Marlina 3', '2025-07-03', 'Siswa membuat surat pernyataan', 15, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(4, 50, 4, 'Dinda Permata', 4, 'X-D', 4, 'Rambut tidak sesuai ketentuan', 4, 4, 'Siti Nurhaliza 4', '2025-07-04', 'Dipanggil wali kelas', 20, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(5, 50, 5, 'Eko Pratama', 5, 'X-E', 5, 'Tidak mengerjakan tugas', 5, 5, 'Andi Wijaya 5', '2025-07-05', 'Diberikan pembinaan lanjutan', 25, 'Pembinaan Lanjutan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(6, 50, 6, 'Fajar Ramadhan', 6, 'X-F', 6, 'Membuat keributan di kelas', 6, 6, 'Dewi Lestari 6', '2025-07-06', 'Diberikan teguran oleh guru', 30, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(7, 50, 7, 'Gita Anggraini', 7, 'X-G', 7, 'Membuang sampah sembarangan', 7, 7, 'Bambang Hidayat 7', '2025-07-07', 'Sudah diberikan pembinaan', 35, 'Pembinaan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(8, 50, 8, 'Hendra Wijaya', 8, 'X-H', 8, 'Membawa barang terlarang', 8, 8, 'Novi Anggraini 8', '2025-07-08', 'Siswa membuat surat pernyataan', 40, 'Surat Pernyataan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(9, 50, 9, 'Intan Sari', 9, 'X-I', 9, 'Berbicara tidak sopan', 9, 9, 'Hendra Gunawan 9', '2025-07-09', 'Dipanggil wali kelas', 45, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(10, 50, 10, 'Joko Saputra', 10, 'X-J', 10, 'Melanggar tata tertib sekolah', 10, 10, 'Yudi Pratama 10', '2025-07-10', 'Diberikan pembinaan lanjutan', 50, 'Pembinaan Lanjutan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(11, 50, 11, 'Kania Putri', 11, 'XI-A', 11, 'Terlambat datang ke sekolah', 11, 11, 'Agus Setiawan 11', '2025-07-11', 'Diberikan teguran oleh guru', 5, 'Teguran', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(12, 50, 12, 'Lukman Hakim', 12, 'XI-B', 12, 'Tidak memakai atribut lengkap', 12, 12, 'Dedi Kurniawan 12', '2025-07-12', 'Sudah diberikan pembinaan', 10, 'Pembinaan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(13, 50, 13, 'Maya Safitri', 13, 'XI-C', 13, 'Tidak masuk tanpa keterangan', 13, 13, 'Rina Marlina 13', '2025-07-13', 'Siswa membuat surat pernyataan', 15, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(14, 50, 14, 'Nanda Maulana', 14, 'XI-D', 14, 'Rambut tidak sesuai ketentuan', 14, 14, 'Siti Nurhaliza 14', '2025-07-14', 'Dipanggil wali kelas', 20, 'Pemanggilan Wali Kelas', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(15, 50, 15, 'Olivia Amelia', 15, 'XI-E', 15, 'Tidak mengerjakan tugas', 15, 15, 'Andi Wijaya 15', '2025-07-15', 'Diberikan pembinaan lanjutan', 25, 'Pembinaan Lanjutan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(16, 50, 16, 'Putra Nugraha', 16, 'XI-F', 16, 'Membuat keributan di kelas', 16, 16, 'Dewi Lestari 16', '2025-07-16', 'Diberikan teguran oleh guru', 30, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(17, 50, 17, 'Qori Aulia', 17, 'XI-G', 17, 'Membuang sampah sembarangan', 17, 17, 'Bambang Hidayat 17', '2025-07-17', 'Sudah diberikan pembinaan', 35, 'Pembinaan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(18, 50, 18, 'Raka Aditya', 18, 'XI-H', 18, 'Membawa barang terlarang', 18, 18, 'Novi Anggraini 18', '2025-07-18', 'Siswa membuat surat pernyataan', 40, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(19, 50, 19, 'Salsa Nuraini', 19, 'XI-I', 19, 'Berbicara tidak sopan', 19, 19, 'Hendra Gunawan 19', '2025-07-19', 'Dipanggil wali kelas', 45, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(20, 50, 20, 'Tegar Firmansyah', 20, 'XI-J', 20, 'Melanggar tata tertib sekolah', 20, 20, 'Yudi Pratama 20', '2025-07-20', 'Diberikan pembinaan lanjutan', 50, 'Pembinaan Lanjutan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(21, 50, 21, 'Umi Kalsum', 21, 'XII-A', 21, 'Terlambat datang ke sekolah', 21, 21, 'Agus Setiawan 21', '2025-07-21', 'Diberikan teguran oleh guru', 5, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(22, 50, 22, 'Vino Alfarizi', 22, 'XII-B', 22, 'Tidak memakai atribut lengkap', 22, 22, 'Dedi Kurniawan 22', '2025-07-22', 'Sudah diberikan pembinaan', 10, 'Pembinaan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(23, 50, 23, 'Wulan Sari', 23, 'XII-C', 23, 'Tidak masuk tanpa keterangan', 23, 23, 'Rina Marlina 23', '2025-07-23', 'Siswa membuat surat pernyataan', 15, 'Surat Pernyataan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(24, 50, 24, 'Yoga Pratama', 24, 'XII-D', 24, 'Rambut tidak sesuai ketentuan', 24, 24, 'Siti Nurhaliza 24', '2025-07-24', 'Dipanggil wali kelas', 20, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(25, 50, 25, 'Zahra Azzahra', 25, 'XII-E', 25, 'Tidak mengerjakan tugas', 25, 25, 'Andi Wijaya 25', '2025-07-25', 'Diberikan pembinaan lanjutan', 25, 'Pembinaan Lanjutan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(26, 50, 26, 'Arif Hidayat', 26, 'XII-F', 26, 'Membuat keributan di kelas', 26, 26, 'Dewi Lestari 26', '2025-07-26', 'Diberikan teguran oleh guru', 30, 'Teguran', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(27, 50, 27, 'Bella Maharani', 27, 'XII-G', 27, 'Membuang sampah sembarangan', 27, 27, 'Bambang Hidayat 27', '2025-07-27', 'Sudah diberikan pembinaan', 35, 'Pembinaan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(28, 50, 28, 'Chandra Kusuma', 28, 'XII-H', 28, 'Membawa barang terlarang', 28, 28, 'Novi Anggraini 28', '2025-07-28', 'Siswa membuat surat pernyataan', 40, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(29, 50, 29, 'Dewi Kartika', 29, 'XII-I', 29, 'Berbicara tidak sopan', 29, 29, 'Hendra Gunawan 29', '2025-07-29', 'Dipanggil wali kelas', 45, 'Pemanggilan Wali Kelas', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(30, 50, 30, 'Erwin Setiawan', 30, 'XII-J', 30, 'Melanggar tata tertib sekolah', 30, 30, 'Yudi Pratama 30', '2025-07-30', 'Diberikan pembinaan lanjutan', 50, 'Pembinaan Lanjutan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(31, 50, 31, 'Fitri Handayani', 31, 'XII-A', 31, 'Terlambat datang ke sekolah', 31, 31, 'Agus Setiawan 31', '2025-07-31', 'Diberikan teguran oleh guru', 5, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(32, 50, 32, 'Galih Ramadhan', 32, 'XII-B', 32, 'Tidak memakai atribut lengkap', 32, 32, 'Dedi Kurniawan 32', '2025-08-01', 'Sudah diberikan pembinaan', 10, 'Pembinaan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(33, 50, 33, 'Hani Fauziah', 33, 'XII-C', 33, 'Tidak masuk tanpa keterangan', 33, 33, 'Rina Marlina 33', '2025-08-02', 'Siswa membuat surat pernyataan', 15, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(34, 50, 34, 'Irfan Maulana', 34, 'XII-D', 34, 'Rambut tidak sesuai ketentuan', 34, 34, 'Siti Nurhaliza 34', '2025-08-03', 'Dipanggil wali kelas', 20, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(35, 50, 35, 'Jihan Safira', 35, 'XII-E', 35, 'Tidak mengerjakan tugas', 35, 35, 'Andi Wijaya 35', '2025-08-04', 'Diberikan pembinaan lanjutan', 25, 'Pembinaan Lanjutan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(36, 50, 36, 'Kevin Prakoso', 36, 'XII-F', 36, 'Membuat keributan di kelas', 36, 36, 'Dewi Lestari 36', '2025-08-05', 'Diberikan teguran oleh guru', 30, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(37, 50, 37, 'Laila Rahma', 37, 'XII-G', 37, 'Membuang sampah sembarangan', 37, 37, 'Bambang Hidayat 37', '2025-08-06', 'Sudah diberikan pembinaan', 35, 'Pembinaan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(38, 50, 38, 'Miftahul Huda', 38, 'XII-H', 38, 'Membawa barang terlarang', 38, 38, 'Novi Anggraini 38', '2025-08-07', 'Siswa membuat surat pernyataan', 40, 'Surat Pernyataan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(39, 50, 39, 'Nabila Putri', 39, 'XII-I', 39, 'Berbicara tidak sopan', 39, 39, 'Hendra Gunawan 39', '2025-08-08', 'Dipanggil wali kelas', 45, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(40, 50, 40, 'Oki Firmansyah', 40, 'XII-J', 40, 'Melanggar tata tertib sekolah', 40, 40, 'Yudi Pratama 40', '2025-08-09', 'Diberikan pembinaan lanjutan', 50, 'Pembinaan Lanjutan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(41, 50, 41, 'Pandu Wijaya', 41, 'XII-A', 41, 'Terlambat datang ke sekolah', 41, 41, 'Agus Setiawan 41', '2025-08-10', 'Diberikan teguran oleh guru', 5, 'Teguran', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(42, 50, 42, 'Rina Marlina', 42, 'XII-B', 42, 'Tidak memakai atribut lengkap', 42, 42, 'Dedi Kurniawan 42', '2025-08-11', 'Sudah diberikan pembinaan', 10, 'Pembinaan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(43, 50, 43, 'Sandi Nugroho', 43, 'XII-C', 43, 'Tidak masuk tanpa keterangan', 43, 43, 'Rina Marlina 43', '2025-08-12', 'Siswa membuat surat pernyataan', 15, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(44, 50, 44, 'Tania Aprilia', 44, 'XII-D', 44, 'Rambut tidak sesuai ketentuan', 44, 44, 'Siti Nurhaliza 44', '2025-08-13', 'Dipanggil wali kelas', 20, 'Pemanggilan Wali Kelas', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(45, 50, 45, 'Ujang Kurnia', 45, 'XII-E', 45, 'Tidak mengerjakan tugas', 45, 45, 'Andi Wijaya 45', '2025-08-14', 'Diberikan pembinaan lanjutan', 25, 'Pembinaan Lanjutan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(46, 50, 46, 'Vina Oktaviani', 46, 'XII-F', 46, 'Membuat keributan di kelas', 46, 46, 'Dewi Lestari 46', '2025-08-15', 'Diberikan teguran oleh guru', 30, 'Teguran', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(47, 50, 47, 'Wahyu Setiawan', 47, 'XII-G', 47, 'Membuang sampah sembarangan', 47, 47, 'Bambang Hidayat 47', '2025-08-16', 'Sudah diberikan pembinaan', 35, 'Pembinaan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(48, 50, 48, 'Yuni Astuti', 48, 'XII-H', 48, 'Membawa barang terlarang', 48, 48, 'Novi Anggraini 48', '2025-08-17', 'Siswa membuat surat pernyataan', 40, 'Surat Pernyataan', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(49, 50, 49, 'Zaki Ramadhan', 49, 'XII-I', 49, 'Berbicara tidak sopan', 49, 49, 'Hendra Gunawan 49', '2025-08-18', 'Dipanggil wali kelas', 45, 'Pemanggilan Wali Kelas', 'Selesai', '2026-09-23 02:11:17', '2026-09-23 02:11:17'),
(50, 50, 50, 'Zulfa Nabila', 50, 'XII-J', 50, 'Melanggar tata tertib sekolah', 50, 50, 'Yudi Pratama 50', '2025-08-19', 'Diberikan pembinaan lanjutan', 50, 'Pembinaan Lanjutan', 'Diproses', '2026-09-23 02:11:17', '2026-09-23 02:11:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'NIS00001', '0000000001', 'Ahmad Fauzan', 'L', '2008-01-02', 'Jl. Pendidikan No. 1, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(2, 'NIS00002', '0000000002', 'Budi Setiawan', 'P', '2008-01-03', 'Jl. Pendidikan No. 2, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(3, 'NIS00003', '0000000003', 'Citra Lestari', 'L', '2008-01-04', 'Jl. Pendidikan No. 3, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(4, 'NIS00004', '0000000004', 'Dinda Permata', 'P', '2008-01-05', 'Jl. Pendidikan No. 4, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(5, 'NIS00005', '0000000005', 'Eko Pratama', 'L', '2008-01-06', 'Jl. Pendidikan No. 5, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(6, 'NIS00006', '0000000006', 'Fajar Ramadhan', 'P', '2008-01-07', 'Jl. Pendidikan No. 6, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(7, 'NIS00007', '0000000007', 'Gita Anggraini', 'L', '2008-01-08', 'Jl. Pendidikan No. 7, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(8, 'NIS00008', '0000000008', 'Hendra Wijaya', 'P', '2008-01-09', 'Jl. Pendidikan No. 8, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(9, 'NIS00009', '0000000009', 'Intan Sari', 'L', '2008-01-10', 'Jl. Pendidikan No. 9, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(10, 'NIS00010', '0000000010', 'Joko Saputra', 'P', '2008-01-11', 'Jl. Pendidikan No. 10, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(11, 'NIS00011', '0000000011', 'Kania Putri', 'L', '2008-01-12', 'Jl. Pendidikan No. 11, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(12, 'NIS00012', '0000000012', 'Lukman Hakim', 'P', '2008-01-13', 'Jl. Pendidikan No. 12, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(13, 'NIS00013', '0000000013', 'Maya Safitri', 'L', '2008-01-14', 'Jl. Pendidikan No. 13, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(14, 'NIS00014', '0000000014', 'Nanda Maulana', 'P', '2008-01-15', 'Jl. Pendidikan No. 14, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(15, 'NIS00015', '0000000015', 'Olivia Amelia', 'L', '2008-01-16', 'Jl. Pendidikan No. 15, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(16, 'NIS00016', '0000000016', 'Putra Nugraha', 'P', '2008-01-17', 'Jl. Pendidikan No. 16, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(17, 'NIS00017', '0000000017', 'Qori Aulia', 'L', '2008-01-18', 'Jl. Pendidikan No. 17, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(18, 'NIS00018', '0000000018', 'Raka Aditya', 'P', '2008-01-19', 'Jl. Pendidikan No. 18, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(19, 'NIS00019', '0000000019', 'Salsa Nuraini', 'L', '2008-01-20', 'Jl. Pendidikan No. 19, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(20, 'NIS00020', '0000000020', 'Tegar Firmansyah', 'P', '2008-01-21', 'Jl. Pendidikan No. 20, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(21, 'NIS00021', '0000000021', 'Umi Kalsum', 'L', '2008-01-22', 'Jl. Pendidikan No. 21, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(22, 'NIS00022', '0000000022', 'Vino Alfarizi', 'P', '2008-01-23', 'Jl. Pendidikan No. 22, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(23, 'NIS00023', '0000000023', 'Wulan Sari', 'L', '2008-01-24', 'Jl. Pendidikan No. 23, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(24, 'NIS00024', '0000000024', 'Yoga Pratama', 'P', '2008-01-25', 'Jl. Pendidikan No. 24, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(25, 'NIS00025', '0000000025', 'Zahra Azzahra', 'L', '2008-01-26', 'Jl. Pendidikan No. 25, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(26, 'NIS00026', '0000000026', 'Arif Hidayat', 'P', '2008-01-27', 'Jl. Pendidikan No. 26, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(27, 'NIS00027', '0000000027', 'Bella Maharani', 'L', '2008-01-28', 'Jl. Pendidikan No. 27, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(28, 'NIS00028', '0000000028', 'Chandra Kusuma', 'P', '2008-01-29', 'Jl. Pendidikan No. 28, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(29, 'NIS00029', '0000000029', 'Dewi Kartika', 'L', '2008-01-30', 'Jl. Pendidikan No. 29, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(30, 'NIS00030', '0000000030', 'Erwin Setiawan', 'P', '2008-01-31', 'Jl. Pendidikan No. 30, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(31, 'NIS00031', '0000000031', 'Fitri Handayani', 'L', '2008-02-01', 'Jl. Pendidikan No. 31, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(32, 'NIS00032', '0000000032', 'Galih Ramadhan', 'P', '2008-02-02', 'Jl. Pendidikan No. 32, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(33, 'NIS00033', '0000000033', 'Hani Fauziah', 'L', '2008-02-03', 'Jl. Pendidikan No. 33, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(34, 'NIS00034', '0000000034', 'Irfan Maulana', 'P', '2008-02-04', 'Jl. Pendidikan No. 34, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(35, 'NIS00035', '0000000035', 'Jihan Safira', 'L', '2008-02-05', 'Jl. Pendidikan No. 35, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(36, 'NIS00036', '0000000036', 'Kevin Prakoso', 'P', '2008-02-06', 'Jl. Pendidikan No. 36, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(37, 'NIS00037', '0000000037', 'Laila Rahma', 'L', '2008-02-07', 'Jl. Pendidikan No. 37, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(38, 'NIS00038', '0000000038', 'Miftahul Huda', 'P', '2008-02-08', 'Jl. Pendidikan No. 38, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(39, 'NIS00039', '0000000039', 'Nabila Putri', 'L', '2008-02-09', 'Jl. Pendidikan No. 39, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(40, 'NIS00040', '0000000040', 'Oki Firmansyah', 'P', '2008-02-10', 'Jl. Pendidikan No. 40, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(41, 'NIS00041', '0000000041', 'Pandu Wijaya', 'L', '2008-02-11', 'Jl. Pendidikan No. 41, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(42, 'NIS00042', '0000000042', 'Rina Marlina', 'P', '2008-02-12', 'Jl. Pendidikan No. 42, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(43, 'NIS00043', '0000000043', 'Sandi Nugroho', 'L', '2008-02-13', 'Jl. Pendidikan No. 43, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(44, 'NIS00044', '0000000044', 'Tania Aprilia', 'P', '2008-02-14', 'Jl. Pendidikan No. 44, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(45, 'NIS00045', '0000000045', 'Ujang Kurnia', 'L', '2008-02-15', 'Jl. Pendidikan No. 45, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(46, 'NIS00046', '0000000046', 'Vina Oktaviani', 'P', '2008-02-16', 'Jl. Pendidikan No. 46, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(47, 'NIS00047', '0000000047', 'Wahyu Setiawan', 'L', '2008-02-17', 'Jl. Pendidikan No. 47, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(48, 'NIS00048', '0000000048', 'Yuni Astuti', 'P', '2008-02-18', 'Jl. Pendidikan No. 48, Tasikmalaya', 1, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(49, 'NIS00049', '0000000049', 'Zaki Ramadhan', 'L', '2008-02-19', 'Jl. Pendidikan No. 49, Tasikmalaya', 0, '2026-09-23 02:09:14', '2026-09-23 02:09:14'),
(50, 'NIS00050', '0000000050', 'Zulfa Nabila', 'P', '2008-02-20', 'Jl. Pendidikan No. 50, Tasikmalaya', 0, '2026-09-23 02:09:14', '2026-09-23 02:09:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '1976/1977', '1976-07-01', '1977-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(2, '1977/1978', '1977-07-01', '1978-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(3, '1978/1979', '1978-07-01', '1979-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(4, '1979/1980', '1979-07-01', '1980-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(5, '1980/1981', '1980-07-01', '1981-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(6, '1981/1982', '1981-07-01', '1982-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(7, '1982/1983', '1982-07-01', '1983-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(8, '1983/1984', '1983-07-01', '1984-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(9, '1984/1985', '1984-07-01', '1985-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(10, '1985/1986', '1985-07-01', '1986-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(11, '1986/1987', '1986-07-01', '1987-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(12, '1987/1988', '1987-07-01', '1988-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(13, '1988/1989', '1988-07-01', '1989-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(14, '1989/1990', '1989-07-01', '1990-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(15, '1990/1991', '1990-07-01', '1991-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(16, '1991/1992', '1991-07-01', '1992-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(17, '1992/1993', '1992-07-01', '1993-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(18, '1993/1994', '1993-07-01', '1994-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(19, '1994/1995', '1994-07-01', '1995-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(20, '1995/1996', '1995-07-01', '1996-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(21, '1996/1997', '1996-07-01', '1997-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(22, '1997/1998', '1997-07-01', '1998-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(23, '1998/1999', '1998-07-01', '1999-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(24, '1999/2000', '1999-07-01', '2000-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(25, '2000/2001', '2000-07-01', '2001-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(26, '2001/2002', '2001-07-01', '2002-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(27, '2002/2003', '2002-07-01', '2003-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(28, '2003/2004', '2003-07-01', '2004-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(29, '2004/2005', '2004-07-01', '2005-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(30, '2005/2006', '2005-07-01', '2006-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(31, '2006/2007', '2006-07-01', '2007-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(32, '2007/2008', '2007-07-01', '2008-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(33, '2008/2009', '2008-07-01', '2009-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(34, '2009/2010', '2009-07-01', '2010-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(35, '2010/2011', '2010-07-01', '2011-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(36, '2011/2012', '2011-07-01', '2012-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(37, '2012/2013', '2012-07-01', '2013-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(38, '2013/2014', '2013-07-01', '2014-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(39, '2014/2015', '2014-07-01', '2015-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(40, '2015/2016', '2015-07-01', '2016-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(41, '2016/2017', '2016-07-01', '2017-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(42, '2017/2018', '2017-07-01', '2018-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(43, '2018/2019', '2018-07-01', '2019-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(44, '2019/2020', '2019-07-01', '2020-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(45, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(46, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(47, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(48, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(49, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-09-23 02:09:25', '2026-09-23 02:09:25'),
(50, '2025/2026', '2025-07-01', '2026-06-30', 1, '2026-09-23 02:09:25', '2026-09-23 02:09:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'User 1', 'user1@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(2, 'User 2', 'user2@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(3, 'User 3', 'user3@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(4, 'User 4', 'user4@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(5, 'User 5', 'user5@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(6, 'User 6', 'user6@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(7, 'User 7', 'user7@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(8, 'User 8', 'user8@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(9, 'User 9', 'user9@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(10, 'User 10', 'user10@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(11, 'User 11', 'user11@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(12, 'User 12', 'user12@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(13, 'User 13', 'user13@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(14, 'User 14', 'user14@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(15, 'User 15', 'user15@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(16, 'User 16', 'user16@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(17, 'User 17', 'user17@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(18, 'User 18', 'user18@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(19, 'User 19', 'user19@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(20, 'User 20', 'user20@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(21, 'User 21', 'user21@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(22, 'User 22', 'user22@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(23, 'User 23', 'user23@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(24, 'User 24', 'user24@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(25, 'User 25', 'user25@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(26, 'User 26', 'user26@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(27, 'User 27', 'user27@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(28, 'User 28', 'user28@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(29, 'User 29', 'user29@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(30, 'User 30', 'user30@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(31, 'User 31', 'user31@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(32, 'User 32', 'user32@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(33, 'User 33', 'user33@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(34, 'User 34', 'user34@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(35, 'User 35', 'user35@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(36, 'User 36', 'user36@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(37, 'User 37', 'user37@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(38, 'User 38', 'user38@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(39, 'User 39', 'user39@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(40, 'User 40', 'user40@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(41, 'User 41', 'user41@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(42, 'User 42', 'user42@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(43, 'User 43', 'user43@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(44, 'User 44', 'user44@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(45, 'User 45', 'user45@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(46, 'User 46', 'user46@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(47, 'User 47', 'user47@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(48, 'User 48', 'user48@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(49, 'User 49', 'user49@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04'),
(50, 'User 50', 'user50@sekolah.test', '2026-09-23 02:09:04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3R6z7qQ9R5Kx5K5K5K', NULL, '2026-09-23 02:09:04', '2026-09-23 02:09:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `tahun_ajaran_id`, `kelas_id`, `guru_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 50, 1, 1, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(2, 50, 2, 2, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(3, 50, 3, 3, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(4, 50, 4, 4, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(5, 50, 5, 5, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(6, 50, 6, 6, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(7, 50, 7, 7, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(8, 50, 8, 8, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(9, 50, 9, 9, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(10, 50, 10, 10, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(11, 50, 11, 11, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(12, 50, 12, 12, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(13, 50, 13, 13, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(14, 50, 14, 14, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(15, 50, 15, 15, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(16, 50, 16, 16, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(17, 50, 17, 17, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(18, 50, 18, 18, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(19, 50, 19, 19, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(20, 50, 20, 20, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(21, 50, 21, 21, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(22, 50, 22, 22, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(23, 50, 23, 23, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(24, 50, 24, 24, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(25, 50, 25, 25, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(26, 50, 26, 26, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(27, 50, 27, 27, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(28, 50, 28, 28, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(29, 50, 29, 29, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(30, 50, 30, 30, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(31, 50, 31, 31, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(32, 50, 32, 32, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(33, 50, 33, 33, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(34, 50, 34, 34, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(35, 50, 35, 35, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(36, 50, 36, 36, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(37, 50, 37, 37, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(38, 50, 38, 38, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(39, 50, 39, 39, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(40, 50, 40, 40, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(41, 50, 41, 41, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(42, 50, 42, 42, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(43, 50, 43, 43, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(44, 50, 44, 44, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(45, 50, 45, 45, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(46, 50, 46, 46, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(47, 50, 47, 47, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(48, 50, 48, 48, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(49, 50, 49, 49, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54'),
(50, 50, 50, 50, '2025-07-01', NULL, 1, '2026-09-23 02:10:54', '2026-09-23 02:10:54');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_guru_user` (`user_id`);

--
-- Indeks untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_kelas_siswa_siswa` (`siswa_id`),
  ADD KEY `fk_t_kelas_siswa_tahun_ajaran` (`tahun_ajaran_id`),
  ADD KEY `fk_t_kelas_siswa_kelas` (`kelas_id`);

--
-- Indeks untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_pelanggaran_kategori` (`pelanggaran_kategori_id`);

--
-- Indeks untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_pelanggaran_siswa_tahun_ajaran` (`tahun_ajaran_id`),
  ADD KEY `fk_t_pelanggaran_siswa_siswa` (`siswa_id`),
  ADD KEY `fk_t_pelanggaran_siswa_kelas` (`kelas_id`),
  ADD KEY `fk_t_pelanggaran_siswa_pelanggaran` (`pelanggaran_id`),
  ADD KEY `fk_t_pelanggaran_siswa_kategori` (`pelanggaran_kategori_id`),
  ADD KEY `fk_t_pelanggaran_siswa_guru` (`guru_id`);

--
-- Indeks untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_wali_kelas_tahun_ajaran` (`tahun_ajaran_id`),
  ADD KEY `fk_t_wali_kelas_kelas` (`kelas_id`),
  ADD KEY `fk_t_wali_kelas_guru` (`guru_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `fk_t_guru_user` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `fk_t_kelas_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_kelas_siswa_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_kelas_siswa_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `fk_t_pelanggaran_kategori` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `fk_t_pelanggaran_siswa_guru` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pelanggaran_siswa_kategori` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pelanggaran_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pelanggaran_siswa_pelanggaran` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pelanggaran_siswa_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_pelanggaran_siswa_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `fk_t_wali_kelas_guru` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_wali_kelas_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_wali_kelas_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
