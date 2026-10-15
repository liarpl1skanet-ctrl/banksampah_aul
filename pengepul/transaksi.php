<?php
require "../auth.php"; wajib_role("pengepul"); require "../config.php";
$data = mysqli_query($koneksi, "SELECT t.*,u.nama AS nama_user,s.jenis_sampah,p.nama_pengepul
    FROM transaksi t JOIN user u ON t.id_user=u.id_user JOIN sampah s ON t.id_sampah=s.id_sampah
    LEFT JOIN pengepul p ON t.id_pengepul=p.id_pengepul ORDER BY t.id_transaksi DESC");
$page_title = "Transaksi Pengepul"; $active = "transaksi"; $base = "../";
require "../includes/header.php";
?>
<div class="card-soft">
  <div class="card-title"><h3>💰 Semua Transaksi</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>ID</th><th>Nasabah</th><th>Sampah</th><th>Pengepul</th><th>Berat</th><th>Total</th><th>Status</th></tr></thead>
      <tbody>
      <?php if (mysqli_num_rows($data) === 0): ?>
        <tr><td colspan="7" class="empty">Belum ada transaksi.</td></tr>
      <?php else: while ($r = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $r['id_transaksi'] ?></td>
          <td><?= htmlspecialchars($r['nama_user']) ?></td>
          <td><?= htmlspecialchars($r['jenis_sampah']) ?></td>
          <td><?= htmlspecialchars($r['nama_pengepul'] ?? '-') ?></td>
          <td><?= number_format((float)$r['berat_kg'],1) ?> kg</td>
          <td><?= rupiah($r['total_rp']) ?></td>
          <td><span class="status-pill status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
        </tr>
      <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
