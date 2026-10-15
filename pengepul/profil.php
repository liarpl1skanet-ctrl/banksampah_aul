<?php
require "../auth.php"; wajib_role("pengepul"); require "../config.php";
$uid = (int)$_SESSION['id_user'];
$u = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM user WHERE id_user=$uid"));
$mitra = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengepul ORDER BY id_pengepul ASC LIMIT 1"));
$page_title = "Profil - Pengepul"; $active = "profil"; $base = "../";
require "../includes/header.php";
?>
<div class="row g-4">
  <div class="col-lg-6">
    <div class="card-soft text-center">
      <div class="avatar mx-auto mb-3" style="width:76px;height:76px;font-size:1.8rem"><?= strtoupper(substr($u['nama'],0,1)) ?></div>
      <h3 class="mb-0"><?= htmlspecialchars($u['nama']) ?></h3>
      <p class="text-muted mb-3"><span class="badge-role"><?= ucfirst($u['role']) ?></span></p>
      <table class="table text-start">
        <tbody>
          <tr><td class="text-muted">Username</td><td class="fw-semibold"><?= htmlspecialchars($u['username']) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($mitra): ?>
  <div class="col-lg-6">
    <div class="card-soft">
      <div class="card-title"><h3>🚚 Data Mitra Pengepul</h3></div>
      <table class="table">
        <tbody>
          <tr><td class="text-muted">Nama Mitra</td><td class="fw-semibold"><?= htmlspecialchars($mitra['nama_pengepul']) ?></td></tr>
          <tr><td class="text-muted">Alamat</td><td class="fw-semibold"><?= htmlspecialchars($mitra['alamat']) ?></td></tr>
          <tr><td class="text-muted">No. HP</td><td class="fw-semibold"><?= htmlspecialchars($mitra['no_hp']) ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require "../includes/footer.php"; ?>
