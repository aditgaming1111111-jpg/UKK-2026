<?php

    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $tujuan = [
        'admin' => 'admin/dashboard.php',
        'guru' => 'guru/dashboard.php'
    ];

    $role = $_SESSION['role'] ?? '';

    if (isset($tujuan[$role])) {
        header("Location: " . $tujuan[$role]);
        exit;
    } else {
        header("Location: logout.php");
        exit;
    }

?>