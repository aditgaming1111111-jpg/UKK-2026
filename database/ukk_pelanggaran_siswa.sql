-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql212.infinityfree.com
-- Generation Time: Nov 12, 2026 at 07:55 AM
-- Server version: 11.4.13-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_43399599_db_ukk_2026`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sekolahku-cache-0a80c28950b34853701d25c2da598c37fd448ed5', 'i:4;', 1794378973),
('sekolahku-cache-0a80c28950b34853701d25c2da598c37fd448ed5:timer', 'i:1794378973;', 1794378973),
('sekolahku-cache-252989138f7fce46f093b0839326b9e1a7562cbc', 'i:1;', 1794400436),
('sekolahku-cache-252989138f7fce46f093b0839326b9e1a7562cbc:timer', 'i:1794400436;', 1794400436),
('sekolahku-cache-6cc10f9c124210bee0d8f57f469944f822535c72', 'i:2;', 1794365012),
('sekolahku-cache-6cc10f9c124210bee0d8f57f469944f822535c72:timer', 'i:1794365012;', 1794365012),
('sekolahku-cache-a4fff596b3ff07a4d2049cabf78d9a2aee1d5a47', 'i:1;', 1794289199),
('sekolahku-cache-a4fff596b3ff07a4d2049cabf78d9a2aee1d5a47:timer', 'i:1794289199;', 1794289199),
('sekolahku-cache-a7706915597dcadc683ef36cb841c0e487563778', 'i:2;', 1794378971),
('sekolahku-cache-a7706915597dcadc683ef36cb841c0e487563778:timer', 'i:1794378971;', 1794378971),
('sekolahku-cache-ad34d072febaf489d48df3197ece2049a759605e', 'i:2;', 1794314827),
('sekolahku-cache-ad34d072febaf489d48df3197ece2049a759605e:timer', 'i:1794314827;', 1794314827),
('sekolahku-cache-c42b9d556b5900d1cb5c62e7dba35f00cf1b59fd', 'i:1;', 1794289249),
('sekolahku-cache-c42b9d556b5900d1cb5c62e7dba35f00cf1b59fd:timer', 'i:1794289249;', 1794289249);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
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
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
('ABRh7FRmvKBOHXISAFwtytW5eHvsijMnnuPFuRzO', NULL, '180.245.116.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJybUZBMmhLNmVyaEszM0NYS095VVN0aWQ0UXFSeW5FZzBxd09vYXlDIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3Nla29sYWhrdS5zaXRlLmplXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifX0=', 1794457527),
('cd5Nmond1g2KSseRKECBvxw6vyXv9G6FF34LghXJ', NULL, '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3dXVYUG03TXpUWjVzMHBNUFFBZ1pJY2czd2dMZlR0WlZ4bTQwdW90IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3Nla29sYWhrdS5zaXRlLmplXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifX0=', 1794457366),
('dFbEOLB1mbEPgxqkQENFSBhA5knClRGnqFsb2sai', NULL, '180.245.116.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0', 'eyJfdG9rZW4iOiJqQVpLemhjd3J0NGJySFdqWlNRT1JSQTNoV1FDVTBGSHlNQnFuTHg3IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3Nla29sYWhrdS5zaXRlLmplXC9ndXJ1In0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvc2Vrb2xhaGt1LnNpdGUuamVcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1794457515),
('qKZubTIQ8UjdX5VHylQJovmEtsFico5ZLHpVwYyz', NULL, '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJnU3k3aTNGalNJbDBIT3lzNEs4b3h1VHV6YmRDNmFBMFo0SFV0QXV3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zZWtvbGFoa3Uuc2l0ZS5qZVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1794485948);

-- --------------------------------------------------------

--
-- Table structure for table `t_guru`
--

CREATE TABLE `t_guru` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(18) DEFAULT NULL,
  `status_kepegawaian` varchar(20) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` bigint(20) UNSIGNED NOT NULL,
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
(29, '202600000000000028', 'PNS', 'Ari Sandriana, S.Kom', 'arisandriana@sekolahku.com', 1, 30, '2026-11-04 15:01:22', '2026-11-09 00:53:30'),
(30, '202600000000000029', NULL, 'Yaneu Nurdian, S.Pd., M.Pd', 'yaneunurdian@sekolahku.com', 1, 31, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(31, '202600000000000030', NULL, 'Ucu Muhammad Khoer N, S.Pd', 'ucumuhammadkhoern@sekolahku.com', 1, 32, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(32, '202600000000000031', NULL, 'Mira Dwi Rahmawati, S.Ag', 'miradwirahmawati@sekolahku.com', 1, 33, '2026-11-04 15:01:22', '2026-11-04 15:01:22'),
(33, '202600000000000032', 'PNS', 'Aldi Maulana, S.Pd', 'aldimaulana@sekolahku.com', 1, 34, '2026-11-04 15:01:22', '2026-11-09 00:53:21'),
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
(50, '202600000000000049', NULL, 'Aan Andriana, S.T', 'aanandriana@sekolahku.com', 1, 51, '2026-11-04 15:01:22', '2026-11-10 19:56:06'),
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama_kelas`, `tingkat`, `jurusan`, `created_at`, `updated_at`) VALUES
(1, 'XII RPL 1', '12', 'Rekayasa Perangkat Lunak', '2026-11-04 08:03:36', '2026-11-04 08:03:36'),
(2, 'XII RPL 2', '12', 'Rekayasa Perangkat Lunak', '2026-11-04 08:03:52', '2026-11-04 08:03:52'),
(3, 'XII TKR 3', '12', 'Teknik Kendaraan Ringan', '2026-11-09 07:22:48', '2026-11-09 01:53:26'),
(4, 'XII TKJ 4', '12', 'Teknik Komputer dan Jaringan', '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(5, 'XII TSM 3', '12', 'Teknik Sepeda Motor', '2026-11-09 07:49:39', '2026-11-09 07:49:39');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `kelas_id`, `tahun_ajaran_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 08:10:57', '2026-11-04 08:10:57'),
(2, 2, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 08:11:16', '2026-11-04 08:11:16'),
(3, 3, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 08:11:35', '2026-11-04 08:11:35'),
(4, 28, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 08:22:23', '2026-11-04 08:22:23'),
(5, 29, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:44:19', '2026-11-04 15:44:19'),
(6, 30, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:44:41', '2026-11-04 15:44:41'),
(7, 4, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:44:59', '2026-11-04 15:44:59'),
(8, 5, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:45:17', '2026-11-04 15:45:17'),
(9, 6, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:46:00', '2026-11-04 15:46:00'),
(10, 31, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:46:21', '2026-11-04 15:46:21'),
(11, 32, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:46:42', '2026-11-04 15:46:42'),
(12, 33, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:47:09', '2026-11-04 15:47:09'),
(13, 7, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:47:35', '2026-11-04 15:47:35'),
(14, 34, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:47:54', '2026-11-04 15:47:54'),
(15, 35, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:48:19', '2026-11-04 15:48:19'),
(16, 36, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:48:45', '2026-11-04 15:48:45'),
(17, 8, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:49:04', '2026-11-04 15:49:04'),
(18, 37, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:49:25', '2026-11-04 15:49:25'),
(19, 9, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:49:42', '2026-11-04 15:49:42'),
(20, 38, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:50:02', '2026-11-04 15:50:02'),
(21, 39, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:50:23', '2026-11-04 15:50:23'),
(22, 10, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:50:48', '2026-11-04 15:50:48'),
(23, 40, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:51:07', '2026-11-04 15:51:07'),
(24, 11, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:51:35', '2026-11-04 15:51:35'),
(25, 12, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:51:56', '2026-11-04 15:51:56'),
(26, 41, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:52:18', '2026-11-04 15:52:18'),
(27, 42, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:52:39', '2026-11-04 15:52:39'),
(28, 13, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:52:57', '2026-11-04 15:52:57'),
(29, 43, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:53:14', '2026-11-04 15:53:14'),
(30, 14, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:53:31', '2026-11-04 15:53:31'),
(31, 44, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:53:50', '2026-11-04 15:53:50'),
(32, 45, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:54:08', '2026-11-04 15:54:08'),
(33, 15, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:54:29', '2026-11-04 15:54:29'),
(34, 46, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:54:47', '2026-11-04 15:54:47'),
(35, 16, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:03', '2026-11-04 15:55:03'),
(36, 47, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:20', '2026-11-04 15:55:20'),
(37, 17, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:38', '2026-11-04 15:55:38'),
(38, 18, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:55:57', '2026-11-04 15:55:57'),
(39, 19, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:56:13', '2026-11-04 15:56:13'),
(40, 48, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:56:33', '2026-11-04 15:56:33'),
(41, 20, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:56:55', '2026-11-04 15:56:55'),
(42, 21, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:10', '2026-11-04 15:57:10'),
(43, 49, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:28', '2026-11-04 15:57:28'),
(44, 22, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:43', '2026-11-04 15:57:43'),
(45, 23, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:57:58', '2026-11-04 15:57:58'),
(46, 24, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:58:13', '2026-11-04 15:58:13'),
(47, 25, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:58:30', '2026-11-04 15:58:30'),
(48, 26, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:58:45', '2026-11-04 15:58:45'),
(49, 50, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 15:59:04', '2026-11-04 15:59:04'),
(50, 27, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 15:59:22', '2026-11-04 15:59:22'),
(51, 51, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(52, 52, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(53, 53, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(54, 54, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(55, 55, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(56, 56, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(57, 57, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(58, 58, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(59, 59, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(60, 60, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(61, 61, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(62, 62, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(63, 63, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(64, 64, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(65, 65, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(66, 66, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(67, 67, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(68, 68, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(69, 69, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(70, 70, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(71, 71, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(72, 72, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(73, 73, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(74, 74, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(75, 75, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(76, 76, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(77, 77, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(78, 78, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(79, 79, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(80, 80, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(81, 81, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(82, 82, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(83, 83, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(114, 84, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(115, 85, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(116, 86, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(117, 87, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(118, 88, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(119, 89, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(120, 90, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(121, 91, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(122, 92, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(123, 93, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(124, 94, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(125, 95, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(126, 96, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(127, 97, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(128, 98, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(129, 99, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(130, 100, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(131, 101, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(132, 102, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(133, 103, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(134, 104, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(135, 105, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(136, 106, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(137, 107, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(138, 108, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(139, 109, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(140, 110, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(141, 111, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(142, 112, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(143, 113, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(144, 114, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(145, 115, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(146, 116, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(177, 117, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(178, 118, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(179, 119, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(180, 120, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(181, 121, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(182, 122, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(183, 123, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(184, 124, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(185, 125, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(186, 126, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(187, 127, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(188, 128, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(189, 129, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(190, 130, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(191, 131, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(192, 132, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(193, 133, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(194, 134, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(195, 135, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(196, 136, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(197, 137, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(198, 138, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(199, 139, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(200, 140, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(201, 141, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(202, 142, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(203, 143, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(204, 144, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(205, 145, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(206, 146, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39'),
(207, 147, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39');

-- --------------------------------------------------------

--
-- Table structure for table `t_log_aktivitas`
--

CREATE TABLE `t_log_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `aktivitas` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
(68, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-04 16:09:43'),
(69, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:19:31'),
(70, 1, 'Tanggal penugasan wali kelas dikoreksi', 'Penugasan ID #2 (guru #33, kelas #2, tahun ajaran #1) dikoreksi. Sebelum: {\"tanggal_mulai\":\"2027-06-18\",\"tanggal_selesai\":null,\"status_aktif\":true}. Sesudah: {\"tanggal_mulai\":\"2026-07-13\",\"tanggal_selesai\":null,\"status_aktif\":true}.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:19:56'),
(71, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:31:19'),
(72, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:32:26'),
(73, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:39:18'),
(74, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:39:25'),
(75, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:50:44'),
(76, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:50:54'),
(77, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:51:07'),
(78, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:51:29'),
(79, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-08 23:59:52'),
(80, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:00:00'),
(81, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:01:19'),
(82, 3, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:01:33'),
(83, 3, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:02:52'),
(84, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:02:58'),
(85, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:08:13');
INSERT INTO `t_log_aktivitas` (`id`, `user_id`, `aktivitas`, `deskripsi`, `ip_address`, `user_agent`, `created_at`) VALUES
(86, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:08:20'),
(87, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:08:21'),
(88, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:08:57'),
(89, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:09:03'),
(90, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:29:05'),
(91, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:29:18'),
(92, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:41:01'),
(93, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:41:20'),
(94, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:50:40'),
(95, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:50:47'),
(96, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:51:45'),
(97, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:51:51'),
(98, 1, 'Data Guru diperbarui', 'Identitas Guru ID #33 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:53:21'),
(99, 1, 'Data Guru diperbarui', 'Identitas Guru ID #29 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:53:30'),
(100, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:53:36'),
(101, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:53:46'),
(102, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #1 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:54:42'),
(103, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #2 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:55:22'),
(104, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #3 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:56:07'),
(105, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:56:09'),
(106, 3, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:56:15'),
(107, 3, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #4 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:56:40'),
(108, 3, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #5 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:57:17'),
(109, 3, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #6 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:57:31'),
(110, 3, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:58:05'),
(111, 30, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:58:23'),
(112, 30, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #7 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:58:37'),
(113, 30, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #8 berhasil dibuat.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:58:55'),
(114, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:58:57'),
(115, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 00:59:02'),
(116, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:00:27'),
(117, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:00:32'),
(118, 1, 'Kelas diperbarui', 'Data kelas ID #3 berhasil diperbarui.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:53:26'),
(119, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:54:53'),
(120, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:55:01'),
(121, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:55:03'),
(122, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-09 01:56:45'),
(123, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 11:00:25'),
(124, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 11:02:18'),
(125, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 11:02:42'),
(126, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Mobile Safari/537.36', '2026-11-10 11:05:13'),
(127, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Mobile Safari/537.36', '2026-11-10 11:05:53'),
(128, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '2026-11-10 11:13:36'),
(129, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '2026-11-10 11:14:54'),
(130, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 11:18:23'),
(131, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 12:17:35'),
(132, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 12:26:57'),
(133, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 13:11:36'),
(134, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 13:22:41'),
(135, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 13:28:16'),
(136, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-10 13:29:01'),
(137, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '172.225.78.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '2026-11-10 14:44:11'),
(138, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '172.225.78.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '2026-11-10 14:44:59'),
(139, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 18:28:44'),
(140, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 18:31:47'),
(141, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:48:41'),
(142, 1, 'Tahun ajaran ditambahkan', 'Periode tahun ajaran ID #2 berhasil ditambahkan.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:52:05'),
(143, 1, 'Guru dinonaktifkan', 'Guru ID #50 berhasil dinonaktifkan.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:55:34'),
(144, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:55:42'),
(145, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:55:57'),
(146, 1, 'Guru diaktifkan', 'Guru ID #50 berhasil diaktifkan.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:56:06'),
(147, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:56:08'),
(148, 51, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:56:14'),
(149, 51, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:56:17'),
(150, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:56:24'),
(151, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:57:02'),
(152, 30, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 19:57:10'),
(153, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:10:37'),
(154, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:10:44'),
(155, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:11:31'),
(156, 30, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:11:38'),
(157, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:11:52'),
(158, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:12:00'),
(159, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:12:40'),
(160, 43, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:12:46'),
(161, 43, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:13:17'),
(162, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:13:26'),
(163, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:30:53'),
(164, 30, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:31:01'),
(165, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:31:21'),
(166, 30, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:31:34'),
(167, 30, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #9 berhasil dibuat.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:32:06'),
(168, 30, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:32:29'),
(169, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:32:36'),
(170, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-10 20:41:41'),
(171, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '114.122.79.209', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', '2026-11-10 20:46:26'),
(172, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '114.122.79.209', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', '2026-11-10 20:48:01'),
(173, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 08:11:06'),
(174, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #10 berhasil dibuat.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 08:39:22');
INSERT INTO `t_log_aktivitas` (`id`, `user_id`, `aktivitas`, `deskripsi`, `ip_address`, `user_agent`, `created_at`) VALUES
(175, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #11 berhasil dibuat.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 09:06:11'),
(176, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 09:50:57'),
(177, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 09:51:43'),
(178, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:00:43'),
(179, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:00:59'),
(180, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:09:49'),
(181, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:13:29'),
(182, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:15:47'),
(183, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '114.122.78.221', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', '2026-11-11 10:33:09'),
(184, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '114.122.78.221', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', '2026-11-11 10:33:36'),
(185, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:34:18'),
(186, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 10:35:20'),
(187, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 12:03:17'),
(188, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #12 berhasil dibuat.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 12:07:19'),
(189, 34, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #13 berhasil dibuat.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 12:07:21'),
(190, 34, 'Pelanggaran dikoreksi', 'Catatan pelanggaran siswa ID #13 dikoreksi; rincian perubahan tersedia di log pelanggaran.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 12:08:51'),
(191, 34, 'Pelanggaran dibatalkan', 'Catatan pelanggaran siswa ID #12 dibatalkan; alasan tersimpan pada log pelanggaran.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 12:09:31'),
(192, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 12:12:36'),
(193, 3, 'Login', 'Pengguna berhasil masuk sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 13:01:38'),
(194, 3, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 13:01:47'),
(195, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 13:40:43'),
(196, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '103.162.63.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 13:43:59'),
(197, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 22:25:33'),
(198, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 22:44:05'),
(199, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 22:51:29'),
(200, 34, 'Pelanggaran dikoreksi', 'Catatan pelanggaran siswa ID #13 dikoreksi; rincian perubahan tersedia di log pelanggaran.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 22:59:45'),
(201, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 23:18:21'),
(202, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 23:25:35'),
(203, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 23:29:07'),
(204, 3, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 23:34:29'),
(205, 3, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-11 23:34:38'),
(206, 1, 'Login', 'Pengguna berhasil masuk sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 11:17:19'),
(207, 1, 'Logout', 'Pengguna keluar dari aplikasi sebagai admin.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 11:17:38'),
(208, 34, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:03:43'),
(209, 34, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:03:46'),
(210, 13, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:04:05'),
(211, 13, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #14 berhasil dibuat.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:05:08'),
(212, 13, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #15 berhasil dibuat.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:06:36'),
(213, 13, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #19 berhasil dibuat.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:18:50'),
(214, 13, 'Pelanggaran dicatat', 'Catatan pelanggaran siswa ID #20 berhasil dibuat.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:22:17'),
(215, 13, 'Pelanggaran dikoreksi', 'Catatan pelanggaran siswa ID #20 dikoreksi; rincian perubahan tersedia di log pelanggaran.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:22:36'),
(216, 13, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-12 12:22:45'),
(217, 13, 'Login', 'Pengguna berhasil masuk sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', '2026-11-12 12:25:12'),
(218, 13, 'Logout', 'Pengguna keluar dari aplikasi sebagai guru.', '180.245.116.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/27.2 Mobile/15E148 Safari/604.1', '2026-11-12 12:25:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_log_pelanggaran`
--

CREATE TABLE `t_log_pelanggaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pelanggaran_siswa_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `aksi` varchar(20) NOT NULL,
  `data_sebelum` text DEFAULT NULL,
  `data_sesudah` text DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_log_pelanggaran`
--

INSERT INTO `t_log_pelanggaran` (`id`, `pelanggaran_siswa_id`, `user_id`, `aksi`, `data_sebelum`, `data_sesudah`, `alasan`, `created_at`) VALUES
(1, 1, 34, 'tambah', NULL, '{\"kelas_siswa_id\":19,\"pelanggaran_id\":4,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:54:42\",\"poin_saat_dicatat\":10,\"keterangan\":\"Bolos pelajaran matematika\",\"tindakan\":\"Cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:54:42\",\"created_at\":\"2026-11-09 07:54:42\",\"id\":1}', 'Pencatatan awal', '2026-11-09 00:54:42'),
(2, 2, 34, 'tambah', NULL, '{\"kelas_siswa_id\":34,\"pelanggaran_id\":19,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:55:22\",\"poin_saat_dicatat\":15,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:55:22\",\"created_at\":\"2026-11-09 07:55:22\",\"id\":2}', 'Pencatatan awal', '2026-11-09 00:55:22'),
(3, 3, 34, 'tambah', NULL, '{\"kelas_siswa_id\":125,\"pelanggaran_id\":7,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:56:07\",\"poin_saat_dicatat\":5,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:56:07\",\"created_at\":\"2026-11-09 07:56:07\",\"id\":3}', 'Pencatatan awal', '2026-11-09 00:56:07'),
(4, 4, 3, 'tambah', NULL, '{\"kelas_siswa_id\":25,\"pelanggaran_id\":7,\"guru_id\":2,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:56:40\",\"poin_saat_dicatat\":5,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:56:40\",\"created_at\":\"2026-11-09 07:56:40\",\"id\":4}', 'Pencatatan awal', '2026-11-09 00:56:40'),
(5, 5, 3, 'tambah', NULL, '{\"kelas_siswa_id\":45,\"pelanggaran_id\":18,\"guru_id\":2,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:57:16\",\"poin_saat_dicatat\":10,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:57:16\",\"created_at\":\"2026-11-09 07:57:16\",\"id\":5}', 'Pencatatan awal', '2026-11-09 00:57:16'),
(6, 6, 3, 'tambah', NULL, '{\"kelas_siswa_id\":19,\"pelanggaran_id\":7,\"guru_id\":2,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:57:30\",\"poin_saat_dicatat\":5,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:57:30\",\"created_at\":\"2026-11-09 07:57:30\",\"id\":6}', 'Pencatatan awal', '2026-11-09 00:57:31'),
(7, 7, 30, 'tambah', NULL, '{\"kelas_siswa_id\":19,\"pelanggaran_id\":7,\"guru_id\":29,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:58:37\",\"poin_saat_dicatat\":5,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:58:37\",\"created_at\":\"2026-11-09 07:58:37\",\"id\":7}', 'Pencatatan awal', '2026-11-09 00:58:37'),
(8, 8, 30, 'tambah', NULL, '{\"kelas_siswa_id\":13,\"pelanggaran_id\":1,\"guru_id\":29,\"tanggal_pelanggaran\":\"2026-11-09 00:00:00\",\"tanggal_dicatat\":\"2026-11-09 07:58:55\",\"poin_saat_dicatat\":5,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-09 07:58:55\",\"created_at\":\"2026-11-09 07:58:55\",\"id\":8}', 'Pencatatan awal', '2026-11-09 00:58:55'),
(9, 9, 30, 'tambah', NULL, '{\"kelas_siswa_id\":43,\"pelanggaran_id\":6,\"guru_id\":29,\"tanggal_pelanggaran\":\"2026-11-10 00:00:00\",\"tanggal_dicatat\":\"2026-11-10 12:32:06\",\"poin_saat_dicatat\":5,\"keterangan\":\"cuman test aja\",\"tindakan\":\"cuman test aja\",\"status\":\"aktif\",\"updated_at\":\"2026-11-10 12:32:06\",\"created_at\":\"2026-11-10 12:32:06\",\"id\":9}', 'Pencatatan awal', '2026-11-10 20:32:06'),
(10, 10, 34, 'tambah', NULL, '{\"kelas_siswa_id\":23,\"pelanggaran_id\":13,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11 00:00:00\",\"tanggal_dicatat\":\"2026-11-11 00:39:22\",\"poin_saat_dicatat\":10,\"keterangan\":\"cuman test ajaa\",\"tindakan\":\"cuman test ajaa\",\"status\":\"aktif\",\"updated_at\":\"2026-11-11 00:39:22\",\"created_at\":\"2026-11-11 00:39:22\",\"id\":10}', 'Pencatatan awal', '2026-11-11 08:39:22'),
(11, 11, 34, 'tambah', NULL, '{\"kelas_siswa_id\":50,\"pelanggaran_id\":23,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11 00:00:00\",\"tanggal_dicatat\":\"2026-11-11 01:06:11\",\"poin_saat_dicatat\":10,\"keterangan\":null,\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-11-11 01:06:11\",\"created_at\":\"2026-11-11 01:06:11\",\"id\":11}', 'Pencatatan awal', '2026-11-11 09:06:11'),
(12, 12, 34, 'tambah', NULL, '{\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11 00:00:00\",\"tanggal_dicatat\":\"2026-11-11 04:07:19\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-11-11 04:07:19\",\"created_at\":\"2026-11-11 04:07:19\",\"id\":12}', 'Pencatatan awal', '2026-11-11 12:07:19'),
(13, 13, 34, 'tambah', NULL, '{\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11 00:00:00\",\"tanggal_dicatat\":\"2026-11-11 04:07:21\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-11-11 04:07:21\",\"created_at\":\"2026-11-11 04:07:21\",\"id\":13}', 'Pencatatan awal', '2026-11-11 12:07:21'),
(14, 13, 34, 'edit', '{\"id\":13,\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11\",\"tanggal_dicatat\":\"2026-11-11 04:07:21\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":null,\"status\":\"aktif\",\"created_at\":\"2026-11-11 04:07:21\",\"updated_at\":\"2026-11-11 04:07:21\"}', '{\"id\":13,\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11 00:00:00\",\"tanggal_dicatat\":\"2026-11-11 04:07:21\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":\"tess aja\",\"status\":\"aktif\",\"created_at\":\"2026-11-11 04:07:21\",\"updated_at\":\"2026-11-11 04:08:51\"}', 'tindakan', '2026-11-11 12:08:51'),
(15, 12, 34, 'batal', '{\"id\":12,\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11\",\"tanggal_dicatat\":\"2026-11-11 04:07:19\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":null,\"status\":\"aktif\",\"created_at\":\"2026-11-11 04:07:19\",\"updated_at\":\"2026-11-11 04:07:19\"}', '{\"id\":12,\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11\",\"tanggal_dicatat\":\"2026-11-11 04:07:19\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":null,\"status\":\"dibatalkan\",\"created_at\":\"2026-11-11 04:07:19\",\"updated_at\":\"2026-11-11 04:09:31\"}', 'tes aja', '2026-11-11 12:09:31'),
(16, 13, 34, 'edit', '{\"id\":13,\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11\",\"tanggal_dicatat\":\"2026-11-11 04:07:21\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfgh\",\"tindakan\":\"tess aja\",\"status\":\"aktif\",\"created_at\":\"2026-11-11 04:07:21\",\"updated_at\":\"2026-11-11 04:08:51\"}', '{\"id\":13,\"kelas_siswa_id\":18,\"pelanggaran_id\":24,\"guru_id\":33,\"tanggal_pelanggaran\":\"2026-11-11 00:00:00\",\"tanggal_dicatat\":\"2026-11-11 04:07:21\",\"poin_saat_dicatat\":10,\"keterangan\":\"fgfghwd\",\"tindakan\":\"tess aja\",\"status\":\"aktif\",\"created_at\":\"2026-11-11 04:07:21\",\"updated_at\":\"2026-11-11 14:59:45\"}', 'adasds', '2026-11-11 22:59:45'),
(17, 14, 13, 'tambah', NULL, '{\"idempotency_key\":\"1d9e9aaf-ccf2-4864-b018-8a007ccba8ac\",\"kelas_siswa_id\":188,\"pelanggaran_id\":7,\"guru_id\":12,\"tanggal_pelanggaran\":\"2026-11-12 00:00:00\",\"tanggal_dicatat\":\"2026-11-12 04:05:08\",\"poin_saat_dicatat\":5,\"keterangan\":\"menggunakan seragam batik di hari senin\",\"tindakan\":\"membaca surat\",\"status\":\"aktif\",\"updated_at\":\"2026-11-12 04:05:08\",\"created_at\":\"2026-11-12 04:05:08\",\"id\":14}', 'Pencatatan awal', '2026-11-12 12:05:08'),
(18, 15, 13, 'tambah', NULL, '{\"idempotency_key\":\"89334611-b626-406e-b8fc-f54a572f81e3\",\"kelas_siswa_id\":138,\"pelanggaran_id\":16,\"guru_id\":12,\"tanggal_pelanggaran\":\"2026-11-12 00:00:00\",\"tanggal_dicatat\":\"2026-11-12 04:06:36\",\"poin_saat_dicatat\":5,\"keterangan\":null,\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-11-12 04:06:36\",\"created_at\":\"2026-11-12 04:06:36\",\"id\":15}', 'Pencatatan awal', '2026-11-12 12:06:36'),
(19, 19, 13, 'tambah', NULL, '{\"idempotency_key\":\"87ea4414-21e6-48ec-a24f-78f45b276b82\",\"kelas_siswa_id\":195,\"pelanggaran_id\":1,\"guru_id\":12,\"tanggal_pelanggaran\":\"2026-11-12 00:00:00\",\"tanggal_dicatat\":\"2026-11-12 04:18:50\",\"poin_saat_dicatat\":5,\"keterangan\":\"dddd\",\"tindakan\":\"dddd\",\"status\":\"aktif\",\"updated_at\":\"2026-11-12 04:18:50\",\"created_at\":\"2026-11-12 04:18:50\",\"id\":19}', 'Pencatatan awal', '2026-11-12 12:18:50'),
(20, 20, 13, 'tambah', NULL, '{\"idempotency_key\":\"423d6c07-4c7e-4cc5-88c3-4c7b34d5fa30\",\"kelas_siswa_id\":42,\"pelanggaran_id\":16,\"guru_id\":12,\"tanggal_pelanggaran\":\"2026-11-12 00:00:00\",\"tanggal_dicatat\":\"2026-11-12 04:22:17\",\"poin_saat_dicatat\":5,\"keterangan\":\"test aja\",\"tindakan\":\"test aja\",\"status\":\"aktif\",\"updated_at\":\"2026-11-12 04:22:17\",\"created_at\":\"2026-11-12 04:22:17\",\"id\":20}', 'Pencatatan awal', '2026-11-12 12:22:17'),
(21, 20, 13, 'edit', '{\"id\":20,\"kelas_siswa_id\":42,\"pelanggaran_id\":16,\"guru_id\":12,\"tanggal_pelanggaran\":\"2026-11-12\",\"tanggal_dicatat\":\"2026-11-12 04:22:17\",\"poin_saat_dicatat\":5,\"keterangan\":\"test aja\",\"tindakan\":\"test aja\",\"status\":\"aktif\",\"created_at\":\"2026-11-12 04:22:17\",\"updated_at\":\"2026-11-12 04:22:17\",\"idempotency_key\":\"423d6c07-4c7e-4cc5-88c3-4c7b34d5fa30\"}', '{\"id\":20,\"kelas_siswa_id\":42,\"pelanggaran_id\":16,\"guru_id\":12,\"tanggal_pelanggaran\":\"2026-11-12 00:00:00\",\"tanggal_dicatat\":\"2026-11-12 04:22:17\",\"poin_saat_dicatat\":5,\"keterangan\":\"test aja\",\"tindakan\":\"test ajaaaa\",\"status\":\"aktif\",\"created_at\":\"2026-11-12 04:22:17\",\"updated_at\":\"2026-11-12 04:22:36\",\"idempotency_key\":\"423d6c07-4c7e-4cc5-88c3-4c7b34d5fa30\"}', 'test aja', '2026-11-12 12:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama_pelanggaran` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `poin` smallint(5) UNSIGNED NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_siswa_id` bigint(20) UNSIGNED NOT NULL,
  `pelanggaran_id` bigint(20) UNSIGNED NOT NULL,
  `guru_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pelanggaran` date NOT NULL,
  `tanggal_dicatat` datetime NOT NULL,
  `poin_saat_dicatat` smallint(5) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idempotency_key` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `kelas_siswa_id`, `pelanggaran_id`, `guru_id`, `tanggal_pelanggaran`, `tanggal_dicatat`, `poin_saat_dicatat`, `keterangan`, `tindakan`, `status`, `created_at`, `updated_at`, `idempotency_key`) VALUES
(1, 19, 4, 33, '2026-11-09', '2026-11-09 07:54:42', 10, 'Bolos pelajaran matematika', 'Cuman test ajaa', 'aktif', '2026-11-09 00:54:42', '2026-11-09 00:54:42', NULL),
(2, 34, 19, 33, '2026-11-09', '2026-11-09 07:55:22', 15, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:55:22', '2026-11-09 00:55:22', NULL),
(3, 125, 7, 33, '2026-11-09', '2026-11-09 07:56:07', 5, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:56:07', '2026-11-09 00:56:07', NULL),
(4, 25, 7, 2, '2026-11-09', '2026-11-09 07:56:40', 5, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:56:40', '2026-11-09 00:56:40', NULL),
(5, 45, 18, 2, '2026-11-09', '2026-11-09 07:57:16', 10, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:57:16', '2026-11-09 00:57:16', NULL),
(6, 19, 7, 2, '2026-11-09', '2026-11-09 07:57:30', 5, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:57:30', '2026-11-09 00:57:30', NULL),
(7, 19, 7, 29, '2026-11-09', '2026-11-09 07:58:37', 5, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:58:37', '2026-11-09 00:58:37', NULL),
(8, 13, 1, 29, '2026-11-09', '2026-11-09 07:58:55', 5, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-09 00:58:55', '2026-11-09 00:58:55', NULL),
(9, 43, 6, 29, '2026-11-10', '2026-11-10 12:32:06', 5, 'cuman test aja', 'cuman test aja', 'aktif', '2026-11-10 20:32:06', '2026-11-10 20:32:06', NULL),
(10, 23, 13, 33, '2026-11-11', '2026-11-11 00:39:22', 10, 'cuman test ajaa', 'cuman test ajaa', 'aktif', '2026-11-11 08:39:22', '2026-11-11 08:39:22', NULL),
(11, 50, 23, 33, '2026-11-11', '2026-11-11 01:06:11', 10, NULL, NULL, 'aktif', '2026-11-11 09:06:11', '2026-11-11 09:06:11', NULL),
(12, 18, 24, 33, '2026-11-11', '2026-11-11 04:07:19', 10, 'fgfgh', NULL, 'dibatalkan', '2026-11-11 12:07:19', '2026-11-11 12:09:31', NULL),
(13, 18, 24, 33, '2026-11-11', '2026-11-11 04:07:21', 10, 'fgfghwd', 'tess aja', 'aktif', '2026-11-11 12:07:21', '2026-11-11 22:59:45', NULL),
(14, 188, 7, 12, '2026-11-12', '2026-11-12 04:05:08', 5, 'menggunakan seragam batik di hari senin', 'membaca surat', 'aktif', '2026-11-12 12:05:08', '2026-11-12 12:05:08', '1d9e9aaf-ccf2-4864-b018-8a007ccba8ac'),
(15, 138, 16, 12, '2026-11-12', '2026-11-12 04:06:36', 5, NULL, NULL, 'aktif', '2026-11-12 12:06:36', '2026-11-12 12:06:36', '89334611-b626-406e-b8fc-f54a572f81e3'),
(19, 195, 1, 12, '2026-11-12', '2026-11-12 04:18:50', 5, 'dddd', 'dddd', 'aktif', '2026-11-12 12:18:50', '2026-11-12 12:18:50', '87ea4414-21e6-48ec-a24f-78f45b276b82'),
(20, 42, 16, 12, '2026-11-12', '2026-11-12 04:22:17', 5, 'test aja', 'test ajaaaa', 'aktif', '2026-11-12 12:22:17', '2026-11-12 12:22:36', '423d6c07-4c7e-4cc5-88c3-4c7b34d5fa30');

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` char(1) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tahun_masuk` smallint(5) UNSIGNED NOT NULL,
  `status_siswa` varchar(20) NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nis` varchar(10) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nisn`, `nama`, `jenis_kelamin`, `alamat`, `tahun_masuk`, `status_siswa`, `created_at`, `updated_at`, `nis`, `tanggal_lahir`) VALUES
(1, '0000000001', 'ADITTIA NUR LANTIP', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-04 15:10:12', '2026-11-04 08:21:17', '2024000001', '2008-01-15'),
(2, '0000000002', 'AGNIA ROSYA NOERILLAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-04 15:10:12', '2026-11-04 08:21:33', '2024000002', '2008-03-22'),
(3, '0000000027', 'ALFIN RAHADIAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-04 15:10:12', '2026-11-04 08:21:49', '2024000027', '2009-01-12'),
(4, '0000000003', 'ANISA AMIRULLA', 'P', 'Jl. Tentara Pelajar, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000003', '2008-05-10'),
(5, '0000000004', 'ARADEA KANDI', 'L', 'Jl. RE Martadinata, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:45:25', '2024000004', '2008-07-19'),
(6, '0000000005', 'ARFA DIESA OKMALELIZA', 'P', 'Jl. Sutisna Senjaya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000005', '2008-09-12'),
(7, '0000000006', 'FITRI NUR SYAMI', 'P', 'Jl. Paseh, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000006', '2008-11-25'),
(8, '0000000007', 'JUNITA PUTRI NUR RAHMAN', 'P', 'Jl. Mitra Batik, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000007', '2008-02-08'),
(9, '0000000008', 'MOHAMMAD ATALARIK ALI SYAHBANA', 'L', 'Jl. Perintis Kemerdekaan, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000008', '2008-04-17'),
(10, '0000000009', 'MUHAMAD FAHRIL FAHRUROZI', 'L', 'Jl. Gunung Sabeulah, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000009', '2008-06-29'),
(11, '0000000010', 'MUHAMMAD ADNAN SYAEFUL ISLAMI', 'L', 'Jl. Letnan Harun, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000010', '2008-08-13'),
(12, '0000000011', 'MUHAMMAD ZAHRU AQILA FIKRI', 'L', 'Jl. BKR, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000011', '2008-10-21'),
(13, '0000000012', 'NADIA ZULFA SORAYA', 'P', 'Jl. Cieunteung, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000012', '2008-12-05'),
(14, '0000000013', 'NAZRIL ILHAM', 'L', 'Jl. Siliwangi, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000013', '2008-01-28'),
(15, '0000000014', 'PUSPITA DEWI', 'P', 'Jl. Gobras, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000014', '2008-03-14'),
(16, '0000000015', 'RAKHA MUHAMMAD HAYKAL', 'L', 'Jl. Indihiang, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000015', '2008-05-26'),
(17, '0000000016', 'RANI MEILANI', 'P', 'Jl. Mangkubumi, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000016', '2008-07-07'),
(18, '0000000017', 'REIHAN MUZAKI', 'L', 'Jl. Tamansari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000017', '2008-09-30'),
(19, '0000000018', 'RESA JULIANSAH', 'L', 'Jl. Kawalu, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000018', '2008-11-11'),
(20, '0000000019', 'RIZKI PERMATA SUGIANTO', 'L', 'Jl. Cibeureum, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000019', '2008-02-19'),
(21, '0000000020', 'SAHIRA FIRAJ', 'P', 'Jl. Cipedes, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000020', '2008-04-03'),
(22, '0000000021', 'SENDI MARDIAN', 'L', 'Jl. Awipari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000021', '2008-06-16'),
(23, '0000000022', 'SHAHAIDAN NIZZAR', 'L', 'Jl. Sukarindik, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000022', '2008-08-27'),
(24, '0000000023', 'SHERLY SATRIANI', 'P', 'Jl. Sukamulya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000023', '2008-10-09'),
(25, '0000000024', 'SOFI NURJANAH', 'P', 'Jl. Mulyasari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000024', '2008-12-18'),
(26, '0000000025', 'SURAIYA SALSABILA AZZAHRA', 'P', 'Jl. Cikalang, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000025', '2009-02-12'),
(27, '0000000026', 'TONI ANTONIO', 'L', 'Jl. Sukaraja, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000026', '2009-04-24'),
(28, '0000000028', 'ALISA RAMADHANI', 'P', 'Jl. Sambongpari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000028', '2009-02-18'),
(29, '0000000029', 'AMA AURA LIVIA', 'P', 'Jl. Sukamaju, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000029', '2009-03-21'),
(30, '0000000030', 'ANIS KHOERUNNISA NUR RAMADANI', 'P', 'Jl. Cibunigeulis, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000030', '2009-04-15'),
(31, '0000000031', 'AZMIL AMIRA', 'P', 'Jl. Parakannyasag, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000031', '2009-05-09'),
(32, '0000000032', 'AZRIL AKBAR FAWJI ABDILLAH', 'L', 'Jl. Cibeureum, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000032', '2009-06-24'),
(33, '0000000033', 'ERLANGGA SAPUTRA HIDAYAT', 'L', 'Jl. Bantarsari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000033', '2009-07-11'),
(34, '0000000034', 'HILMA HUMMA TUNNAJAH', 'P', 'Jl. Sukaraja, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000034', '2009-08-19'),
(35, '0000000035', 'ILMA NAJYA', 'P', 'Jl. Setiajaya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000035', '2009-09-13'),
(36, '0000000036', 'JULIANA DHARMA PANGESTU', 'P', 'Jl. Sukahurip, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000036', '2009-10-22'),
(37, '0000000037', 'KAYLA NUR FADILAH', 'P', 'Jl. Cikadongdong, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000037', '2009-11-16'),
(38, '0000000038', 'MOZA DINA NABILLAH', 'P', 'Jl. Cintaraja, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000038', '2009-12-05'),
(39, '0000000039', 'MUHAMAD ALFARIZI', 'L', 'Jl. Mangkubumi, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000039', '2009-01-27'),
(40, '0000000040', 'MUHAMAD FAHRIN ADAM', 'L', 'Jl. Urug, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000040', '2009-02-14'),
(41, '0000000041', 'MUHAMMAD ZIDANE MAULANA', 'L', 'Jl. Kawalu, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000041', '2009-03-08'),
(42, '0000000042', 'MUHAMMAD ZULFAN NURALFANSYAH', 'L', 'Jl. Cibeuti, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000042', '2009-04-26'),
(43, '0000000043', 'NATIYA NUR AZKIYA', 'P', 'Jl. Sukagalih, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000043', '2009-05-17'),
(44, '0000000044', 'NOVITA PUSPITASARI', 'P', 'Jl. Leuwidahu, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000044', '2009-06-12'),
(45, '0000000045', 'NUCKY ANDREAS FEBRIAN', 'L', 'Jl. Sukamaju, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000045', '2009-07-23'),
(46, '0000000046', 'RADITYA JETI PRASETYA', 'L', 'Jl. Awipari, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000046', '2009-08-07'),
(47, '0000000047', 'RANGGA RAMADIYANTO NURHAKIM', 'L', 'Jl. Cibunigeulis, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000047', '2009-09-25'),
(48, '0000000048', 'RISQI MUHAMAD IHWANI', 'L', 'Jl. Setiamulya, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000048', '2009-10-14'),
(49, '0000000049', 'SALWA SABILATUL ZARKAH', 'P', 'Jl. Sukarindik, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000049', '2009-11-29'),
(50, '0000000050', 'TAZKIA ZAHRA', 'P', 'Jl. Indihiang, Tasikmalaya', 2026, 'aktif', '2026-11-04 15:20:46', '2026-11-04 15:20:46', '2024000050', '2009-12-19'),
(51, '0000000051', 'ADAM LASMANA NUGRAHA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000051', '2008-01-12'),
(52, '0000000052', 'AFEP SAEPUL HIDAYAH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000052', '2008-02-18'),
(53, '0000000053', 'AGIS PRATAMA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000053', '2008-03-21'),
(54, '0000000054', 'DAWAM ARIASA PUTRA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000054', '2008-04-15'),
(55, '0000000055', 'DIKA ADITYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000055', '2008-05-09'),
(56, '0000000056', 'DIKI SAPUTRA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000056', '2008-06-24'),
(57, '0000000057', 'DIMAS ANUGRAH PRANATAMA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000057', '2008-07-11'),
(58, '0000000058', 'ERGI ZULVIAN RAZVI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000058', '2008-08-19'),
(59, '0000000059', 'EVAN RASENDRIYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000059', '2008-09-13'),
(60, '0000000060', 'FAIZ FAUZAN ABDURAHMAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000060', '2008-10-22'),
(61, '0000000061', 'FATIR RIZIKY', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000061', '2008-11-16'),
(62, '0000000062', 'GUNTUR MUHAMAD AZRIL', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000062', '2008-12-05'),
(63, '0000000063', 'HIBAN SA\'BAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000063', '2008-01-27'),
(64, '0000000064', 'HILMAN SOHIBUL KIYAM', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000064', '2008-02-14'),
(65, '0000000065', 'IJAD RAYI NURFAIS ANSORI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000065', '2008-03-08'),
(66, '0000000066', 'IQBAL MUHARROM', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000066', '2008-04-26'),
(67, '0000000067', 'IZAZ SYAKIR', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000067', '2008-05-17'),
(68, '0000000068', 'MOCH FAIZAL GILANG PERMANA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000068', '2008-06-12'),
(69, '0000000069', 'MUHAMAD RIZKY GOZALI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000069', '2008-07-23'),
(70, '0000000070', 'MUHAMMAD RIKMAN NULHAKIM', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000070', '2008-08-07'),
(71, '0000000071', 'MUHAMMAD SIDDIQ IBRAHIM SOBARI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000071', '2008-09-25'),
(72, '0000000072', 'NIZAR SURYANA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000072', '2008-10-14'),
(73, '0000000073', 'RASYA ABDUL BASITH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000073', '2008-11-29'),
(74, '0000000074', 'RAUSAN VIKRI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000074', '2008-12-19'),
(75, '0000000075', 'REVANDI NORWANSHAH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000075', '2008-01-16'),
(76, '0000000076', 'REVAN RUDIANSANH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000076', '2008-02-23'),
(77, '0000000077', 'RIZKI RADITYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000077', '2008-03-17'),
(78, '0000000078', 'ROBY ORIE AL RASYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000078', '2008-04-11'),
(79, '0000000079', 'RULY RUDIANSANH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000079', '2008-05-28'),
(80, '0000000080', 'SYAHDAN RASDANI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000080', '2008-06-19'),
(81, '0000000081', 'TABA HAERUL SALIM', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000081', '2008-07-14'),
(82, '0000000082', 'TIRTA NUR ZAMZAM', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000082', '2008-08-26'),
(83, '0000000083', 'ZILHAD AYALTHUHAQ', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:22:48', '2026-11-09 07:22:48', '2024000083', '2008-09-18'),
(84, '0000000101', 'ADITYA REFAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010101', '2008-01-12'),
(85, '0000000102', 'ADRI FAISAL MUHAMMAD', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010102', '2008-02-18'),
(86, '0000000103', 'AFRIZA ABDUL MU\'IZ RIF\'AT', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010103', '2008-03-21'),
(87, '0000000104', 'AGNI AFIANSAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010104', '2008-04-15'),
(88, '0000000105', 'ALIA RAHMAWATI', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010105', '2008-05-09'),
(89, '0000000106', 'ALVIN ALVIANSYAH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010106', '2008-06-24'),
(90, '0000000107', 'ANDHIKA DISTA FIRDAUS', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010107', '2008-07-11'),
(91, '0000000108', 'ARYA DIAS PRATAMA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010108', '2008-08-19'),
(92, '0000000109', 'BRIAN SUNJAYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010109', '2008-09-13'),
(93, '0000000110', 'BUNGA NURHALIZA', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010110', '2008-10-22'),
(94, '0000000111', 'DAFA FADHLURRAHMAN CHANDRA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010111', '2008-11-16'),
(95, '0000000112', 'DESYANNA FITRIA RAMADANI', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010112', '2008-12-05'),
(96, '0000000113', 'FAHRI FIRMANSYAH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010113', '2008-01-27'),
(97, '0000000114', 'ILHAM TASANII RAMDANI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010114', '2008-02-14'),
(98, '0000000115', 'IP SYARIF PAHRUROJI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010115', '2008-03-08'),
(99, '0000000116', 'KASNAYA NUREZKA', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010116', '2008-04-26'),
(100, '0000000117', 'KEVIN NUGRAHA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010117', '2008-05-17'),
(101, '0000000118', 'KIRANI ALYA NUR AFIFAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010118', '2008-06-12'),
(102, '0000000119', 'LILA RAMADANI', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010119', '2008-07-23'),
(103, '0000000120', 'LUTFIYAN MUHAMMAD SOHIBIL', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010120', '2008-08-07'),
(104, '0000000121', 'MARISA PUTRI OKTAVIANI', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010121', '2008-09-25'),
(105, '0000000122', 'MIFTAHUL JANNAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010122', '2008-10-14'),
(106, '0000000123', 'MOCHAMAD MIKO ADITYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010123', '2008-11-29'),
(107, '0000000124', 'MUHAMMAD ISA ANDARISTA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010124', '2008-12-19'),
(108, '0000000125', 'MUHAMMAD RIZKI SYABANI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010125', '2008-01-16'),
(109, '0000000126', 'RAVELA SAGI', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010126', '2008-02-23'),
(110, '0000000127', 'RAZIFA LUTFIA MALIK', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010127', '2008-03-17'),
(111, '0000000128', 'RAYHAN REZKY NOORMAHRAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010128', '2008-04-11'),
(112, '0000000129', 'REHAN NURUL AMALIN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010129', '2008-05-28'),
(113, '0000000130', 'RIZAH BILQIS KHOERIYAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010130', '2008-06-19'),
(114, '0000000131', 'SILEN NUR ROFIAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010131', '2008-07-14'),
(115, '0000000132', 'VINA REZKY NOVITASARI', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010132', '2008-08-26'),
(116, '0000000133', 'WAWAN SUMEBAT', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:42:36', '2026-11-09 07:42:36', '2024010133', '2008-09-18'),
(117, '0000000134', 'AKBAR MAULANA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010134', '2008-01-12'),
(118, '0000000135', 'DAVIN REYSYA PUTRA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010135', '2008-02-18'),
(119, '0000000136', 'DIKRI ERIKA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010136', '2008-03-21'),
(120, '0000000137', 'DIPTA SEPTIANA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010137', '2008-04-15'),
(121, '0000000138', 'DZIKRI WIGUNA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010138', '2008-05-09'),
(122, '0000000139', 'FABIAN MAULIDAN SUSANTO', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010139', '2008-06-24'),
(123, '0000000140', 'FARID NURUL HUDA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010140', '2008-07-11'),
(124, '0000000141', 'FIRMAN NUR ALAMSYAH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010141', '2008-08-19'),
(125, '0000000142', 'INDRA IRANA PUTRA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010142', '2008-09-13'),
(126, '0000000143', 'KINKIN NURUL YAKIN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010143', '2008-10-22'),
(127, '0000000144', 'M IRSYAD NURFALLAH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010144', '2008-11-16'),
(128, '0000000145', 'MOCHAMAD FATHAN MUYASAR', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010145', '2008-12-05');
INSERT INTO `t_siswa` (`id`, `nisn`, `nama`, `jenis_kelamin`, `alamat`, `tahun_masuk`, `status_siswa`, `created_at`, `updated_at`, `nis`, `tanggal_lahir`) VALUES
(129, '0000000146', 'MOCHAMAD RIZQI ADITYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010146', '2008-01-27'),
(130, '0000000147', 'MUHAMAD FAHRUL HENDRAWAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010147', '2008-02-14'),
(131, '0000000148', 'MUHAMAD RIZKI SYAPUTRA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010148', '2008-03-08'),
(132, '0000000149', 'MUHAMMAD DIVRIAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010149', '2008-04-26'),
(133, '0000000150', 'MUHAMMAD RIDHO AL FATHI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010150', '2008-05-17'),
(134, '0000000151', 'MUHAMMAD ZAMZAM AL FARIZHI', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010151', '2008-06-12'),
(135, '0000000152', 'RAFFA RADITYA RASINO', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010152', '2008-07-23'),
(136, '0000000153', 'RAHAN RIZKULLOH', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010153', '2008-08-07'),
(137, '0000000154', 'RANGGA ADITYA', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010154', '2008-09-25'),
(138, '0000000155', 'RAYHAN FIRDAUS RACHMAT', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010155', '2008-10-14'),
(139, '0000000156', 'REHAN SYAHRUDDIN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010156', '2008-11-29'),
(140, '0000000157', 'RIDWANULLOH SETIAWAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010157', '2008-12-19'),
(141, '0000000158', 'RIZKY NUR AKBAR', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010158', '2008-01-16'),
(142, '0000000159', 'SALMAN ABDULLAH MAULAY', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010159', '2008-02-23'),
(143, '0000000160', 'SENDY DIMASTIAN', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010160', '2008-03-17'),
(144, '0000000161', 'SYAHAL SAID AL FAJR', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010161', '2008-04-11'),
(145, '0000000162', 'TACHA NURFADILAH', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010162', '2008-05-28'),
(146, '0000000163', 'WAWAN NOPIANA HIDAYAT', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010163', '2008-06-19'),
(147, '0000000164', 'YUDHI SYAEFUL ANWAR', 'L', 'Tasikmalaya', 2026, 'aktif', '2026-11-09 07:49:39', '2026-11-09 07:49:39', '2024010164', '2008-07-14');

-- --------------------------------------------------------

--
-- Table structure for table `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_tahun_ajaran` varchar(20) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama_tahun_ajaran`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '2026/2027', '2026-07-13', '2027-06-18', 1, '2026-11-04 08:04:43', '2026-11-04 08:04:43'),
(2, '2027/2028', '2027-07-13', '2028-06-18', 0, '2026-11-10 19:52:05', '2026-11-10 19:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`id`, `nama`, `email`, `password`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Administrator SekolahKu', 'admin@sekolahku.com', '$2y$12$6XT1uPdDIoaORCDVwJBqoeQmihFbS9PmvQk/cL/gTCYYfp7WqqaxS', '2026-11-04 07:59:23', '2026-11-04 07:59:23', 'admin'),
(3, 'Wawan Kurniawan, S.Pd.I', 'wawankurniawan@sekolahku.com', '$2y$12$/0Y2wi1xsMyTV2sb.Bg9xORBAK2HPwu7sCvznC1SodZwVHEa/PNiS', '2026-11-04 15:01:22', '2026-11-09 00:01:33', 'guru'),
(4, 'Ai Nuryaidah, S.Pd', 'ainuryaidah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(5, 'Maya Permata Endah, S. Pd', 'mayapermataendah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(6, 'Dody Jaenudin, S.Pd.', 'dodyjaenudin@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(7, 'Dedi Kardiman, S. Pd', 'dedikardiman@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(8, 'Uca Musa Yusenda, S.Pd', 'ucamusayusenda@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(9, 'Drs. Ahmad Nur Mukhlis', 'ahmadnurmukhlis@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(10, 'Drs. Heri', 'heri@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(11, 'Asep Bastaman', 'asepbastaman@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(12, 'H. Encep Maemun, S.T., M.Pd', 'encepmaemun@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(13, 'Opik', 'opik@sekolahku.com', '$2y$12$U5lWD5r3xeRs8bSjOOMn9eVciHod.hK/taqXEw/ncxk/K/uJWkELC', '2026-11-04 15:01:22', '2026-11-12 12:04:05', 'guru'),
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
(34, 'Aldi Maulana, S.Pd', 'aldimaulana@sekolahku.com', '$2y$12$/B.jtFuM.FAeQKAuHjm3.en14GVRlHdRpT8YvJrjzDA5SRSeQFco.', '2026-11-04 15:01:22', '2026-11-08 23:32:26', 'guru'),
(35, 'Eli Rosliah, S.Pd', 'elirosliah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(36, 'Isni Nur Fitriani, S.Pd', 'isninurfitriani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(37, 'Aanisah Putri Nur Aini, S.Pd', 'aanisahputrinuraini@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(38, 'Yusi Luthfiani, S.Sos.I', 'yusiluthfiani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(39, 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'lilisrustandymawardi@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(40, 'Yusrin Noer Sabrina, S.Pd', 'yusrinnoersabrina@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(41, 'Senny Indayani, S.Pd', 'sennyindayani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(42, 'Ilma Amalia, S.Pd', 'ilmaamalia@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(43, 'Alvira Sofwatunnisa, S.Pd', 'alvirasofwatunnisa@sekolahku.com', '$2y$12$1OPhE3Yr/z.aCUsYilJK/.YWXbLU3xtaQCKEe5KxmdYsSvKXCrZ7i', '2026-11-04 15:01:22', '2026-11-10 20:12:46', 'guru'),
(44, 'Risna Andriyani, S.Pd', 'risnaandriyani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(45, 'Dra. Tintin Noerhayati', 'tintinnoerhayati@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(46, 'Rizkia Fahira, S.Pd', 'rizkiafahira@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(47, 'Frasetio', 'frasetio@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(48, 'Ratnaningsih Hatil Aeni, S.Pd.', 'ratnaningshhatilaeni@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(49, 'Dheny Darajatul Hasani, S.Pd.', 'dhenydarajatulhasani@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(50, 'Fahrullah, S.Pd', 'fahrullah@sekolahku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-11-04 15:01:22', '2026-11-04 15:01:22', 'guru'),
(51, 'Aan Andriana, S.T', 'aanandriana@sekolahku.com', '$2y$12$x97sbJTBhUHH9SvjzwEsNeyOEmeMIIFfUdMTzrnPB36AJkowqX.jC', '2026-11-04 15:01:22', '2026-11-10 19:56:14', 'guru'),
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `guru_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `guru_id`, `kelas_id`, `tahun_ajaran_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 29, 1, 1, '2026-07-13', NULL, 1, '2026-11-04 08:05:11', '2026-11-04 08:05:11'),
(2, 33, 2, 1, '2026-07-13', NULL, 1, '2026-11-04 08:05:38', '2026-11-08 23:19:56'),
(3, 43, 3, 1, '2026-07-13', NULL, 1, '2026-11-09 07:22:48', '2026-11-09 07:22:48'),
(4, 42, 4, 1, '2026-07-13', NULL, 1, '2026-11-09 07:42:36', '2026-11-09 07:42:36'),
(5, 53, 5, 1, '2026-07-13', NULL, 1, '2026-11-09 07:49:39', '2026-11-09 07:49:39');

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
  ADD UNIQUE KEY `t_guru_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `t_guru_nip_unique` (`nip`);

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
  ADD UNIQUE KEY `t_ps_guru_idempotency_unique` (`guru_id`,`idempotency_key`),
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `t_log_aktivitas`
--
ALTER TABLE `t_log_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `t_guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`);

--
-- Constraints for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `t_kelas_siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`),
  ADD CONSTRAINT `t_kelas_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`);

--
-- Constraints for table `t_log_aktivitas`
--
ALTER TABLE `t_log_aktivitas`
  ADD CONSTRAINT `t_log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`);

--
-- Constraints for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  ADD CONSTRAINT `t_log_pelanggaran_pelanggaran_siswa_id_foreign` FOREIGN KEY (`pelanggaran_siswa_id`) REFERENCES `t_pelanggaran_siswa` (`id`),
  ADD CONSTRAINT `t_log_pelanggaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`);

--
-- Constraints for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `t_pelanggaran_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`);

--
-- Constraints for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`),
  ADD CONSTRAINT `t_pelanggaran_siswa_kelas_siswa_id_foreign` FOREIGN KEY (`kelas_siswa_id`) REFERENCES `t_kelas_siswa` (`id`),
  ADD CONSTRAINT `t_pelanggaran_siswa_pelanggaran_id_foreign` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`);

--
-- Constraints for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `t_wali_kelas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`),
  ADD CONSTRAINT `t_wali_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`),
  ADD CONSTRAINT `t_wali_kelas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
