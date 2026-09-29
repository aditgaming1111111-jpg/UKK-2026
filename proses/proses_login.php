<?php
    session_start();

    require_once __DIR__ . '/../config/database.php';

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        header("Location: ../login.php?err=kosong");
        exit;
    }

    $query = "SELECT * FROM t_users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {
            // Password cocok, buat session
            $_SESSION['login'] = true;
            $_SESSION['id'] = $data['id'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['role'] = $data['role'];

            header("Location: ../index.php");
            exit;
        } else {
            header("Location: ../login.php?err=password");
            exit;
        }
    } else {
        header("Location: ../login.php?err=email");
        exit;
}
?>