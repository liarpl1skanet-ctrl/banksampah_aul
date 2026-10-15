<?php
require "../auth.php"; wajib_role("user"); require "../config.php";
$uid = (int)$_SESSION['id_user'];
$data = mysqli_query($koneksi, "SELECT t.*, s.jenis_sampah FROM transaksi t JOIN sampah s ON t.id_sampah=s.id_sampah
    WHERE t.id_user=$uid ORDER BY t.id_transaksi DESC");
$page_title = "Riwayat - User"; $active = "riwayat"; $base = "../";
require "../includes/header.php";
?>
<div class="card-soft">
  <div class="card-title"><h3>📜 Riwayat Transaksi</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>Tanggal</th><th>Jenis Sampah</th><th>Berat (kg)</th><th>Total (Rp)</th><th>Status</th></tr></thead>
      <tbody>
      <?php if (mysqli_num_rows($data) === 0): ?>
        <tr><td colspan="5" class="empty">Belum ada riwayat transaksi.</td></tr>
      <?php else: while ($r = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
          <td><?= htmlspecialchars($r['jenis_sampah']) ?></td>
          <td><?= number_format((float)$r['berat_kg'],1) ?></td>
          <td><?= rupiah($r['total_rp']) ?></td>
          <td><span class="status-pill status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
        </tr>
      <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
