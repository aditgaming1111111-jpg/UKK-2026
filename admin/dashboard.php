<?php 

    session_start();
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../auth/auth.php';
    cek_role('admin');

    $total_users = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM t_users ")) ['c'];
    $total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM t_guru ")) ['c'];
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <style>
        a:hover {
            color: skyblue;
        }
    </style>

</head>
<body>
    <div>

        <h4>Selamat Datang <?=  htmlspecialchars ($_SESSION['name']); ?> || </h4>
        <p>Anda Login sebagai <?=  $_SESSION['role']; ?></p>
        <p><a href="../logout.php">Logout</a></p>

    </div>

    <hr>

    <h1>Dashboard Admin</h1> 

    <a href="siswa/index.php">Data Siswa</a><br>
    <a href="guru/index.php">Data Guru</a><br>
    <a href="kelas/index.php">Data Kelas</a><br>
    <a href="pelanggaran/index.php">Data Pelanggaran</a><br>
    <a href="laporan/index.php">Laporan</a><br>
    <a href="pengguna/index.php">Pengguna</a><br>

    <table border="1" cellpadding="3">
        <h3>Total Users</h3>
        <tr>
            <th>Total Users</th>
            <th>Total Guru</th>
        </tr>
        <tr>
            <td><?= $total_users ?></td>
            <td><?= $total_guru ?></td>
        </tr>
    </table>
    
</body>
</html>