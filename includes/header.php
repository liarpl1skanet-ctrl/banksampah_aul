<?php
/**
 * Dipanggil setelah $_SESSION dipastikan aktif (lewat auth.php).
 * Variabel yang dipakai dari halaman pemanggil:
 * $page_title  -> judul tab browser
 * $active      -> key menu aktif (dashboard, sampah, users, transaksi, pengepul, riwayat, setor, tarik, profil, nasabah)
 * $base        -> "../" jika dipanggil dari dalam folder role, "" jika dari root
 */
$base = $base ?? "../";
$role = $_SESSION['role'] ?? 'user';
$nama = $_SESSION['nama'] ?? '';
$inisial = strtoupper(substr($nama, 0, 1) ?: 'A');

$menus = [
    'admin' => [
        ['key'=>'dashboard','label'=>'Dashboard','icon'=>'🏠','href'=>'index.php'],
        ['key'=>'users','label'=>'Data Pengguna','icon'=>'👥','href'=>'users.php'],
        ['key'=>'sampah','label'=>'Data Sampah','icon'=>'📦','href'=>'sampah.php'],
        ['key'=>'transaksi','label'=>'Transaksi','icon'=>'💰','href'=>'transaksi.php'],
        ['key'=>'pengepul','label'=>'Pengepul','icon'=>'🚚','href'=>'pengepul.php'],
    ],
    'nasabah' => [
        ['key'=>'dashboard','label'=>'Dashboard','icon'=>'🏠','href'=>'index.php'],
        ['key'=>'setor','label'=>'Setor Sampah','icon'=>'♻️','href'=>'setor.php'],
        ['key'=>'riwayat','label'=>'Riwayat Setoran','icon'=>'📜','href'=>'riwayat.php'],
        ['key'=>'tarik','label'=>'Tarik Saldo','icon'=>'💸','href'=>'tarik.php'],
        ['key'=>'profil','label'=>'Profil','icon'=>'👤','href'=>'profil.php'],
    ],
    'user' => [
        ['key'=>'dashboard','label'=>'Dashboard','icon'=>'🏠','href'=>'index.php'],
        ['key'=>'sampah','label'=>'Info Sampah','icon'=>'📦','href'=>'sampah.php'],
        ['key'=>'riwayat','label'=>'Riwayat','icon'=>'📜','href'=>'riwayat.php'],
        ['key'=>'profil','label'=>'Profil','icon'=>'👤','href'=>'profil.php'],
    ],
    'pengepul' => [
        ['key'=>'dashboard','label'=>'Dashboard','icon'=>'🏠','href'=>'index.php'],
        ['key'=>'sampah','label'=>'Data Sampah','icon'=>'📦','href'=>'sampah.php'],
        ['key'=>'transaksi','label'=>'Transaksi Pengepul','icon'=>'💰','href'=>'transaksi.php'],
        ['key'=>'riwayat','label'=>'Riwayat Transaksi','icon'=>'📜','href'=>'riwayat.php'],
        ['key'=>'profil','label'=>'Profil','icon'=>'👤','href'=>'profil.php'],
    ],
];
$roleLabel = ['admin'=>'Administrator','nasabah'=>'Nasabah','user'=>'User','pengepul'=>'Pengepul'][$role] ?? 'Pengguna';
$navItems = $menus[$role] ?? [];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($page_title ?? 'Bank Sampah AUL') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
</head>
<body>
<div class="app">
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <div class="logo-badge">♻️</div>
      <div>
        <div class="brand-name">Bank Sampah AUL</div>
        <div class="brand-tag">Kelola Sampah, Raih Masa Depan</div>
      </div>
    </div>
    <div class="side-user">
      <div class="avatar"><?= htmlspecialchars($inisial) ?></div>
      <div>
        <div class="u-name"><?= htmlspecialchars($nama) ?></div>
        <div class="u-role"><?= htmlspecialchars($roleLabel) ?></div>
      </div>
    </div>
    <ul class="side-nav">
      <?php foreach ($navItems as $m): ?>
        <li><a href="<?= $m['href'] ?>" class="<?= ($active ?? '') === $m['key'] ? 'active' : '' ?>"><span class="ic"><?= $m['icon'] ?></span><?= $m['label'] ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="side-foot">
      <span class="emoji">🌱</span>
      <p>Lingkungan bersih, hidup lebih sehat</p>
    </div>
    <a href="<?= $base ?>logout.php" class="side-logout"><span class="ic">🚪</span>Logout</a>
  </aside>

  <div class="main-col">
    <header class="topbar">
      <button class="toggle-btn" id="toggleSidebar">☰</button>
      <div class="search"><span>🔍</span><input type="text" placeholder="Cari sesuatu..." disabled></div>
      <div class="topbar-right">
        <div class="bell">🔔<span class="dot"></span></div>
        <div class="topbar-user">
          <div class="avatar" style="width:34px;height:34px;font-size:.85rem;flex:0 0 34px"><?= htmlspecialchars($inisial) ?></div>
          <div>
            <div class="u-name"><?= htmlspecialchars($nama) ?></div>
            <div class="u-role"><?= htmlspecialchars($roleLabel) ?></div>
          </div>
        </div>
      </div>
    </header>
    <main class="content">
