<?php 

    session_start();
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../auth/auth.php';
    cek_role('admin');

    $msg = $_GET['msg'] ?? '';

    // === Mode Edit : Ambil data user ===

    $edit = null;
    if ( isset($_GET['edit'])) {
        $id = (int) $_GET['edit'];
        $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id = $id"));
    }

    // === Progres Hapus ====

    if ( isset($_GET['hapus'])) {
        $id = (int) $_GET['hapus'];
        if ($id != $_SESSION['id'] ) {
            $r = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nis, nisn, nama FROM t_siswa WHERE id = $id"));
            mysqli_query($koneksi, "DELETE FROM t_siswa WHERE id = $id");
            $msg = 'hapus_ok';
        }
    }

    // === Progres Tambah/Edit ====

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nis = mysqli_real_escape_string($koneksi, trim($_POST['nis'] ?? ''));
        $nisn = mysqli_real_escape_string($koneksi, trim($_POST['nisn'] ?? ''));
        $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama'] ?? ''));
        $jk = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin'] ?? ''));
        $tgl_lahir = trim($_POST['tanggal_lahir'] ?? '');
        $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat'] ?? ''));
        $status = mysqli_real_escape_string($koneksi, trim($_POST['status_aktif'] ?? ''));
        $id = (int) ($_POST['id'] ?? 0);

        if ( $id > 0 ) {
            mysqli_query($koneksi, "UPDATE t_siswa SET nis = '$nis', nisn = '$nisn', nama = '$nama', jenis_kelamin = '$jk', tanggal_lahir = '$tgl_lahir', alamat = '$alamat', status_aktif = '$status' WHERE id = $id");
            $msg = 'edit_ok';
        } else {
            mysqli_query($koneksi, "INSERT INTO t_siswa (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif) VALUES ('$nis', '$nisn', '$nama', '$jk', '$tgl_lahir', '$alamat', '$status')");
            $msg = 'tambah_ok';
        }
    }

    // Ambil data user (search) ===

    $search = mysqli_real_escape_string($koneksi, $_GET['search'] ?? '');
    $where = $search 
        ? "WHERE nis LIKE '%$search%' OR nisn LIKE '%$search%' OR nama LIKE '%$search%'"
        : '';
    $result = mysqli_query($koneksi, "SELECT * FROM t_siswa $where ORDER BY nama ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- <link href="../../src/output.css" rel="stylesheet"> -->
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:block">
            <div class="h-20 items-center px-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold">SMK</h1>
                <h1 class="text-2xl font-bold">MUHAMMADIYAH</h1>
            </div>

            <div class="p-4">
                <a href="../dashboard.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white font-medium mb-2 transition duration-500 t ">
                    Dashboard
                </a>

                <a href="../siswa/index.php" class="flex items-center px-4 py-3 rounded-lg bg-gray-900 text-white text-gray-600 mb-2 transition duration-500">
                    Data Siswa
                </a>

                <a href="../guru/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Data Guru
                </a>

                <a href="../kelas/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Data Kelas
                </a>

                <a href="../pelanggaran/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Pelanggaran
                </a>

                <a href="../laporan/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Laporan
                </a>

                <a href="../pengguna/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Pengguna
                </a>
                <a href="../about.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white transition duration-500">
                    About me
                </a>
            </div>

            <div class="absolute bottom-0 w-64 p-4">
                <a href="../../logout.php" class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-red-600 hover:text-white transition duration-500">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main -->
        <main class="flex-1">

            <!-- Topbar -->
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-6">
                <div class="hidden md:block">
                    <input
                        type="text"
                        placeholder="Search anything..."
                        class="w-80 bg-gray-100 border-none rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold">
                            <?= htmlspecialchars($_SESSION['name']); ?>
                        </p>
                        <p class="text-xs text-gray-400">
                            <?= htmlspecialchars($_SESSION['role']); ?>
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center font-semibold">
                        <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <?php if ($msg == 'tambah_ok')
                echo "<p><b>Siswa berhasil ditambahkan.</b></p>"; ?>
            <?php if ($msg == 'edit_ok')
                echo "<p><b>Siswa berhasil diubah.</b></p>"; ?>
            <?php if ($msg == 'hapus_ok')
                echo "<p><b>Siswa berhasil dihapus.</b></p>"; ?>

            <h3><?= $edit ? 'Edit Siswa' : 'Tambah Siswa' ?></h3>
            
            <form method="POST" action="index.php">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>
                
                <table border="1" cellpadding="3" c>
                    <tr>
                        <td>Nama :</td>
                        <td><input type="text" name="nama" value="<?= htmlspecialchars($edit['nama'] ?? '') ?>"></td>
                    </tr>
                    <tr>
                        <td>Nis :</td>
                        <td><input type="text" name="nis" value="<?= htmlspecialchars($edit['nis'] ?? '') ?>"></td>
                    </tr>
                    <tr>
                        <td>Nisn :</td>
                        <td><input type="text" name="nisn" value="<?= htmlspecialchars($edit['nisn'] ?? '') ?>"></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin :</td>
                        <td>
                            <select name="jenis_kelamin">
                                <option value="">== Pilih Jenis Kelamin ==</option>
                                <option value="L" <?= ($edit['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= ($edit['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir :</td>
                        <td><input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($edit['tanggal_lahir'] ?? '') ?>"></td>
                    </tr>
                    <tr>
                        <td>Alamat :</td>
                        <td><input name="alamat"><?= htmlspecialchars($edit['alamat'] ?? '') ?></input></td>
                    </tr>
                    <tr>
                        <td>Status Aktif :</td>
                        <td>
                            <select name="status_aktif">
                                <option value="1" <?= ($edit['status_aktif'] ?? '') == '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= ($edit['status_aktif'] ?? '') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>;
                        </td>
                    </tr>
                </table>
                <br>
                <button type="submit">
                    <?= $edit ? 'Simpan Perubahan' : 'Tambah Siswa' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="index.php">Batal</a>
                <?php endif; ?>
            </form>

            <h3>Daftar Siswa</h3>
            <form action="index.php" method="POST">
                Cari : <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Cari</button>
                <?php if ($search): ?>
                    <a href="index.php">Reset</a>
                <?php endif; ?>
            </form>

            <br>

            <table border="1" cellpadding="3" cellspacing="0">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nis</th>
                        <th>Nisn</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>

                    <?php $no=1; while ($r = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= htmlspecialchars($r['nis']) ?></td>
                            <td><?= htmlspecialchars($r['nisn']) ?></td>
                            <td><?= htmlspecialchars($r['jenis_kelamin']) ?></td>
                            <td><?= htmlspecialchars($r['tanggal_lahir']) ?></td>
                            <td><?= htmlspecialchars($r['alamat']) ?></td>
                            <td><?= $r['status_aktif'] == 1 ? 'Aktif' : 'Tidak Aktif' ?></td>
                            <td>
                                <a href="index.php?edit=<?= $r['id'] ?>">Edit</a> |
                                <a href="index.php?hapus=<?= $r['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
            </table>

        </main>
    </div>