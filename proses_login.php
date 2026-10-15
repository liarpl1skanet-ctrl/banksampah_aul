<?php
session_start();
require "config.php";

$username = trim($_POST['username'] ?? '');
$role_pilih = strtolower(trim($_POST['role'] ?? ''));
$password = $_POST['password'] ?? '';

$stmt = mysqli_prepare($koneksi, "SELECT id_user, nama, username, password, role, saldo FROM user WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if ($data) {
    $db_password = $data['password'];
    
    // Cek password (bisa hash atau teks biasa)
    $password_cocok = (password_verify($password, $db_password) || $password === $db_password);
    
    if ($password_cocok) {
        $role_db = strtolower(trim($data['role']));
        
        // Toleransi jika di database tertulis 'user' tapi yang dipilih 'nasabah'
        $role_valid = ($role_pilih === $role_db) || 
                      ($role_pilih === 'nasabah' && ($role_db === 'user' || $role_db === 'nasabah'));

        if ($role_valid) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['saldo'] = $data['saldo'];
            
            header("Location: dashboard.php");
            exit;
        }
    }
}

header("Location: login.php?error=1");
exit;
?>