<?php 

    session_start();
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../auth/auth.php';
    cek_role('admin');
?>