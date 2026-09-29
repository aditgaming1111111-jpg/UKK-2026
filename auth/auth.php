<?php 

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function cek_login() {
        if ( empty($_SESSION['id'])) {
            header("Location: ../login.php");
            exit;
        }
    }

    function cek_role($role) {
        cek_login();
        if ( $_SESSION['role'] !== $role) {
            die ("Akses ditolak! Halaman ini hanya untuk: <b>$role</b>" . 
                 " <a href='/UKK-2026/index.php'>Kembali</a>");
        }
    }

    function cek_multi_role($roles) {
        cek_login();
        if ( !in_array($_SESSION['role'], $roles)) {
            die ("Akses ditolak! <a href='/UKK-2026/index.php'>Kembali</a>");
        }
    }