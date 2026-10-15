<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function wajib_login() {
    if (!isset($_SESSION['id_user'])) {
        // Deteksi apakah file dipanggil dari dalam subfolder (admin/nasabah/pengepul) atau di root
        $path_prefix = (file_exists("login.php")) ? "" : "../";
        header("Location: " . $path_prefix . "login.php");
        exit;
    }
}

function wajib_role($role) {
    wajib_login();
    if (strtolower($_SESSION['role']) !== strtolower($role)) {
        $path_prefix = (file_exists("dashboard.php")) ? "" : "../";
        header("Location: " . $path_prefix . "dashboard.php");
        exit;
    }
}

function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}
?>