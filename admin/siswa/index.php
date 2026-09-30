<?php 

    session_start();
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../auth/auth.php';
    cek_role('admin');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="../../src/output.css" rel="stylesheet">
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

                <a href="../pengguna/index.php" class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-800 hover:text-white transition duration-500">
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