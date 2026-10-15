<?php
require "../auth.php"; wajib_role("admin"); require "../config.php";
$data = mysqli_query($koneksi, "SELECT * FROM pengepul ORDER BY id_pengepul DESC");
$page_title = "Data Pengepul - Admin"; $active = "pengepul"; $base = "../";
require "../includes/header.php";
?>
<div class="card-soft">
  <div class="card-title"><h3>🚚 Data Pengepul</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>ID</th><th>Nama</th><th>Username</th><th>Alamat</th><th>No. HP</th></tr></thead>
      <tbody>
      <?php if (mysqli_num_rows($data) === 0): ?>
        <tr><td colspan="5" class="empty">Belum ada data pengepul.</td></tr>
      <?php else: while ($r = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $r['id_pengepul'] ?></td>
          <td><?= htmlspecialchars($r['nama_pengepul']) ?></td>
          <td><?= htmlspecialchars($r['username']) ?></td>
          <td><?= htmlspecialchars($r['alamat']) ?></td>
          <td><?= htmlspecialchars($r['no_hp']) ?></td>
        </tr>
      <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
