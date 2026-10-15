<?php
session_start();
require "config.php";

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = 'nasabah'; // Default daftar otomatis jadi nasabah
    $saldo = 0;

    // Cek apakah username sudah digunakan
    $check = mysqli_prepare($koneksi, "SELECT id_user FROM user WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $pesan = '<div class="alert alert-danger">Username sudah digunakan, pilih username lain!</div>';
    } else {
        // Simpan user baru (password disimpan teks biasa atau hash sesuai sistemmu)
        $stmt = mysqli_prepare($koneksi, "INSERT INTO user (nama, username, password, role, saldo) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssd", $nama, $username, $password, $role, $saldo);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php?register=success");
            exit;
        } else {
            $pesan = '<div class="alert alert-danger">Gagal mendaftar, coba lagi.</div>';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register Nasabah - Bank Sampah AUL</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
<div class="login-card">
    <div class="brand-icon">♻️</div>
    <h2>Daftar Nasabah</h2>
    <p class="text-muted mb-4">Gabung Bank Sampah AUL 🌷</p>
    
    <?= $pesan; ?>

    <form action="" method="post" class="text-start">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-pink w-100 mt-2">Daftar Sekarang</button>
    </form>
    
    <div class="text-center mt-3">
        <small class="text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none">Masuk di sini</a></small>
    </div>
</div>
</body>
</html>