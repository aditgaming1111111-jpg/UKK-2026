-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2026 at 12:58 PM
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sekolahku-cache-818cb4847ce323a3aa5cc5587b165e84dcc60a71', 'i:1;', 1792073405),
('sekolahku-cache-818cb4847ce323a3aa5cc5587b165e84dcc60a71:timer', 'i:1792073405;', 1792073405),
('sekolahku-cache-9fb7e9d5af1dbc37c9464e14407f3a9299f9bce6', 'i:1;', 1792498856),
('sekolahku-cache-9fb7e9d5af1dbc37c9464e14407f3a9299f9bce6:timer', 'i:1792498856;', 1792498856),
('sekolahku-cache-bb2926e45c06ec8262bb785a3aae4dd2ffaca6d2', 'i:1;', 1792069644),
('sekolahku-cache-bb2926e45c06ec8262bb785a3aae4dd2ffaca6d2:timer', 'i:1792069644;', 1792069644),
('sekolahku-cache-fc26eb645d3edf739579e766ba23e8f5291583bb', 'i:1;', 1791991221),
('sekolahku-cache-fc26eb645d3edf739579e766ba23e8f5291583bb:timer', 'i:1791991221;', 1791991221);

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
(6, '2026_10_01_155432_add_unique_index_to_t_pelanggaran_kode', 1);

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
('WhekWjycCkP8Od9pOF6iBMhn5UMrIwjOqbfxxGvG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJKMVlaRElUN3AxUU5yNWxhZUVOSHhneEFpSVM3SUtWNmRmWXJHUVU2IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL2FkbWluIiwicm91dGUiOiJhZG1pbi5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiNmMzNGM2N2I4ZTc4YTgxNDhlNjBmNTMzOTllNzA5ZDA5N2Q0NGMxNWExZDM4ZWQyOTkwZDcwMDdhMWM1YWE3NyJ9', 1792501085);

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
(1, '1980010120260001', 'Anton Saputra', 'anton@sekolahku.com', 1, 2, '2026-10-14 07:47:00', '2026-10-14 08:16:53'),
(2, '123456789123456789', 'Surya Santoso', 'surya@sekolahku.com', 1, 3, '2026-10-20 04:49:05', '2026-10-20 04:49:05'),
(3, '123456780000000017', 'Andi Saputra', 'andisaputra@sekolahku.com', 1, 4, '2026-10-20 05:19:42', '2026-10-20 05:19:42');

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
(1, 'X RPL 1', '10', 'RPL', '2026-10-14 08:03:01', '2026-10-14 08:03:01'),
(2, 'X RPL 2', '10', 'RPL', '2026-10-20 04:51:16', '2026-10-20 04:51:16');

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
(1, 1, 1, 1, '2026-07-01', NULL, 1, '2026-10-14 08:04:42', '2026-10-14 08:04:42'),
(2, 2, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:21:19', '2026-10-19 06:21:19'),
(3, 4, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:28:14', '2026-10-19 06:28:14'),
(4, 3, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:28:36', '2026-10-19 06:28:36'),
(5, 10, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:28:57', '2026-10-19 06:28:57'),
(6, 7, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:29:17', '2026-10-19 06:29:17'),
(7, 6, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:29:37', '2026-10-19 06:29:37'),
(8, 8, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:29:57', '2026-10-19 06:29:57'),
(9, 11, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:30:16', '2026-10-19 06:30:16'),
(10, 9, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:30:38', '2026-10-19 06:30:38'),
(11, 5, 1, 1, '2026-07-01', NULL, 1, '2026-10-19 06:30:57', '2026-10-19 06:30:57'),
(12, 12, 2, 1, '2026-07-01', NULL, 1, '2026-10-20 04:53:47', '2026-10-20 04:53:47');

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
(1, 1, 2, 'tambah', NULL, '{\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-14 00:00:00\",\"tanggal_dicatat\":\"2026-10-14 15:12:15\",\"poin_saat_dicatat\":5,\"keterangan\":\"Andi datang terlambat saat jam masuk sekolah\",\"tindakan\":null,\"status\":\"aktif\",\"updated_at\":\"2026-10-14 15:12:15\",\"created_at\":\"2026-10-14 15:12:15\",\"id\":1}', 'Pencatatan awal', '2026-10-14 08:12:15'),
(2, 1, 2, 'edit', '{\"id\":1,\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-14\",\"tanggal_dicatat\":\"2026-10-14 15:12:15\",\"poin_saat_dicatat\":5,\"keterangan\":\"Andi datang terlambat saat jam masuk sekolah\",\"tindakan\":null,\"status\":\"aktif\",\"created_at\":\"2026-10-14 15:12:15\",\"updated_at\":\"2026-10-14 15:12:15\"}', '{\"id\":1,\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-14 00:00:00\",\"tanggal_dicatat\":\"2026-10-14 15:12:15\",\"poin_saat_dicatat\":5,\"keterangan\":\"Andi datang terlambat saat jam masuk sekolah. Sudah diberikan teguran oleh guru piket.\",\"tindakan\":null,\"status\":\"aktif\",\"created_at\":\"2026-10-14 15:12:15\",\"updated_at\":\"2026-10-14 15:30:48\"}', 'TEST', '2026-10-14 08:30:48'),
(3, 2, 2, 'tambah', NULL, '{\"kelas_siswa_id\":5,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-19 00:00:00\",\"tanggal_dicatat\":\"2026-10-19 13:59:42\",\"poin_saat_dicatat\":5,\"keterangan\":\"Terlambat masuk sekolah 15 menit\",\"tindakan\":\"Membaca 10 surah pendek\",\"status\":\"aktif\",\"updated_at\":\"2026-10-19 13:59:42\",\"created_at\":\"2026-10-19 13:59:42\",\"id\":2}', 'Pencatatan awal', '2026-10-19 06:59:42'),
(4, 3, 2, 'tambah', NULL, '{\"kelas_siswa_id\":3,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-15 00:00:00\",\"tanggal_dicatat\":\"2026-10-19 14:00:31\",\"poin_saat_dicatat\":5,\"keterangan\":\"Terlambat datang ke sekolah\",\"tindakan\":\"Membersihkan lapangan\",\"status\":\"aktif\",\"updated_at\":\"2026-10-19 14:00:31\",\"created_at\":\"2026-10-19 14:00:31\",\"id\":3}', 'Pencatatan awal', '2026-10-19 07:00:31'),
(5, 4, 2, 'tambah', NULL, '{\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-07 00:00:00\",\"tanggal_dicatat\":\"2026-10-19 14:01:43\",\"poin_saat_dicatat\":5,\"keterangan\":\"Terlambat datang ke sekolah 1 jam\",\"tindakan\":\"membaca al quran\",\"status\":\"aktif\",\"updated_at\":\"2026-10-19 14:01:43\",\"created_at\":\"2026-10-19 14:01:43\",\"id\":4}', 'Pencatatan awal', '2026-10-19 07:01:43'),
(6, 4, 2, 'batal', '{\"id\":4,\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-07\",\"tanggal_dicatat\":\"2026-10-19 14:01:43\",\"poin_saat_dicatat\":5,\"keterangan\":\"Terlambat datang ke sekolah 1 jam\",\"tindakan\":\"membaca al quran\",\"status\":\"aktif\",\"created_at\":\"2026-10-19 14:01:43\",\"updated_at\":\"2026-10-19 14:01:43\"}', '{\"id\":4,\"kelas_siswa_id\":1,\"pelanggaran_id\":1,\"guru_id\":1,\"tanggal_pelanggaran\":\"2026-10-07\",\"tanggal_dicatat\":\"2026-10-19 14:01:43\",\"poin_saat_dicatat\":5,\"keterangan\":\"Terlambat datang ke sekolah 1 jam\",\"tindakan\":\"membaca al quran\",\"status\":\"dibatalkan\",\"created_at\":\"2026-10-19 14:01:43\",\"updated_at\":\"2026-10-19 14:02:39\"}', 'Sakit pulang dari rumah sakit, Datang ke sekolah', '2026-10-19 07:02:39');

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
(1, 1, 'PLG-001', 'Terlambat datang ke sekolah', 'Siswa datang ke sekolah melewati waktu masuk yang telah ditetapkan tanpa alasan yang dapat diterima oleh pihak sekolah.', 5, 1, '2026-10-14 08:10:14', '2026-10-14 08:10:14');

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
(1, 'Kedisiplinan', 'Ketidakpatuhan siswa terhadap tata tertib dan aturan kedisiplinan sekolah.', 1, '2026-10-14 08:08:58', '2026-10-14 08:08:58');

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
(1, 1, 1, 1, '2026-10-14', '2026-10-14 15:12:15', 5, 'Andi datang terlambat saat jam masuk sekolah. Sudah diberikan teguran oleh guru piket.', NULL, 'aktif', '2026-10-14 08:12:15', '2026-10-14 08:30:48'),
(2, 5, 1, 1, '2026-10-19', '2026-10-19 13:59:42', 5, 'Terlambat masuk sekolah 15 menit', 'Membaca 10 surah pendek', 'aktif', '2026-10-19 06:59:42', '2026-10-19 06:59:42'),
(3, 3, 1, 1, '2026-10-15', '2026-10-19 14:00:31', 5, 'Terlambat datang ke sekolah', 'Membersihkan lapangan', 'aktif', '2026-10-19 07:00:31', '2026-10-19 07:00:31'),
(4, 1, 1, 1, '2026-10-07', '2026-10-19 14:01:43', 5, 'Terlambat datang ke sekolah 1 jam', 'membaca al quran', 'dibatalkan', '2026-10-19 07:01:43', '2026-10-19 07:02:39');

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
(1, '0061234567', 'Andi Saputra', 'L', 'Jl. Melati No. 10, Tasikmalaya', 2026, 'aktif', '2026-10-14 08:03:48', '2026-10-14 08:03:48'),
(2, '0987612345', 'Sri Lestari', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-10-19 06:20:39', '2026-10-19 06:20:39'),
(3, '0000000001', 'Andi Santoso', 'L', 'Tasikamalaya', 2026, 'aktif', '2026-10-19 06:22:08', '2026-10-19 06:22:08'),
(4, '0000000002', 'Ali Syahbana', 'L', 'Cilolohan', 2026, 'aktif', '2026-10-19 06:22:40', '2026-10-19 06:22:40'),
(5, '0000000003', 'Zahru Fikri', 'L', 'Kawalu', 2026, 'aktif', '2026-10-19 06:23:08', '2026-10-19 06:23:08'),
(6, '0000000004', 'Nazril Ilham', 'L', 'Ciberem', 2026, 'aktif', '2026-10-19 06:23:33', '2026-10-19 06:23:33'),
(7, '0000000005', 'Fitri indah', 'P', 'Tasikmalaya', 2026, 'aktif', '2026-10-19 06:24:02', '2026-10-19 06:24:02'),
(8, '0000000006', 'Nijar Saputra', 'L', 'Ciamis', 2026, 'aktif', '2026-10-19 06:24:31', '2026-10-19 06:24:31'),
(9, '0000000007', 'Sofi Nurjanah', 'P', 'Tasikamalaya', 2026, 'aktif', '2026-10-19 06:25:58', '2026-10-19 06:25:58'),
(10, '0000000008', 'Arfa belingham', 'P', 'Tasimalaya', 2026, 'aktif', '2026-10-19 06:26:20', '2026-10-19 06:26:20'),
(11, '0000000009', 'Ressa Juiansayah', 'L', 'Cilolohan', 2026, 'aktif', '2026-10-19 06:26:51', '2026-10-19 06:26:51'),
(12, '0000000078', 'Abdul somad', 'L', 'Tasik', 2026, 'aktif', '2026-10-20 04:53:35', '2026-10-20 04:53:35');

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
(1, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-10-14 08:02:28', '2026-10-14 08:02:28');

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
(1, 'Administrator SekolahKu', 'admin@sekolahku.com', '$2y$12$pxbv9apoz8s2DbfzJ9OW2OvxptH6X/rJ/xL1pMH6L5Jc/XcOli67.', '2026-10-14 07:47:00', '2026-10-14 08:18:31', 'admin'),
(2, 'Anton Saputra', 'anton@sekolahku.com', '$2y$12$YYmP6kFFK0dUqLsxe3MrRO9b5ce1NmXCS/4s10lEeLdNEHUvnWr16', '2026-10-14 07:47:00', '2026-10-14 08:20:32', 'guru'),
(3, 'Surya Santoso', 'surya@sekolahku.com', '$2y$12$0QGsZ.AmqOlMmWhy1zVxMOhUMRn1PNBq7omrIbUw1b8sdetBgYpgW', '2026-10-20 04:49:05', '2026-10-20 04:49:05', 'guru'),
(4, 'Andi Saputra', 'andisaputra@sekolahku.com', '$2y$12$dFvrKoQpG5O.myuNX51HKeuZQ1Mcn3YrQyQcRPf3R98aENqhja4my', '2026-10-20 05:19:42', '2026-10-20 05:19:42', 'guru');

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
(1, 1, 1, 1, '2026-07-01', NULL, 1, '2026-10-15 06:05:58', '2026-10-15 06:05:58');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_log_pelanggaran`
--
ALTER TABLE `t_log_pelanggaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
