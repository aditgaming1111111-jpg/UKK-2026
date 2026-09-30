-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Sep 2026 pada 07.21
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

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `isi_dummy_data` ()   BEGIN

    DECLARE i INT DEFAULT 1;
    DECLARE v_jurusan VARCHAR(10);
    DECLARE v_tingkat VARCHAR(10);
    DECLARE v_kategori INT;
    DECLARE v_poin INT;

    /* =========================================================
       1. TABEL t_users - 100 DATA
       User 1-50 digunakan sebagai akun guru
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        INSERT INTO t_users (
            id,
            name,
            email,
            email_verified_at,
            password,
            remember_token,
            role,
            created_at,
            updated_at
        )
        VALUES (
            i,
            CONCAT('User ', i),
            CONCAT('user', i, '@smkmuh-tasik.sch.id'),
            NOW(),

            /* Password default: password */
            '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',

            NULL,

            CASE
                WHEN i <= 50 THEN 'guru'
                WHEN i <= 75 THEN 'admin'
                ELSE 'petugas'
            END,

            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       2. TABEL t_guru - 50 DATA
       ========================================================= */
    SET i = 1;

    WHILE i <= 50 DO

        INSERT INTO t_guru (
            id,
            nip,
            nama,
            email,
            status_aktif,
            user_id,
            created_at,
            updated_at
        )
        VALUES (
            i,
            CONCAT('198', LPAD(i, 7, '0')),
            CONCAT('Guru ', LPAD(i, 2, '0')),
            CONCAT('guru', i, '@smkmuh-tasik.sch.id'),
            1,
            i,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       3. TABEL t_siswa - 100 DATA
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        INSERT INTO t_siswa (
            id,
            nis,
            nisn,
            nama,
            jenis_kelamin,
            tanggal_lahir,
            alamat,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,
            CONCAT('2026', LPAD(i, 4, '0')),
            CONCAT('00', LPAD(i, 8, '0')),
            CONCAT(
                CASE
                    WHEN MOD(i, 2) = 0 THEN 'Siswa Putra '
                    ELSE 'Siswa Putri '
                END,
                LPAD(i, 3, '0')
            ),

            CASE
                WHEN MOD(i, 2) = 0 THEN 'L'
                ELSE 'P'
            END,

            DATE_ADD(
                '2008-01-01',
                INTERVAL MOD(i * 17, 1000) DAY
            ),

            CONCAT(
                'Jl. Pendidikan No. ',
                i,
                ', Kota Tasikmalaya'
            ),

            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       4. TABEL t_pelanggaran_kategori - 100 DATA
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        INSERT INTO t_pelanggaran_kategori (
            id,
            nama,
            deskripsi,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,

            CONCAT(
                CASE MOD(i - 1, 5)
                    WHEN 0 THEN 'Kedisiplinan'
                    WHEN 1 THEN 'Kerapihan'
                    WHEN 2 THEN 'Kehadiran'
                    WHEN 3 THEN 'Etika'
                    WHEN 4 THEN 'Ketertiban'
                END,
                ' ',
                LPAD(i, 3, '0')
            ),

            CONCAT(
                'Kategori pelanggaran dummy nomor ',
                i,
                ' untuk keperluan pengujian sistem.'
            ),

            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       5. TABEL t_kelas - 100 DATA
       Nama kelas hanya:
       RPL, TKJ, TSM, BD, TKR
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        SET v_jurusan =
        CASE MOD(i - 1, 5)
            WHEN 0 THEN 'RPL'
            WHEN 1 THEN 'TKJ'
            WHEN 2 THEN 'TSM'
            WHEN 3 THEN 'BD'
            WHEN 4 THEN 'TKR'
        END;

        SET v_tingkat =
        CASE MOD(i - 1, 3)
            WHEN 0 THEN 'X'
            WHEN 1 THEN 'XI'
            WHEN 2 THEN 'XII'
        END;

        INSERT INTO t_kelas (
            id,
            nama,
            tingkat,
            jurusan,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,
            v_jurusan,
            v_tingkat,
            v_jurusan,
            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       6. TABEL t_tahun_ajaran - 100 DATA
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        INSERT INTO t_tahun_ajaran (
            id,
            nama,
            tanggal_mulai,
            tanggal_selesai,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,

            CONCAT(
                2025 + i,
                '/',
                2026 + i
            ),

            STR_TO_DATE(
                CONCAT(2025 + i, '-07-01'),
                '%Y-%m-%d'
            ),

            STR_TO_DATE(
                CONCAT(2026 + i, '-06-30'),
                '%Y-%m-%d'
            ),

            CASE
                WHEN i = 1 THEN 1
                ELSE 0
            END,

            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       7. TABEL t_pelanggaran - 100 DATA
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        SET v_kategori = MOD(i - 1, 100) + 1;

        SET v_poin =
        CASE MOD(i - 1, 5)
            WHEN 0 THEN 5
            WHEN 1 THEN 10
            WHEN 2 THEN 15
            WHEN 3 THEN 20
            WHEN 4 THEN 25
        END;

        INSERT INTO t_pelanggaran (
            id,
            pelanggaran_kategori_id,
            kode,
            nama,
            poin,
            deskripsi,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,
            v_kategori,
            CONCAT('PLG-', LPAD(i, 3, '0')),

            CONCAT(
                'Pelanggaran ',
                LPAD(i, 3, '0')
            ),

            v_poin,

            CONCAT(
                'Deskripsi pelanggaran dummy nomor ',
                i
            ),

            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       8. TABEL t_kelas_siswa - 100 DATA
       Setiap siswa memiliki kelas
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        INSERT INTO t_kelas_siswa (
            id,
            siswa_id,
            tahun_ajaran_id,
            kelas_id,
            tanggal_mulai,
            tanggal_selesai,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,

            i,

            /* menggunakan TA ID 1 */
            1,

            /* siswa dibagi ke kelas 1-100 */
            i,

            '2026-07-01',
            '2027-06-30',

            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       9. TABEL t_wali_kelas - 100 DATA
       Guru hanya 50 sehingga digunakan bergantian
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        INSERT INTO t_wali_kelas (
            id,
            tahun_ajaran_id,
            kelas_id,
            guru_id,
            tanggal_mulai,
            tanggal_selesai,
            status_aktif,
            created_at,
            updated_at
        )
        VALUES (
            i,

            /* tahun ajaran */
            MOD(i - 1, 100) + 1,

            /* kelas */
            i,

            /* guru 1 - 50 */
            MOD(i - 1, 50) + 1,

            '2026-07-01',
            '2027-06-30',

            1,
            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;


    /* =========================================================
       10. TABEL t_pelanggaran_siswa - 100 DATA
       ========================================================= */
    SET i = 1;

    WHILE i <= 100 DO

        SET v_jurusan =
        CASE MOD(i - 1, 5)
            WHEN 0 THEN 'RPL'
            WHEN 1 THEN 'TKJ'
            WHEN 2 THEN 'TSM'
            WHEN 3 THEN 'BD'
            WHEN 4 THEN 'TKR'
        END;

        SET v_poin =
        CASE MOD(i - 1, 5)
            WHEN 0 THEN 5
            WHEN 1 THEN 10
            WHEN 2 THEN 15
            WHEN 3 THEN 20
            WHEN 4 THEN 25
        END;

        INSERT INTO t_pelanggaran_siswa (
            id,
            tahun_ajaran_id,
            siswa_id,
            nama_siswa,
            kelas_id,
            nama_kelas,
            pelanggaran_id,
            nama_pelanggaran,
            pelanggaran_kategori_id,
            guru_id,
            nama_guru,
            tanggal,
            keterangan,
            poin,
            tindakan,
            status,
            created_at,
            updated_at
        )
        VALUES (
            i,

            1,

            i,

            CONCAT(
                CASE
                    WHEN MOD(i, 2) = 0 THEN 'Siswa Putra '
                    ELSE 'Siswa Putri '
                END,
                LPAD(i, 3, '0')
            ),

            i,

            v_jurusan,

            i,

            CONCAT(
                'Pelanggaran ',
                LPAD(i, 3, '0')
            ),

            i,

            MOD(i - 1, 50) + 1,

            CONCAT(
                'Guru ',
                LPAD(MOD(i - 1, 50) + 1, 2, '0')
            ),

            DATE_ADD(
                '2026-07-01',
                INTERVAL MOD(i * 2, 90) DAY
            ),

            CONCAT(
                'Catatan pelanggaran siswa nomor ',
                i
            ),

            v_poin,

            CASE MOD(i - 1, 4)
                WHEN 0 THEN 'Teguran lisan'
                WHEN 1 THEN 'Teguran tertulis'
                WHEN 2 THEN 'Pembinaan oleh wali kelas'
                WHEN 3 THEN 'Pemanggilan orang tua'
            END,

            CASE MOD(i - 1, 3)
                WHEN 0 THEN 'Diproses'
                WHEN 1 THEN 'Selesai'
                WHEN 2 THEN 'Pembinaan'
            END,

            NOW(),
            NOW()
        );

        SET i = i + 1;

    END WHILE;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_ar` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) DEFAULT NULL,
  `kode` varchar(30) DEFAULT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `poin` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `pelanggaran_id` int(11) DEFAULT NULL,
  `nama_pelanggaran` varchar(150) NOT NULL,
  `pelanggaran_kategori_id` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text NOT NULL,
  `poin` int(11) NOT NULL,
  `tindakan` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `jenis_kelamin` char(1) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'User 1', 'user1@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(2, 'User 2', 'user2@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(3, 'User 3', 'user3@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(4, 'User 4', 'user4@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(5, 'User 5', 'user5@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(6, 'User 6', 'user6@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(7, 'User 7', 'user7@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(8, 'User 8', 'user8@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(9, 'User 9', 'user9@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(10, 'User 10', 'user10@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(11, 'User 11', 'user11@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(12, 'User 12', 'user12@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(13, 'User 13', 'user13@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(14, 'User 14', 'user14@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(15, 'User 15', 'user15@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(16, 'User 16', 'user16@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(17, 'User 17', 'user17@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(18, 'User 18', 'user18@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(19, 'User 19', 'user19@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(20, 'User 20', 'user20@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(21, 'User 21', 'user21@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(22, 'User 22', 'user22@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(23, 'User 23', 'user23@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(24, 'User 24', 'user24@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(25, 'User 25', 'user25@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(26, 'User 26', 'user26@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(27, 'User 27', 'user27@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(28, 'User 28', 'user28@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(29, 'User 29', 'user29@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(30, 'User 30', 'user30@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(31, 'User 31', 'user31@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(32, 'User 32', 'user32@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(33, 'User 33', 'user33@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(34, 'User 34', 'user34@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(35, 'User 35', 'user35@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(36, 'User 36', 'user36@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(37, 'User 37', 'user37@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(38, 'User 38', 'user38@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(39, 'User 39', 'user39@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(40, 'User 40', 'user40@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(41, 'User 41', 'user41@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(42, 'User 42', 'user42@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(43, 'User 43', 'user43@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(44, 'User 44', 'user44@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(45, 'User 45', 'user45@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(46, 'User 46', 'user46@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(47, 'User 47', 'user47@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(48, 'User 48', 'user48@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(49, 'User 49', 'user49@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(50, 'User 50', 'user50@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'guru', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(51, 'User 51', 'user51@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(52, 'User 52', 'user52@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(53, 'User 53', 'user53@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(54, 'User 54', 'user54@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(55, 'User 55', 'user55@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(56, 'User 56', 'user56@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(57, 'User 57', 'user57@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(58, 'User 58', 'user58@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(59, 'User 59', 'user59@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(60, 'User 60', 'user60@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(61, 'User 61', 'user61@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(62, 'User 62', 'user62@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(63, 'User 63', 'user63@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(64, 'User 64', 'user64@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(65, 'User 65', 'user65@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(66, 'User 66', 'user66@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(67, 'User 67', 'user67@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(68, 'User 68', 'user68@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(69, 'User 69', 'user69@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(70, 'User 70', 'user70@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(71, 'User 71', 'user71@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(72, 'User 72', 'user72@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(73, 'User 73', 'user73@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(74, 'User 74', 'user74@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(75, 'User 75', 'user75@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'admin', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(76, 'User 76', 'user76@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(77, 'User 77', 'user77@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(78, 'User 78', 'user78@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(79, 'User 79', 'user79@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(80, 'User 80', 'user80@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(81, 'User 81', 'user81@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(82, 'User 82', 'user82@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(83, 'User 83', 'user83@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(84, 'User 84', 'user84@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(85, 'User 85', 'user85@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(86, 'User 86', 'user86@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(87, 'User 87', 'user87@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(88, 'User 88', 'user88@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(89, 'User 89', 'user89@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(90, 'User 90', 'user90@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(91, 'User 91', 'user91@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(92, 'User 92', 'user92@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(93, 'User 93', 'user93@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(94, 'User 94', 'user94@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(95, 'User 95', 'user95@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(96, 'User 96', 'user96@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(97, 'User 97', 'user97@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(98, 'User 98', 'user98@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(99, 'User 99', 'user99@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02'),
(100, 'User 100', 'user100@smkmuh-tasik.sch.id', '2026-09-28 05:21:02', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'petugas', '2026-09-28 05:21:02', '2026-09-28 05:21:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`);

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
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`);

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
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `t_guru_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_wali_kelas` (`guru_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_guru_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD CONSTRAINT `t_kelas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_kelas_siswa` (`kelas_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `t_pelanggaran_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_pelanggaran_siswa` (`pelanggaran_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_ibfk_2` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD CONSTRAINT `t_pelanggaran_kategori_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_pelanggaran_siswa` (`pelanggaran_kategori_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_3` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_4` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD CONSTRAINT `t_siswa_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_kelas_siswa` (`siswa_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD CONSTRAINT `t_tahun_ajaran_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_kelas_siswa` (`tahun_ajaran_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `t_wali_kelas_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_2` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
