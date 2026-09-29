<?php 

    session_start();

    if ( isset($_SESSION['id'])) {
        header("Location: index.php");
        exit;
    }

    $err = $_GET['err'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>


   <?php if ($err === 'email'): ?>
        <p>Email tidak ditemukan!</p>
    <?php elseif ($err === 'password'): ?>
        <p>Password salah!</p>
    <?php elseif ($err === 'kosong'): ?>
        <p>Email dan password wajib diisi!</p>
    <?php endif; ?>


    <h2>Login </h2>

    <form action="proses/proses_login.php" method="POST">

    <p>Username: <input type="email" name="email" required placeholder="Masukkan email"></p>
    <p>Password: <input type="password" name="password" placeholder="Masukkan Password"></p>

    <button type="submit" name="Login">Login</button>

    </form>
    
</body>
</html>