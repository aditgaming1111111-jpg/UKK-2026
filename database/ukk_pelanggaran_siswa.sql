-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 12, 2026 at 11:02 PM
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
(5, '2026_10_01_132222_create_sekolahku_core_tables', 2),
(6, '2026_10_01_155432_add_unique_index_to_t_pelanggaran_kode', 3);

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
('PZc2vrgfLtwg5TYXc6wWdXrJ54qp9n93x3h1tig4', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJiVFR1djlib0psTld0WXducElJZDVzTXZ0TkUzMzhMTjJBTHc4RjFDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiM2VjNWM1NGYxNGM1ZDVmZGM5OGFmOGE4ODI4ZDE3YmYzMTQyZTUzYWMyNDllZDdmMDZkZWIyNDdjM2FiMDYzNyJ9', 1791815585),
('Weaq8sYNvMu8kEjPg8OCXKfnLzIzvtINhWyFPYCL', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.140.0 Chrome/150.0.7871.250 Electron/43.7.3 Safari/537.36', 'eyJfdG9rZW4iOiJGUWRVYmVjekNOdUlpQU5hanplWGNRdWRrN2JZcDZ5RHN5QnVOQmQzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvc2lzd2FcLzEiLCJyb3V0ZSI6ImFkbWluLnN0dWRlbnRzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIzZWM1YzU0ZjE0YzVkNWZkYzk4YWY4YTg4MjhkMTdiZjMxNDJlNTNhYzI0OWVkN2YwNmRlYjI0N2MzYWIwNjM3In0=', 1791815982);

-- --------------------------------------------------------

--
-- Table structure for table `t_guru`
--

CREATE TABLE `t_guru` (
  `id` bigint UNSIGNED NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '1980010120260001', 'Guru SekolahKu', 'guru@sekolahku.test', 1, 2, '2026-10-01 05:55:03', '2026-10-01 05:55:03'),
(2, '12345678', 'Budi Santoso', 'budi@sekolahku.com', 0, 3, '2026-10-02 04:19:03', '2026-10-11 03:36:12'),
(3, '123444444', 'Anton Sanjaya', 'anton@sekolahku.com', 1, 4, '2026-10-02 04:28:32', '2026-10-02 04:28:32'),
(4, '1998051520260002', 'Dedi Kurniawan', 'dedi.kurniawan@sekolahku.com', 1, 5, '2026-10-04 01:13:41', '2026-10-04 01:18:29');

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
(2, 'X RPL 1', '10', 'RPL', '2026-10-03 00:49:03', '2026-10-03 00:49:03'),
(3, 'XI RPL 2', '11', 'RPL', '2026-10-05 03:23:04', '2026-10-05 03:23:04');

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
(1, 2, 2, 1, '2026-07-01', '2026-12-31', 0, '2026-10-03 00:57:32', '2026-10-05 03:24:25'),
(2, 3, 2, 1, '2026-07-01', '2026-12-31', 0, '2026-10-04 01:17:59', '2026-10-05 03:25:41'),
(3, 2, 3, 1, '2027-01-01', '2027-06-30', 0, '2026-10-05 03:24:25', '2026-10-05 03:36:10'),
(4, 3, 3, 1, '2027-01-01', NULL, 1, '2026-10-05 03:25:41', '2026-10-05 03:25:41'),
(5, 2, 3, 2, '2027-07-01', NULL, 1, '2026-10-05 03:36:10', '2026-10-05 03:36:10'),
(6, 1, 3, 2, '2027-07-01', NULL, 1, '2026-10-05 03:52:53', '2026-10-05 03:52:53');

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

--
-- Dumping data for table `t_log_pelanggaran`
--

INSERT INTO `t_log_pelanggaran` (`id`, `pelanggaran_siswa_id`, `user_id`, `aksi`, `data_sebelum`, `data_sesudah`, `alasan`, `created_at`) VALUES
(1, 1, 4, 'tambah', NULL, '{\"kelas_siswa_id\":2,\"pelanggaran_id\":1,\"guru_id\":3,\"tanggal_pelanggaran\":\"2026-10-04 00:00:00\",\"tanggal_dicatat\":\"2026-10-04 08:33:40\",\"poin_saat_dicatat\":5,\"keterangan\":\"Siswa terlambat masuk sekolah.\",\"tindakan\":\"Teguran dan pembinaan oleh guru.\",\"status\":\"aktif\",\"updated_at\":\"2026-10-04 08:33:40\",\"created_at\":\"2026-10-04 08:33:40\",\"id\":1}', 'Pencatatan awal', '2026-10-04 01:33:40'),
(2, 1, 4, 'batal', '{\"id\":1,\"kelas_siswa_id\":2,\"pelanggaran_id\":1,\"guru_id\":3,\"tanggal_pelanggaran\":\"2026-10-04\",\"tanggal_dicatat\":\"2026-10-04 08:33:40\",\"poin_saat_dicatat\":5,\"keterangan\":\"Siswa terlambat masuk sekolah.\",\"tindakan\":\"Teguran dan pembinaan oleh guru.\",\"status\":\"aktif\",\"created_at\":\"2026-10-04 08:33:40\",\"updated_at\":\"2026-10-04 08:33:40\"}', '{\"id\":1,\"kelas_siswa_id\":2,\"pelanggaran_id\":1,\"guru_id\":3,\"tanggal_pelanggaran\":\"2026-10-04\",\"tanggal_dicatat\":\"2026-10-04 08:33:40\",\"poin_saat_dicatat\":5,\"keterangan\":\"Siswa terlambat masuk sekolah.\",\"tindakan\":\"Teguran dan pembinaan oleh guru.\",\"status\":\"dibatalkan\",\"created_at\":\"2026-10-04 08:33:40\",\"updated_at\":\"2026-10-04 08:38:12\"}', 'Pembatalan catatan percobaan untuk pengujian sistem. Catatan dibuat sebagai data uji, bukan kejadian pelanggaran sebenarnya.', '2026-10-04 01:38:12'),
(3, 2, 4, 'tambah', NULL, '{\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":3,\"tanggal_pelanggaran\":\"2026-10-04 00:00:00\",\"tanggal_dicatat\":\"2026-10-04 09:01:51\",\"poin_saat_dicatat\":5,\"keterangan\":\"Test\",\"tindakan\":\"Test\",\"status\":\"aktif\",\"updated_at\":\"2026-10-04 09:01:51\",\"created_at\":\"2026-10-04 09:01:51\",\"id\":2}', 'Pencatatan awal', '2026-10-04 02:01:51'),
(4, 3, 5, 'tambah', NULL, '{\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":4,\"tanggal_pelanggaran\":\"2026-10-05 00:00:00\",\"tanggal_dicatat\":\"2026-10-05 09:28:33\",\"poin_saat_dicatat\":5,\"keterangan\":null,\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-10-05 09:28:33\",\"created_at\":\"2026-10-05 09:28:33\",\"id\":3}', 'Pencatatan awal', '2026-10-05 02:28:33'),
(5, 4, 4, 'tambah', NULL, '{\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":3,\"tanggal_pelanggaran\":\"2026-10-05 00:00:00\",\"tanggal_dicatat\":\"2026-10-05 10:09:10\",\"poin_saat_dicatat\":5,\"keterangan\":null,\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-10-05 10:09:10\",\"created_at\":\"2026-10-05 10:09:10\",\"id\":4}', 'Pencatatan awal', '2026-10-05 03:09:10');

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
(1, 1, 'TST001', 'Terlambat Masuk Sekolah Test Edit', 'Pelanggaran percobaan untuk pengujian sistem.', 5, 1, '2026-10-04 01:24:12', '2026-10-04 01:26:27'),
(2, 2, 'TST002', 'Terlambat Masuk Sekolah No', 'Terlambat tes', 3, 0, '2026-10-05 02:26:33', '2026-10-05 02:26:38');

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
(1, 'Kedisiplinan', NULL, 1, '2026-10-02 04:23:12', '2026-10-02 04:23:12'),
(2, 'Keterlambatan Test Edit', 'Kategori percobaan untuk pengujian sistem.', 1, '2026-10-04 01:20:13', '2026-10-04 01:20:55');

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

--
-- Dumping data for table `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `kelas_siswa_id`, `pelanggaran_id`, `guru_id`, `tanggal_pelanggaran`, `tanggal_dicatat`, `poin_saat_dicatat`, `keterangan`, `tindakan`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 3, '2026-10-04', '2026-10-04 08:33:40', 5, 'Siswa terlambat masuk sekolah.', 'Teguran dan pembinaan oleh guru.', 'dibatalkan', '2026-10-04 01:33:40', '2026-10-04 01:38:12'),
(2, 1, 1, 3, '2026-10-04', '2026-10-04 09:01:51', 5, 'Test', 'Test', 'aktif', '2026-10-04 02:01:51', '2026-10-04 02:01:51'),
(3, 1, 1, 4, '2026-10-05', '2026-10-05 09:28:33', 5, NULL, NULL, 'aktif', '2026-10-05 02:28:33', '2026-10-05 02:28:33'),
(4, 1, 1, 3, '2026-10-05', '2026-10-05 10:09:10', 5, NULL, NULL, 'aktif', '2026-10-05 03:09:10', '2026-10-05 03:09:10');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nisn`, `nama`, `jenis_kelamin`, `alamat`, `tahun_masuk`, `status_siswa`, `created_at`, `updated_at`) VALUES
(1, '0098765432', 'Andi Saputra Test', 'L', 'Jl. Melati No. 10, Tasikmalaya', 2026, 'aktif', '2026-10-03 00:44:15', '2026-10-12 07:39:42'),
(2, '0087654321', 'Budi Santoso', 'L', 'Jl. Mawar No. 5, Tasikmalaya.', 2026, 'aktif', '2026-10-03 00:53:50', '2026-10-03 00:58:33'),
(3, '0076543210', 'Citra Permata Test', 'P', 'Jl. Mawar No. 15, Tasikmalaya', 2026, 'aktif', '2026-10-04 01:16:02', '2026-10-04 01:18:14');

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
(1, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-10-03 00:29:32', '2026-10-03 00:30:29'),
(2, '2027/2028', '2027-07-01', '2028-06-30', 0, '2026-10-05 03:27:01', '2026-10-05 03:27:01');

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
(1, 'Administrator', 'admin@sekolahku.com', '$2y$12$1lC.HGeUEDLJQlirpcyMDOSDgiXkKu3Yw.mg.IlkGYvCX/w.dlJKe', '2026-10-01 05:55:02', '2026-10-02 04:22:16', 'admin'),
(2, 'Guru SekolahKu', 'guru@sekolahku.test', '$2y$12$GCkGmhTH/5SbSkpDx3.Muu2lxiKUtDKTvez6SEcGXFmz1yQpj5WSS', '2026-10-01 05:55:03', '2026-10-01 05:55:03', 'guru'),
(3, 'Budi Santoso', 'budi@skolahku.com', '$2y$12$/z6GzFJmCL.u0vxyh0jkp.1cbO0L7goQt0ptmiyU61W8V01TrCYUy', '2026-10-02 04:19:03', '2026-10-02 04:25:21', 'guru'),
(4, 'Anton Sanjaya', 'anton@sekolahku.com', '$2y$12$ZmTT24dW58essPq8WZrrhuGM6Mt5PFjKhhgDN0Vh90X7jN8JlX3di', '2026-10-02 04:28:32', '2026-10-02 04:28:32', 'guru'),
(5, 'Dedi Kurniawan', 'dedi.kurniawan@sekolahku.com', '$2y$12$e1SJ1GyOw/TGLH7H2ncOK.undQklLYwlanuBHrLoGFqh3fhQcuiIq', '2026-10-04 01:13:41', '2026-10-04 01:13:41', 'guru');

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
(1, 3, 2, 1, '2026-07-01', '2026-10-05', 0, '2026-10-04 01:02:03', '2026-10-05 03:48:53'),
(2, 3, 3, 2, '2027-07-01', NULL, 1, '2026-10-05 03:49:17', '2026-10-05 03:49:17');

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
  ADD UNIQUE KEY `t_siswa_nisn_unique` (`nisn`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
