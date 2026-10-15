<?php
require_once '../auth.php';
wajib_role('pengepul');
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$sessionUserId = (int)($_SESSION['id_user'] ?? 0);
$sessionUsername = (string)($_SESSION['username'] ?? '');

function h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function rupiah_p($value) { return 'Rp' . number_format((float)$value, 0, ',', '.'); }

function get_pengepul_id($koneksi, $userId, $username) {
    $username = mysqli_real_escape_string($koneksi, $username);
    $q = mysqli_query($koneksi, "SELECT id_pengepul FROM pengepul WHERE username='$username' LIMIT 1");
    if ($q && ($row = mysqli_fetch_assoc($q))) return (int)$row['id_pengepul'];

    $q = mysqli_query($koneksi, "SHOW COLUMNS FROM pengepul LIKE 'id_user'");
    if ($q && mysqli_num_rows($q) > 0) {
        $uid = (int)$userId;
        $q2 = mysqli_query($koneksi, "SELECT id_pengepul FROM pengepul WHERE id_user=$uid LIMIT 1");
        if ($q2 && ($row = mysqli_fetch_assoc($q2))) return (int)$row['id_pengepul'];
    }
    return 0;
}

$idPengepul = get_pengepul_id($koneksi, $sessionUserId, $sessionUsername);
if ($idPengepul <= 0) {
    $msg = 'Akun Pengepul belum terhubung ke tabel pengepul. Jalankan database_patch_pengepul.sql atau samakan username akun user dengan username pada tabel pengepul.';
}
