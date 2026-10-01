<?php 

    session_start();
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../auth/auth.php';
    cek_role('admin');

    $total_users = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM t_users ")) ['c'];
    $total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM t_guru ")) ['c'];
    $total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM t_siswa ")) ['c'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="../src/output.css" rel="stylesheet">
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
                <a href="" class="flex items-center px-4 py-3 rounded-lg bg-gray-900 text-white font-medium mb-2 transition duration-500 t ">
                    Dashboard
                </a>

                <a href="siswa/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Data Siswa
                </a>

                <a href="guru/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Data Guru
                </a>

                <a href="kelas/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Data Kelas
                </a>

                <a href="pelanggaran/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Pelanggaran
                </a>

                <a href="laporan/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Laporan
                </a>

                <a href="pengguna/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white mb-2 transition duration-500">
                    Pengguna
                </a>
                <a href="about.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white transition duration-500">
                    About me
                </a>
            </div>

            <div class="absolute bottom-0 w-64 p-4">
                <a href="../logout.php" class="block px-4 py-3 rounded-lg text-gray-600 hover:bg-red-600 hover:text-white transition duration-500">
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

            <!-- Content -->
            <div class="p-6">

                <!-- Heading -->
                <div class="mb-6">
                    <p class="text-sm text-gray-400 mb-1">Dashboard</p>
                    <h2 class="text-2xl font-bold">
                        Selamat Datang, <?= htmlspecialchars($_SESSION['name']); ?>
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Berikut ringkasan data sekolah hari ini.
                    </p>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <p class="text-sm text-gray-500">Total Users</p>
                        <h3 class="text-3xl font-bold mt-2">
                            <?= $total_users ?>
                        </h3>
                        <p class="text-xs text-gray-400 mt-3">Total pengguna</p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <p class="text-sm text-gray-500">Total Guru</p>
                        <h3 class="text-3xl font-bold mt-2">
                            <?= $total_guru ?>
                        </h3>
                        <p class="text-xs text-gray-400 mt-3">Total guru</p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <p class="text-sm text-gray-500">Total Siswa</p>
                        <h3 class="text-3xl font-bold mt-2">-</h3>
                        <p class="text-xs text-gray-400 mt-3">Total siswa</p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <p class="text-sm text-gray-500">Pelanggaran</p>
                        <h3 class="text-3xl font-bold mt-2">-</h3>
                        <p class="text-xs text-gray-400 mt-3">Total pelanggaran</p>
                    </div>

                </div>

                <!-- Main Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Menu Utama -->
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
                        <div class="mb-5">
                            <h3 class="text-lg font-bold">Menu Utama</h3>
                            <p class="text-sm text-gray-400">
                                Kelola data sekolah
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <a href="siswa/index.php" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                <h4 class="font-semibold">Data Siswa</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    Kelola data siswa
                                </p>
                            </a>

                            <a href="guru/index.php" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                <h4 class="font-semibold">Data Guru</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    Kelola data guru
                                </p>
                            </a>

                            <a href="kelas/index.php" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                <h4 class="font-semibold">Data Kelas</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    Kelola data kelas
                                </p>
                            </a>

                            <a href="pelanggaran/index.php" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                <h4 class="font-semibold">Pelanggaran</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    Kelola pelanggaran siswa
                                </p>
                            </a>

                            <a href="laporan/index.php" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                <h4 class="font-semibold">Laporan</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    Lihat laporan sekolah
                                </p>
                            </a>

                            <a href="pengguna/index.php" class="border border-gray-200 rounded-lg p-5 hover:bg-gray-50 transition">
                                <h4 class="font-semibold">Pengguna</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    Kelola akun pengguna
                                </p>
                            </a>

                        </div>
                    </div>

                    <!-- Profile -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <p class="text-sm text-gray-400 mb-5">
                            Akun Anda
                        </p>

                        <div class="flex flex-col items-center text-center">
                            <div class="w-20 h-20 rounded-full bg-gray-900 text-white flex items-center justify-center text-2xl font-bold mb-4">
                                <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
                            </div>

                            <h3 class="text-lg font-bold">
                                <?= htmlspecialchars($_SESSION['name']); ?>
                            </h3>

                            <p class="text-sm text-gray-400 mt-1">
                                <?= htmlspecialchars($_SESSION['role']); ?>
                            </p>

                            <div class="w-full border-t border-gray-200 my-5"></div>

                            <div class="w-full text-left">
                                <p class="text-xs text-gray-400">Status</p>

                                <div class="flex items-center gap-2 mt-2">
                                    <span class="w-2 h-2 rounded-full bg-gray-900"></span>
                                    <span class="text-sm font-medium">
                                        Aktif
                                    </span>
                                </div>
                            </div>

                            <a href="../logout.php" class="w-full mt-6 px-4 py-3 rounded-lg  text-grey-500 font-medium border hover:text-white hover:bg-gray-800 transition duration-500">
                                Logout
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>

