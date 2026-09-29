<?php 

    session_start();
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../auth/auth.php';
    cek_role('guru');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>

    <style>
        a:hover {
            color: skyblue;
        }
    </style>
    
</head>
<body>

    <div>
        <h4>Selamat Datang <?=  htmlspecialchars ($_SESSION['name']); ?> || </h4>
        <p>Anda Login sebagai <b><?=  $_SESSION['role']; ?></b></p>
        <p><a href="../logout.php">Logout</a></p>
    </div>


    <h1>Dashboard Guru</h1> 

    <a href="siswa/index.php">Data Siswa</a><br>
    <a href="pelanggaran/index.php">Data Pelanggaran</a><br>
    <a href="riwayat/index.php">Riwayat Pelanggaran</a><br>
    
</body>
</html>