<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$folder = $_SESSION['role'];
header("Location: " . $folder . "/index.php");
exit;
?>