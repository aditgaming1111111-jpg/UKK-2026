-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2026 at 11:10 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukk_pelanggaran_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_01_121847_migrate_users_to_t_users_and_create_t_guru_table', 1),
(5, '2026_10_01_132222_create_sekolahku_core_tables', 1),
(6, '2026_10_01_155432_add_unique_index_to_t_pelanggaran_kode', 1),
(7, '2026_11_04_113055_add_nis_and_tanggal_lahir_to_t_siswa_table', 1),
(8, '2026_11_04_115330_create_t_log_aktivitas_table', 1),
(9, '2026_11_05_103812_add_status_kepegawaian_to_t_guru_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('eLOy3uJNuZ6BjUMm0wp35bZMYqUaezYUQXDweO98', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJIT3ZEMXZLQjVsemdWSzFwTDl5WklQVFEwc05GVzdDVWxSMGZDakF1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1793833786),
('Yv2v2OpCWo6Ip7kJK6fNRDrHz9KDXIPi2XqYa6ZD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJpN3JKdmd4NXNLN3U2NG5sVVpRc0xCaHFicFJEeExINDBMd3lCbVU0IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1793833783);

-- --------------------------------------------------------

--
-- Table structure for table `t_guru`
--

CREATE TABLE `t_guru` (
  `id` bigint UNSIGNED NOT NULL,
  `nip` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kepegawaian` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `status_kepegawaian`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(2, '202600000000000001', NULL, 'Wawan Kurniawan, S.Pd.I', 'wawankurniawan@sekolahku.com', 1, 3, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(3, '202600000000000002', NULL, 'Ai Nuryaidah, S.Pd', 'ainuryaidah@sekolahku.com', 1, 4, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(4, '202600000000000003', NULL, 'Maya Permata Endah, S. Pd', 'mayapermataendah@sekolahku.com', 1, 5, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(5, '202600000000000004', NULL, 'Dody Jaenudin, S.Pd.', 'dodyjaenudin@sekolahku.com', 1, 6, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(6, '202600000000000005', NULL, 'Dedi Kardiman, S. Pd', 'dedikardiman@sekolahku.com', 1, 7, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(7, '202600000000000006', NULL, 'Uca Musa Yusenda, S.Pd', 'ucamusayusenda@sekolahku.com', 1, 8, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(8, '202600000000000007', NULL, 'Drs. Ahmad Nur Mukhlis', 'ahmadnurmukhlis@sekolahku.com', 1, 9, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(9, '202600000000000008', NULL, 'Drs. Heri', 'heri@sekolahku.com', 1, 10, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(10, '202600000000000009', NULL, 'Asep Bastaman', 'asepbastaman@sekolahku.com', 1, 11, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(11, '202600000000000010', NULL, 'H. Encep Maemun, S.T., M.Pd', 'encepmaemun@sekolahku.com', 1, 12, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(12, '202600000000000011', NULL, 'Opik', 'opik@sekolahku.com', 1, 13, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(13, '202600000000000012', NULL, 'Ida Farida, S. Pd', 'idafarida@sekolahku.com', 1, 14, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(14, '202600000000000013', NULL, 'Idah Mahmudah, S.Pd', 'idahmahmudah@sekolahku.com', 1, 15, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(15, '202600000000000014', NULL, 'Drs. Muhamad Ali', 'muhamadali@sekolahku.com', 1, 16, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(16, '202600000000000015', NULL, 'Helmi Ali Akbar, S.Pd.I', 'helmialiakbar@sekolahku.com', 1, 17, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(17, '202600000000000016', NULL, 'Syeni Suswarini, S.Pd', 'syenisuswarini@sekolahku.com', 1, 18, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(18, '202600000000000017', NULL, 'Wiwid Yulianti, S.Pd', 'wiwidyulianti@sekolahku.com', 1, 19, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(19, '202600000000000018', NULL, 'Ansor Muhajir, S.T', 'ansormuhajir@sekolahku.com', 1, 20, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(20, '202600000000000019', NULL, 'Ogi Gaosun Gias, S.Pd.I', 'ogigaosungias@sekolahku.com', 1, 21, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(21, '202600000000000020', NULL, 'Sandi Mulyana, S.Pd', 'sandimulyana@sekolahku.com', 1, 22, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(22, '202600000000000021', NULL, 'Erna Suryani, S.Pd', 'ernasuryani@sekolahku.com', 1, 23, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(23, '202600000000000022', NULL, 'Ai Wahidah, S.Ag', 'aiwahidah@sekolahku.com', 1, 24, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(24, '202600000000000023', NULL, 'Chandra Aditya Yusup, S.Pd', 'chandraadityayusup@sekolahku.com', 1, 25, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(25, '202600000000000024', NULL, 'Arif Kusmawan, S.Pd', 'arifkusmawan@sekolahku.com', 1, 26, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(26, '202600000000000025', NULL, 'Asep Taufiq Hidayat, S.Kom', 'aseptaufiqhidayat@sekolahku.com', 1, 27, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(27, '202600000000000026', NULL, 'Rendy Ichsan Kusumah, S.T', 'rendyichsankusumah@sekolahku.com', 1, 28, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(28, '202600000000000027', NULL, 'Feri Rahmawan, S.Pd', 'ferirahmawan@sekolahku.com', 1, 29, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(29, '202600000000000028', NULL, 'Ari Sandriana, S.Kom', 'arisandriana@sekolahku.com', 1, 30, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(30, '202600000000000029', NULL, 'Yaneu Nurdian, S.Pd., M.Pd', 'yaneunurdian@sekolahku.com', 1, 31, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(31, '202600000000000030', NULL, 'Ucu Muhammad Khoer N, S.Pd', 'ucumuhammadkhoern@sekolahku.com', 1, 32, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(32, '202600000000000031', NULL, 'Mira Dwi Rahmawati, S.Ag', 'miradwirahmawati@sekolahku.com', 1, 33, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(33, '202600000000000032', NULL, 'Aldi Maulana, S.Pd', 'aldimaulana@sekolahku.com', 1, 34, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(34, '202600000000000033', NULL, 'Eli Rosliah, S.Pd', 'elirosliah@sekolahku.com', 1, 35, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(35, '202600000000000034', NULL, 'Isni Nur Fitriani, S.Pd', 'isninurfitriani@sekolahku.com', 1, 36, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(36, '202600000000000035', NULL, 'Aanisah Putri Nur Aini, S.Pd', 'aanisahputrinuraini@sekolahku.com', 1, 37, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(37, '202600000000000036', NULL, 'Yusi Luthfiani, S.Sos.I', 'yusiluthfiani@sekolahku.com', 1, 38, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(38, '202600000000000037', NULL, 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'lilisrustandymawardi@sekolahku.com', 1, 39, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(39, '202600000000000038', NULL, 'Yusrin Noer Sabrina, S.Pd', 'yusrinnoersabrina@sekolahku.com', 1, 40, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(40, '202600000000000039', NULL, 'Senny Indayani, S.Pd', 'sennyindayani@sekolahku.com', 1, 41, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(41, '202600000000000040', NULL, 'Ilma Amalia, S.Pd', 'ilmaamalia@sekolahku.com', 1, 42, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(42, '202600000000000041', NULL, 'Alvira Sofwatunnisa, S.Pd', 'alvirasofwatunnisa@sekolahku.com', 1, 43, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(43, '202600000000000042', NULL, 'Risna Andriyani, S.Pd', 'risnaandriyani@sekolahku.com', 1, 44, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(44, '202600000000000043', NULL, 'Dra. Tintin Noerhayati', 'tintinnoerhayati@sekolahku.com', 1, 45, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(45, '202600000000000044', NULL, 'Rizkia Fahira, S.Pd', 'rizkiafahira@sekolahku.com', 1, 46, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(46, '202600000000000045', NULL, 'Frasetio', 'frasetio@sekolahku.com', 1, 47, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(47, '202600000000000046', NULL, 'Ratnaningsih Hatil Aeni, S.Pd.', 'ratnaningshhatilaeni@sekolahku.com', 1, 48, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(48, '202600000000000047', NULL, 'Dheny Darajatul Hasani, S.Pd.', 'dhenydarajatulhasani@sekolahku.com', 1, 49, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(49, '202600000000000048', NULL, 'Fahrullah, S.Pd', 'fahrullah@sekolahku.com', 1, 50, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(50, '202600000000000049', NULL, 'Aan Andriana, S.T', 'aanandriana@sekolahku.com', 1, 51, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(51, '202600000000000050', NULL, 'Fitri Fauziah, S.T', 'fitrifauziah@sekolahku.com', 1, 52, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(52, '202600000000000051', NULL, 'Nida Aulia, S.Pd', 'nidaaulia@sekolahku.com', 1, 53, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(53, '202600000000000052', NULL, 'Sri Nurrizki Putri Krisnadi, S.Pd', 'srinurrizkiputrikrisnadi@sekolahku.com', 1, 54, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(54, '202600000000000053', NULL, 'Riesy Putri Navia, S.Kom', 'riesyputrinavia@sekolahku.com', 1, 55, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(55, '202600000000000054', NULL, 'Rurry Febriani Maryadi, S.Pd', 'rurryfebrianimaryadi@sekolahku.com', 1, 56, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(56, '202600000000000055', NULL, 'Irvan Rizal, S.Pd', 'irvanrizal@sekolahku.com', 1, 57, '2026-11-04 15:01:22', '2026-11-04 15:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama_kelas`, `tingkat`, `jurusan`, `created_at`, `updated_at`) VALUES
(1, 'XII RPL 1', '12', 'Rekayasa Perangkat Lunak', '2026-11-04 08:03:36', '2026-11-04 08:03:36'),
(2, 'XII RPL 2', '12', 'Rekayasa Perangkat Lunak', '2026-11-04 08:03:52', '2026-11-04 08:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `kelas_id`, `tahun_ajaran_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 08:10:57', '2026-11-04 08:10:57'),
(2, 2, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 08:11:16', '2026-11-04 08:11:16'),
(3, 27, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 08:11:35', '2026-11-04 08:11:35'),
(4, 75, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 08:22:23', '2026-11-04 08:22:23'),
(5, 76, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:44:19', '2026-11-04 15:44:19'),
(6, 77, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:44:41', '2026-11-04 15:44:41'),
(7, 51, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:44:59', '2026-11-04 15:44:59'),
(8, 52, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:45:17', '2026-11-04 15:45:17'),
(9, 53, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:46:00', '2026-11-04 15:46:00'),
(10, 78, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:46:21', '2026-11-04 15:46:21'),
(11, 79, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:46:42', '2026-11-04 15:46:42'),
(12, 80, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:47:09', '2026-11-04 15:47:09'),
(13, 54, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:47:35', '2026-11-04 15:47:35'),
(14, 81, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:47:54', '2026-11-04 15:47:54'),
(15, 82, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:48:19', '2026-11-04 15:48:19'),
(16, 83, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:48:45', '2026-11-04 15:48:45'),
(17, 55, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:49:04', '2026-11-04 15:49:04'),
(18, 84, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:49:25', '2026-11-04 15:49:25'),
(19, 56, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:49:42', '2026-11-04 15:49:42'),
(20, 85, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:50:02', '2026-11-04 15:50:02'),
(21, 86, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:50:23', '2026-11-04 15:50:23'),
(22, 57, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:50:48', '2026-11-04 15:50:48'),
(23, 87, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:51:07', '2026-11-04 15:51:07'),
(24, 58, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:51:35', '2026-11-04 15:51:35'),
(25, 59, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:51:56', '2026-11-04 15:51:56'),
(26, 88, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:52:18', '2026-11-04 15:52:18'),
(27, 89, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:52:39', '2026-11-04 15:52:39'),
(28, 60, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:52:57', '2026-11-04 15:52:57'),
(29, 90, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:53:14', '2026-11-04 15:53:14'),
(30, 61, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:53:31', '2026-11-04 15:53:31'),
(31, 91, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:53:50', '2026-11-04 15:53:50'),
(32, 92, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:54:08', '2026-11-04 15:54:08'),
(33, 62, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:54:29', '2026-11-04 15:54:29'),
(34, 93, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:54:47', '2026-11-04 15:54:47'),
(35, 63, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:03', '2026-11-04 15:55:03'),
(36, 94, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:20', '2026-11-04 15:55:20'),
(37, 64, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:38', '2026-11-04 15:55:38'),
(38, 65, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:57', '2026-11-04 15:55:57'),
(39, 66, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:56:13', '2026-11-04 15:56:13'),
(40, 95, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:56:33', '2026-11-04 15:56:33'),
(41, 67, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:56:55', '2026-11-04 15:56:55'),
(42, 68, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:10', '2026-11-04 15:57:10'),
(43, 96, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:28', '2026-11-04 15:57:28'),
(44, 69, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:43', '2026-11-04 15:57:43'),
(45, 70, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:58', '2026-11-04 15:57:58'),
(46, 71, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:58:13', '2026-11-04 15:58:13'),
(47, 72, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:58:30', '2026-11-04 15:58:30'),
(48, 73, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:58:45', '2026-11-04 15:58:45'),
(49, 97, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:59:04', '2026-11-04 15:59:04'),
(50, 74, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:59:22', '2026-11-04 15:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `t_log_aktivitas`
--

CREATE TABLE `t_log_aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `aktivitas` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_log_aktivitas`
--

INSERT INTO `t_log_aktivitas` (`id`, `user_id`, `aktivitas`, `deskripsi`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 07:59:54'),
(2, 1, 'Kelas ditambahkan', 'Data kelas ID #1 berhasil ditambahkan.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:03:36'),
(3, 1, 'Kelas ditambahkan', 'Data kelas ID #2 berhasil ditambahkan.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:03:52'),
(4, 1, 'Tahun ajaran ditambahkan', 'Periode tahun ajaran ID #1 berhasil ditambahkan.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:04:43'),
(5, 1, 'Wali kelas ditugaskan', 'Penugasan wali kelas ID #1 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:05:11'),
(6, 1, 'Wali kelas ditugaskan', 'Penugasan wali kelas ID #2 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:05:38'),
(7, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #1 dicatat dengan ID #1.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:10:58'),
(8, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #2 dicatat dengan ID #2.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:11:16'),
(9, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #27 dicatat dengan ID #3.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:11:35'),
(10, 1, 'Status siswa diubah', 'Status siswa ID #27 diubah menjadi nonaktif.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:12:28'),
(11, 1, 'Status siswa diubah', 'Status siswa ID #27 diubah menjadi aktif.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:12:36'),
(12, 1, 'Siswa diperbarui', 'Data siswa ID #1 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:21:07'),
(13, 1, 'Siswa diperbarui', 'Data siswa ID #1 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:21:17'),
(14, 1, 'Siswa diperbarui', 'Data siswa ID #2 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:21:33'),
(15, 1, 'Siswa diperbarui', 'Data siswa ID #27 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:21:49'),
(16, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #75 dicatat dengan ID #4.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 08:22:23'),
(17, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:43:31'),
(18, 1, 'Guru dihapus', 'Identitas Guru ID #1 tanpa histori berhasil dihapus.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:43:54'),
(19, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #76 dicatat dengan ID #5.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:44:19'),
(20, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #77 dicatat dengan ID #6.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:44:41'),
(21, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #51 dicatat dengan ID #7.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:44:59'),
(22, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #52 dicatat dengan ID #8.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:45:17'),
(23, 1, 'Siswa diperbarui', 'Data siswa ID #52 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:45:25'),
(24, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #53 dicatat dengan ID #9.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:46:00'),
(25, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #78 dicatat dengan ID #10.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:46:21'),
(26, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #79 dicatat dengan ID #11.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:46:42'),
(27, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #80 dicatat dengan ID #12.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:47:09'),
(28, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #54 dicatat dengan ID #13.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:47:35'),
(29, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #81 dicatat dengan ID #14.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:47:54'),
(30, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #82 dicatat dengan ID #15.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:48:19'),
(31, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #83 dicatat dengan ID #16.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:48:45'),
(32, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #55 dicatat dengan ID #17.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:49:04'),
(33, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #84 dicatat dengan ID #18.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:49:25'),
(34, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #56 dicatat dengan ID #19.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:49:42'),
(35, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #85 dicatat dengan ID #20.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:50:02'),
(36, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #86 dicatat dengan ID #21.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:50:23'),
(37, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #57 dicatat dengan ID #22.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:50:48'),
(38, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #87 dicatat dengan ID #23.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:51:07'),
(39, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #58 dicatat dengan ID #24.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:51:35'),
(40, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #59 dicatat dengan ID #25.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:51:56'),
(41, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #88 dicatat dengan ID #26.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:52:18'),
(42, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #89 dicatat dengan ID #27.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:52:39'),
(43, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #60 dicatat dengan ID #28.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:52:57'),
(44, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #90 dicatat dengan ID #29.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:53:14'),
(45, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #61 dicatat dengan ID #30.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:53:31'),
(46, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #91 dicatat dengan ID #31.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:53:50'),
(47, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #92 dicatat dengan ID #32.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:54:08'),
(48, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #62 dicatat dengan ID #33.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:54:29'),
(49, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #93 dicatat dengan ID #34.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:54:47'),
(50, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #63 dicatat dengan ID #35.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:55:03'),
(51, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #94 dicatat dengan ID #36.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:55:20'),
(52, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #64 dicatat dengan ID #37.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:55:38'),
(53, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #65 dicatat dengan ID #38.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:55:57'),
(54, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #66 dicatat dengan ID #39.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:56:13'),
(55, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #95 dicatat dengan ID #40.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:56:33'),
(56, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #67 dicatat dengan ID #41.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:56:55'),
(57, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #68 dicatat dengan ID #42.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:57:10'),
(58, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #96 dicatat dengan ID #43.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:57:28'),
(59, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #69 dicatat dengan ID #44.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:57:43'),
(60, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #70 dicatat dengan ID #45.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:57:58'),
(61, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #71 dicatat dengan ID #46.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:58:13'),
(62, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #72 dicatat dengan ID #47.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:58:30'),
(63, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #73 dicatat dengan ID #48.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:58:45'),
(64, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #97 dicatat dengan ID #49.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:59:04'),
(65, 1, 'Penempatan kelas ditambahkan', 'Penempatan siswa ID #74 dicatat dengan ID #50.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 15:59:22'),
(66, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 16:07:10'),
(67, 30, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 16:07:27'),
(68, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 16:09:43');

-- --------------------------------------------------------

--
-- Table structure for table `t_log_pelanggaran`
--

CREATE TABLE `t_log_pelanggaran` (
  `id` bigint UNSIGNED NOT NULL,
  `pelanggaran_siswa_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `aksi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_sebelum` text COLLATE utf8mb4_unicode_ci,
  `data_sesudah` text COLLATE utf8mb4_unicode_ci,
  `alasan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `kode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelanggaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `poin` smallint UNSIGNED NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `kategori_id`, `kode`, `nama_pelanggaran`, `deskripsi`, `poin`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'PLG001', 'Terlambat datang ke sekolah', 'Siswa datang melewati waktu masuk sekolah.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(2, 1, 'PLG002', 'Tidak mengikuti upacara', 'Siswa tidak mengikuti upacara tanpa alasan yang jelas.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(3, 1, 'PLG003', 'Tidak mengikuti kegiatan sekolah', 'Siswa tidak mengikuti kegiatan wajib sekolah.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(4, 1, 'PLG004', 'Membolos pelajaran', 'Siswa meninggalkan atau tidak mengikuti pelajaran tanpa izin.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(5, 1, 'PLG005', 'Meninggalkan sekolah tanpa izin', 'Siswa keluar dari lingkungan sekolah tanpa izin.', 15, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(6, 2, 'PLG006', 'Tidak memakai atribut lengkap', 'Siswa tidak menggunakan atribut sekolah secara lengkap.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(7, 2, 'PLG007', 'Seragam tidak sesuai ketentuan', 'Siswa menggunakan seragam yang tidak sesuai aturan.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(8, 2, 'PLG008', 'Rambut tidak sesuai aturan', 'Penampilan rambut siswa tidak sesuai ketentuan sekolah.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(9, 2, 'PLG009', 'Memakai aksesoris berlebihan', 'Siswa menggunakan aksesoris yang tidak diperbolehkan.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(10, 2, 'PLG010', 'Mengubah model seragam sekolah', 'Siswa mengubah bentuk atau model seragam sekolah.', 15, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(11, 3, 'PLG011', 'Membuat kegaduhan di kelas', 'Siswa membuat keributan yang mengganggu kegiatan belajar.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(12, 3, 'PLG012', 'Tidak mengikuti aturan kelas', 'Siswa melanggar peraturan yang berlaku di kelas.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(13, 3, 'PLG013', 'Membawa barang yang dilarang', 'Siswa membawa barang yang tidak diperbolehkan sekolah.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(14, 3, 'PLG014', 'Mengganggu kegiatan belajar', 'Siswa mengganggu proses pembelajaran.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(15, 3, 'PLG015', 'Berkelahi di lingkungan sekolah', 'Siswa terlibat perkelahian di lingkungan sekolah.', 15, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(16, 4, 'PLG016', 'Berkata tidak sopan', 'Siswa menggunakan perkataan yang tidak pantas.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(17, 4, 'PLG017', 'Tidak menghormati guru', 'Siswa menunjukkan sikap tidak hormat kepada guru.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(18, 4, 'PLG018', 'Mengejek teman', 'Siswa mengejek atau menghina teman.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(19, 4, 'PLG019', 'Melakukan perundungan', 'Siswa melakukan tindakan perundungan terhadap orang lain.', 15, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(20, 4, 'PLG020', 'Mengancam atau mengintimidasi teman', 'Siswa mengancam atau mengintimidasi teman.', 15, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(21, 5, 'PLG021', 'Membuang sampah sembarangan', 'Siswa membuang sampah tidak pada tempatnya.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(22, 5, 'PLG022', 'Tidak melaksanakan piket', 'Siswa tidak melaksanakan tugas piket kelas.', 5, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(23, 5, 'PLG023', 'Mencoret meja atau kursi', 'Siswa mencoret fasilitas meja atau kursi sekolah.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(24, 5, 'PLG024', 'Merusak fasilitas kebersihan', 'Siswa merusak fasilitas kebersihan sekolah.', 10, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(25, 5, 'PLG025', 'Merusak fasilitas sekolah', 'Siswa merusak fasilitas milik sekolah.', 15, 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama_kategori`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan', 'Pelanggaran terhadap aturan waktu dan kedisiplinan siswa.', 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(2, 'Kerapian', 'Pelanggaran terkait penampilan dan atribut sekolah.', 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(3, 'Ketertiban', 'Pelanggaran terhadap ketentuan dan tata tertib sekolah.', 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(4, 'Etika dan Sopan Santun', 'Pelanggaran terhadap norma, etika, dan sikap siswa.', 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53'),
(5, 'Kebersihan dan Lingkungan', 'Pelanggaran terkait kebersihan dan kelestarian lingkungan sekolah.', 1, '2026-11-04 15:02:53', '2026-11-04 15:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_siswa_id` bigint UNSIGNED NOT NULL,
  `pelanggaran_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `tanggal_pelanggaran` date NOT NULL,
  `tanggal_dicatat` datetime NOT NULL,
  `poin_saat_dicatat` smallint UNSIGNED NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `tindakan` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `nisn` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tahun_masuk` smallint UNSIGNED NOT NULL,
  `status_siswa` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nis` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nisn`, `nama`, `jenis_kelamin`, `alamat`, `tahun_masuk`, `status_siswa`, `created_at`, `updated_at`, `nis`, `tanggal_lahir`) VALUES
(1, '0000000001', 'ADITTIA NUR LANTIP', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-04 15:10:12', '2026-11-04 08:21:17', '2024000001', '2008-01-15'),
(2, '0000000002', 'AGNIA ROSYA NOERILLAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-04 15:10:12', '2026-11-04 08:21:33', '2024000002', '2008-03-22'),
(27, '0000000027', 'ALFIN RAHADIAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-04 15:10:12', '2026-11-04 08:21:49', '2024000027', '2009-01-12'),
(51, '0000000003', 'ANISA AMIRULLA', 'P', 'Jl. Tentara Pelajar, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000003', '2008-05-10'),
(52, '0000000004', 'ARADEA KANDI', 'L', 'Jl. RE Martadinata, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:45:25', '2024000004', '2008-07-19'),
(53, '0000000005', 'ARFA DIESA OKMALELIZA', 'P', 'Jl. Sutisna Senjaya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000005', '2008-09-12'),
(54, '0000000006', 'FITRI NUR SYAMI', 'P', 'Jl. Paseh, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000006', '2008-11-25'),
(55, '0000000007', 'JUNITA PUTRI NUR RAHMAN', 'P', 'Jl. Mitra Batik, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000007', '2008-02-08'),
(56, '0000000008', 'MOHAMMAD ATALARIK ALI SYAHBANA', 'L', 'Jl. Perintis Kemerdekaan, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000008', '2008-04-17'),
(57, '0000000009', 'MUHAMAD FAHRIL FAHRUROZI', 'L', 'Jl. Gunung Sabeulah, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000009', '2008-06-29'),
(58, '0000000010', 'MUHAMMAD ADNAN SYAEFUL ISLAMI', 'L', 'Jl. Letnan Harun, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000010', '2008-08-13'),
(59, '0000000011', 'MUHAMMAD ZAHRU AQILA FIKRI', 'L', 'Jl. BKR, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000011', '2008-10-21'),
(60, '0000000012', 'NADIA ZULFA SORAYA', 'P', 'Jl. Cieunteung, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000012', '2008-12-05'),
(61, '0000000013', 'NAZRIL ILHAM', 'L', 'Jl. Siliwangi, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000013', '2008-01-28'),
(62, '0000000014', 'PUSPITA DEWI', 'P', 'Jl. Gobras, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000014', '2008-03-14'),
(63, '0000000015', 'RAKHA MUHAMMAD HAYKAL', 'L', 'Jl. Indihiang, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000015', '2008-05-26'),
(64, '0000000016', 'RANI MEILANI', 'P', 'Jl. Mangkubumi, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000016', '2008-07-07'),
(65, '0000000017', 'REIHAN MUZAKI', 'L', 'Jl. Tamansari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000017', '2008-09-30'),
(66, '0000000018', 'RESA JULIANSAH', 'L', 'Jl. Kawalu, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000018', '2008-11-11'),
(67, '0000000019', 'RIZKI PERMATA SUGIANTO', 'L', 'Jl. Cibeureum, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000019', '2008-02-19'),
(68, '0000000020', 'SAHIRA FIRAJ', 'P', 'Jl. Cipedes, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000020', '2008-04-03'),
(69, '0000000021', 'SENDI MARDIAN', 'L', 'Jl. Awipari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000021', '2008-06-16'),
(70, '0000000022', 'SHAHAIDAN NIZZAR', 'L', 'Jl. Sukarindik, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000022', '2008-08-27'),
(71, '0000000023', 'SHERLY SATRIANI', 'P', 'Jl. Sukamulya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000023', '2008-10-09'),
(72, '0000000024', 'SOFI NURJANAH', 'P', 'Jl. Mulyasari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000024', '2008-12-18'),
(73, '0000000025', 'SURAIYA SALSABILA AZZAHRA', 'P', 'Jl. Cikalang, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000025', '2009-02-12'),
(74, '0000000026', 'TONI ANTONIO', 'L', 'Jl. Sukaraja, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000026', '2009-04-24'),
(75, '0000000028', 'ALISA RAMADHANI', 'P', 'Jl. Sambongpari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000028', '2009-02-18'),
(76, '0000000029', 'AMA AURA LIVIA', 'P', 'Jl. Sukamaju, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000029', '2009-03-21'),
(77, '0000000030', 'ANIS KHOERUNNISA NUR RAMADANI', 'P', 'Jl. Cibunigeulis, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000030', '2009-04-15'),
(78, '0000000031', 'AZMIL AMIRA', 'P', 'Jl. Parakannyasag, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000031', '2009-05-09'),
(79, '0000000032', 'AZRIL AKBAR FAWJI ABDILLAH', 'L', 'Jl. Cibeureum, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000032', '2009-06-24'),
(80, '0000000033', 'ERLANGGA SAPUTRA HIDAYAT', 'L', 'Jl. Bantarsari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000033', '2009-07-11'),
(81, '0000000034', 'HILMA HUMMA TUNNAJAH', 'P', 'Jl. Sukaraja, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000034', '2009-08-19'),
(82, '0000000035', 'ILMA NAJYA', 'P', 'Jl. Setiajaya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000035', '2009-09-13'),
(83, '0000000036', 'JULIANA DHARMA PANGESTU', 'P', 'Jl. Sukahurip, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000036', '2009-10-22'),
(84, '0000000037', 'KAYLA NUR FADILAH', 'P', 'Jl. Cikadongdong, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000037', '2009-11-16'),
(85, '0000000038', 'MOZA DINA NABILLAH', 'P', 'Jl. Cintaraja, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000038', '2009-12-05'),
(86, '0000000039', 'MUHAMAD ALFARIZI', 'L', 'Jl. Mangkubumi, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000039', '2009-01-27'),
(87, '0000000040', 'MUHAMAD FAHRIN ADAM', 'L', 'Jl. Urug, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000040', '2009-02-14'),
(88, '0000000041', 'MUHAMMAD ZIDANE MAULANA', 'L', 'Jl. Kawalu, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000041', '2009-03-08'),
(89, '0000000042', 'MUHAMMAD ZULFAN NURALFANSYAH', 'L', 'Jl. Cibeuti, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000042', '2009-04-26'),
(90, '0000000043', 'NATIYA NUR AZKIYA', 'P', 'Jl. Sukagalih, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000043', '2009-05-17'),
(91, '0000000044', 'NOVITA PUSPITASARI', 'P', 'Jl. Leuwidahu, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000044', '2009-06-12'),
(92, '0000000045', 'NUCKY ANDREAS FEBRIAN', 'L', 'Jl. Sukamaju, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000045', '2009-07-23'),
(93, '0000000046', 'RADITYA JETI PRASETYA', 'L', 'Jl. Awipari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000046', '2009-08-07'),
(94, '0000000047', 'RANGGA RAMADIYANTO NURHAKIM', 'L', 'Jl. Cibunigeulis, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000047', '2009-09-25'),
(95, '0000000048', 'RISQI MUHAMAD IHWANI', 'L', 'Jl. Setiamulya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000048', '2009-10-14'),
(96, '0000000049', 'SALWA SABILATUL ZARKAH', 'P', 'Jl. Sukarindik, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000049', '2009-11-29'),
(97, '0000000050', 'TAZKIA ZAHRA', 'P', 'Jl. Indihiang, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000050', '2009-12-19');

-- --------------------------------------------------------

--
-- Table structure for table `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama_tahun_ajaran`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '2026/2027', '2026-07-13', '2027-06-18', 1, '2026-11-04 08:04:43', '2026-11-04 08:04:43');

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`id`, `nama`, `email`, `password`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Administrator SekolahKu', 'admin@sekolahku.com', '$2y$12$6XT1uPdDIoaORCDVwJBqoeQmihFbS9PmvQk/cL/gTCYYfp7WqqaxS', '2026-11-04 07:59:23', '2026-11-04 07:59:23', 'admin'),
(3, 'Wawan Kurniawan, S.Pd.I', 'wawankurniawan@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(4, 'Ai Nuryaidah, S.Pd', 'ainuryaidah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(5, 'Maya Permata Endah, S. Pd', 'mayapermataendah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(6, 'Dody Jaenudin, S.Pd.', 'dodyjaenudin@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(7, 'Dedi Kardiman, S. Pd', 'dedikardiman@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(8, 'Uca Musa Yusenda, S.Pd', 'ucamusayusenda@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(9, 'Drs. Ahmad Nur Mukhlis', 'ahmadnurmukhlis@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(10, 'Drs. Heri', 'heri@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(11, 'Asep Bastaman', 'asepbastaman@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(12, 'H. Encep Maemun, S.T., M.Pd', 'encepmaemun@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(13, 'Opik', 'opik@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(14, 'Ida Farida, S. Pd', 'idafarida@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(15, 'Idah Mahmudah, S.Pd', 'idahmahmudah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(16, 'Drs. Muhamad Ali', 'muhamadali@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(17, 'Helmi Ali Akbar, S.Pd.I', 'helmialiakbar@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(18, 'Syeni Suswarini, S.Pd', 'syenisuswarini@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(19, 'Wiwid Yulianti, S.Pd', 'wiwidyulianti@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(20, 'Ansor Muhajir, S.T', 'ansormuhajir@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(21, 'Ogi Gaosun Gias, S.Pd.I', 'ogigaosungias@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(22, 'Sandi Mulyana, S.Pd', 'sandimulyana@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(23, 'Erna Suryani, S.Pd', 'ernasuryani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(24, 'Ai Wahidah, S.Ag', 'aiwahidah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(25, 'Chandra Aditya Yusup, S.Pd', 'chandraadityayusup@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(26, 'Arif Kusmawan, S.Pd', 'arifkusmawan@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(27, 'Asep Taufiq Hidayat, S.Kom', 'aseptaufiqhidayat@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(28, 'Rendy Ichsan Kusumah, S.T', 'rendyichsankusumah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(29, 'Feri Rahmawan, S.Pd', 'ferirahmawan@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(30, 'Ari Sandriana, S.Kom', 'arisandriana@sekolahku.com', '$2y$12$FbTLavHo4hBqO7ldagghhuWD/bqz2hXLO2V3fJN8OOyy6hvBdE8je', '2026-11-04 15:01:22', '2026-11-04 16:07:27', 'guru'),
(31, 'Yaneu Nurdian, S.Pd., M.Pd', 'yaneunurdian@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(32, 'Ucu Muhammad Khoer N, S.Pd', 'ucumuhammadkhoern@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(33, 'Mira Dwi Rahmawati, S.Ag', 'miradwirahmawati@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(34, 'Aldi Maulana, S.Pd', 'aldimaulana@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(35, 'Eli Rosliah, S.Pd', 'elirosliah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(36, 'Isni Nur Fitriani, S.Pd', 'isninurfitriani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(37, 'Aanisah Putri Nur Aini, S.Pd', 'aanisahputrinuraini@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(38, 'Yusi Luthfiani, S.Sos.I', 'yusiluthfiani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(39, 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'lilisrustandymawardi@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(40, 'Yusrin Noer Sabrina, S.Pd', 'yusrinnoersabrina@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(41, 'Senny Indayani, S.Pd', 'sennyindayani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(42, 'Ilma Amalia, S.Pd', 'ilmaamalia@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(43, 'Alvira Sofwatunnisa, S.Pd', 'alvirasofwatunnisa@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(44, 'Risna Andriyani, S.Pd', 'risnaandriyani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(45, 'Dra. Tintin Noerhayati', 'tintinnoerhayati@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(46, 'Rizkia Fahira, S.Pd', 'rizkiafahira@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(47, 'Frasetio', 'frasetio@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(48, 'Ratnaningsih Hatil Aeni, S.Pd.', 'ratnaningshhatilaeni@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(49, 'Dheny Darajatul Hasani, S.Pd.', 'dhenydarajatulhasani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(50, 'Fahrullah, S.Pd', 'fahrullah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(51, 'Aan Andriana, S.T', 'aanandriana@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(52, 'Fitri Fauziah, S.T', 'fitrifauziah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(53, 'Nida Aulia, S.Pd', 'nidaaulia@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(54, 'Sri Nurrizki Putri Krisnadi, S.Pd', 'srinurrizkiputrikrisnadi@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(55, 'Riesy Putri Navia, S.Kom', 'riesyputrinavia@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(56, 'Rurry Febriani Maryadi, S.Pd', 'rurryfebrianimaryadi@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(57, 'Irvan Rizal, S.Pd', 'irvanrizal@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru');

-- --------------------------------------------------------

--
-- Table structure for table `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `guru_id`, `kelas_id`, `tahun_ajaran_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 29, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 08:05:11', '2026-11-04 08:05:11'),
(2, 33, 2, 1, '2027-06-18', NULL, 1, '2026-11-04 08:05:38', '2026-11-04 08:05:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_guru_nip_unique` (`nip`),
  ADD UNIQUE KEY `t_guru_user_id_unique` (`user_id`);

--
-- Indexes for table `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_kelas_siswa_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  ADD KEY `t_kelas_siswa_siswa_id_tahun_ajaran_id_index` (`siswa_id`,`tahun_ajaran_id`),
  ADD KEY `t_kelas_siswa_kelas_id_tahun_ajaran_id_index` (`kelas_id`,`tahun_ajaran_id`);

--
-- Indexes for table `t_log_aktivitas`
--
ALTER TABLE `t_log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_log_aktivitas_created_idx` (`created_at`,`id`),
  ADD KEY `t_log_aktivitas_user_created_idx` (`user_id`,`created_at`);

--
-- Indexes for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_log_ps_created_at_idx` (`pelanggaran_siswa_id`,`created_at`),
  ADD KEY `t_log_user_created_at_idx` (`user_id`,`created_at`);

--
-- Indexes for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_pelanggaran_kode_unique` (`kode`),
  ADD KEY `t_pelanggaran_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_pelanggaran_siswa_pelanggaran_id_foreign` (`pelanggaran_id`),
  ADD KEY `t_pelanggaran_siswa_guru_id_foreign` (`guru_id`),
  ADD KEY `t_ps_kelas_status_tanggal_idx` (`kelas_siswa_id`,`status`,`tanggal_pelanggaran`),
  ADD KEY `t_ps_status_created_at_idx` (`status`,`created_at`);

--
-- Indexes for table `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_siswa_nisn_unique` (`nisn`),
  ADD UNIQUE KEY `t_siswa_nis_unique` (`nis`);

--
-- Indexes for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_wali_kelas_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  ADD KEY `t_wali_kelas_kelas_id_tahun_ajaran_id_status_aktif_index` (`kelas_id`,`tahun_ajaran_id`,`status_aktif`),
  ADD KEY `t_wali_kelas_guru_id_tahun_ajaran_id_index` (`guru_id`,`tahun_ajaran_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_log_aktivitas`
--
ALTER TABLE `t_log_aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `t_guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `t_kelas_siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_kelas_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_kelas_siswa_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `t_log_aktivitas`
--
ALTER TABLE `t_log_aktivitas`
  ADD CONSTRAINT `t_log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  ADD CONSTRAINT `t_log_pelanggaran_pelanggaran_siswa_id_foreign` FOREIGN KEY (`pelanggaran_siswa_id`) REFERENCES `t_pelanggaran_siswa` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_log_pelanggaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `t_pelanggaran_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_pelanggaran_siswa_kelas_siswa_id_foreign` FOREIGN KEY (`kelas_siswa_id`) REFERENCES `t_kelas_siswa` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_pelanggaran_siswa_pelanggaran_id_foreign` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `t_wali_kelas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_wali_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `t_wali_kelas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
