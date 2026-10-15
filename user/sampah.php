<?php
require "../auth.php"; wajib_role("user"); require "../config.php";
$data = mysqli_query($koneksi, "SELECT * FROM sampah ORDER BY id_sampah DESC");
$page_title = "Info Sampah - User"; $active = "sampah"; $base = "../";
require "../includes/header.php";
?>
<div class="card-soft">
  <div class="card-title"><h3>📦 Info Jenis Sampah</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>ID</th><th>Jenis Sampah</th><th>Harga/Kg</th><th>Stok (kg)</th></tr></thead>
      <tbody>
      <?php if (mysqli_num_rows($data) === 0): ?>
        <tr><td colspan="4" class="empty">Belum ada data sampah.</td></tr>
      <?php else: while ($r = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $r['id_sampah'] ?></td>
          <td>♻️ <?= htmlspecialchars($r['jenis_sampah']) ?></td>
          <td><?= rupiah($r['harga_per_kg']) ?></td>
          <td><?= number_format((float)$r['stok_kg'],1) ?></td>
        </tr>
      <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
