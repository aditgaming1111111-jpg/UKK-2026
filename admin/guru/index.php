<?php 

    session_start();
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../auth/auth.php';
    cek_role('admin');

    $msg = $_GET['msg'] ?? '';

    // === Mode Edit : Ambil data Guru ===

    $edit = null;
    if ( isset($_GET['edit'])) {
        $id = (int) $_GET['edit'];
        $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_guru WHERE id = $id"));
    }

    // === Progres Hapus ====

    if ( isset($_GET['hapus'])) {
        $id = (int) $_GET['hapus'];
        if ($id != $_SESSION['id'] ) {
            $r = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nip, nama FROM t_guru WHERE id = $id"));
            mysqli_query($koneksi, "DELETE FROM t_guru WHERE id = $id");
            $msg = 'hapus_ok';
        }
    }

    // === Progres Tambah/Edit ====

    // === Proses Tambah/Edit Guru ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $status = (int) ($_POST['status_aktif'] ?? 1);
    $id = (int) ($_POST['id'] ?? 0);

    if ($nip === '' || $nama === '' || $email === '') {
        $msg = 'data_kosong';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'email_invalid';

    } elseif (!in_array($status, [0, 1], true)) {
        $msg = 'status_invalid';

    } elseif ($id > 0) {

        // EDIT DATA GURU
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_guru
             SET nip = ?, nama = ?, email = ?, status_aktif = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssii",
            $nip,
            $nama,
            $email,
            $status,
            $id
        );

        try {
            mysqli_stmt_execute($stmt);
            $msg = 'edit_ok';
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage());
            $msg = 'gagal';
        }

        mysqli_stmt_close($stmt);

    } else {

        // TAMBAH GURU SEKALIGUS AKUN LOGIN
        if ($password === '') {
            $msg = 'password_kosong';

        } elseif (strlen($password) < 8) {
            $msg = 'password_pendek';

        } else {
            try {
                mysqli_begin_transaction($koneksi);

                // 1. Buat akun pengguna
                $password_hash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $role = 'guru';

                $stmt = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_users (name, email, password, role)
                     VALUES (?, ?, ?, ?)"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssss",
                    $nama,
                    $email,
                    $password_hash,
                    $role
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // 2. Ambil ID akun yang baru dibuat
                $user_id = mysqli_insert_id($koneksi);

                // 3. Simpan data guru
                $stmt = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_guru
                     (nip, nama, email, user_id, status_aktif)
                     VALUES (?, ?, ?, ?, ?)"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssii",
                    $nip,
                    $nama,
                    $email,
                    $user_id,
                    $status
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Simpan kedua data
                mysqli_commit($koneksi);

                header("Location: index.php?msg=tambah_ok");
                exit;

            } catch (Throwable $e) {
                mysqli_rollback($koneksi);

                error_log("Tambah guru gagal: " . $e->getMessage());

                if (
                    $e instanceof mysqli_sql_exception &&
                    (int) $e->getCode() === 1062
                ) {
                    $msg = 'duplikat';
                } else {
                    $msg = 'gagal';
                }
            }
        }
    }
}

    // Ambil data user (search) ===

    $search = mysqli_real_escape_string($koneksi, $_GET['search'] ?? '');
    $where = $search 
        ? "WHERE nip LIKE '%$search%' OR nama LIKE '%$search%'"
        : '';
    $result = mysqli_query($koneksi, "SELECT * FROM t_guru $where ORDER BY nama ASC");

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

                <a href="../siswa/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Data Siswa
                </a>

                <a href="../guru/index.php" class="flex items-center px-4 py-3 rounded-lg bg-gray-900 text-white text-gray-600 mb-2 transition duration-500">
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

            <?php if ($msg === 'tambah_ok'): ?>
                <p>Guru dan akun login berhasil ditambahkan.</p>

            <?php elseif ($msg === 'edit_ok'): ?>
                <p>Data guru berhasil diperbarui.</p>

            <?php elseif ($msg === 'data_kosong'): ?>
                <p>NIP, nama, dan email wajib diisi.</p>

            <?php elseif ($msg === 'email_invalid'): ?>
                <p>Format email tidak valid.</p>

            <?php elseif ($msg === 'password_kosong'): ?>
                <p>Password wajib diisi saat menambah guru.</p>

            <?php elseif ($msg === 'password_pendek'): ?>
                <p>Password minimal 8 karakter.</p>

            <?php elseif ($msg === 'status_invalid'): ?>
                <p>Status guru tidak valid.</p>

            <?php elseif ($msg === 'duplikat'): ?>
                <p>NIP atau email sudah digunakan.</p>

            <?php elseif ($msg === 'gagal'): ?>
                <p>Data gagal disimpan. Periksa log server.</p>
            <?php endif; ?>
            <?php if ($msg === 'hapus_ok'):
                echo "<p><b>Guru berhasil dihapus.</b></p>";
            endif; ?>

            <h3><?= $edit ? 'Edit Guru' : 'Tambah Guru' ?></h3>
            
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
                        <td>Nip :</td>
                        <td><input type="text" name="nip" value="<?= htmlspecialchars($edit['nip'] ?? '') ?>"></td>
                    </tr>
                    <tr>
                        <td>Email :</td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($edit['email'] ?? '') ?>"></td>
                    </tr>
                    <tr>
                        <td>Status Aktif :</td>
                        <td>
                            <select name="status_aktif">
                                <option value="">== Pilih ==</option>
                                <option value="1" <?= ($edit['status_aktif'] ?? '') == '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= ($edit['status_aktif'] ?? '') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>;
                        </td>
                    </tr>
                    <tr>
                        <td>Password :</td>
                        <td><input type="password" name="password" value="<?= htmlspecialchars($edit['password'] ?? '') ?>"></td>
                    </tr>
                </table>
                <br>
                <button type="submit">
                    <?= $edit ? 'Simpan Perubahan' : 'Tambah Guru' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="index.php">Batal</a>
                <?php endif; ?>
            </form>

            <h3>Daftar Guru</h3>
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
                        <th>Nip</th>
                        <th>Email</th>
                        <th>Users Id</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>

                    <?php $no=1; while ($r = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= htmlspecialchars($r['nip']) ?></td>
                            <td><?= htmlspecialchars($r['email']) ?></td>
                            <td><?= htmlspecialchars($r['user_id']) ?></td>
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