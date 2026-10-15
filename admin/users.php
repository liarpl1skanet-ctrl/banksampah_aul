<?php
require "../auth.php"; wajib_role("admin"); require "../config.php";
$data = mysqli_query($koneksi, "SELECT id_user,nama,username,role,saldo FROM user ORDER BY id_user DESC");
$page_title = "Data Pengguna - Admin"; $active = "users"; $base = "../";
require "../includes/header.php";
?>
<div class="card-soft">
  <div class="card-title"><h3>👥 Data Pengguna</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>ID</th><th>Nama</th><th>Username</th><th>Role</th><th>Saldo</th></tr></thead>
      <tbody>
      <?php if (mysqli_num_rows($data) === 0): ?>
        <tr><td colspan="5" class="empty">Belum ada pengguna.</td></tr>
      <?php else: while ($r = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $r['id_user'] ?></td>
          <td><?= htmlspecialchars($r['nama']) ?></td>
          <td><?= htmlspecialchars($r['username']) ?></td>
          <td><span class="badge-role"><?= ucfirst($r['role']) ?></span></td>
          <td><?= rupiah($r['saldo']) ?></td>
        </tr>
      <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
