<?php
require "../auth.php"; wajib_role("admin"); require "../config.php";

// verifikasi cepat: setujui / tolak transaksi
if (isset($_GET['verifikasi'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $trx = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_transaksi=$id"));
    if ($trx && $trx['status'] === 'menunggu') {
        $aksi = $_GET['verifikasi'] === 'setuju' ? 'disetujui' : 'ditolak';
        mysqli_query($koneksi, "UPDATE transaksi SET status='$aksi' WHERE id_transaksi=$id");
        // setoran yang disetujui menambah saldo nasabah
        if ($aksi === 'disetujui' && $trx['tipe'] === 'setor') {
            $idu = (int)$trx['id_user'];
            $nominal = (float)$trx['total_rp'];
            mysqli_query($koneksi, "UPDATE user SET saldo = saldo + $nominal WHERE id_user=$idu");
        }
    }
    header("Location: transaksi.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT t.*,u.nama AS nama_user,s.jenis_sampah,p.nama_pengepul
    FROM transaksi t JOIN user u ON t.id_user=u.id_user JOIN sampah s ON t.id_sampah=s.id_sampah
    LEFT JOIN pengepul p ON t.id_pengepul=p.id_pengepul ORDER BY t.id_transaksi DESC");
$page_title = "Transaksi - Admin"; $active = "transaksi"; $base = "../";
require "../includes/header.php";
?>
<div class="card-soft">
  <div class="card-title"><h3>💰 Semua Transaksi</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>ID</th><th>Nasabah</th><th>Sampah</th><th>Pengepul</th><th>Berat</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php if (mysqli_num_rows($data) === 0): ?>
        <tr><td colspan="8" class="empty">Belum ada transaksi.</td></tr>
      <?php else: while ($r = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $r['id_transaksi'] ?></td>
          <td><?= htmlspecialchars($r['nama_user']) ?></td>
          <td><?= htmlspecialchars($r['jenis_sampah']) ?></td>
          <td><?= htmlspecialchars($r['nama_pengepul'] ?? '-') ?></td>
          <td><?= number_format((float)$r['berat_kg'],1) ?> kg</td>
          <td><?= rupiah($r['total_rp']) ?></td>
          <td><span class="status-pill status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
          <td>
            <?php if ($r['status'] === 'menunggu'): ?>
              <a href="?verifikasi=setuju&id=<?= $r['id_transaksi'] ?>" class="btn-mini" style="background:var(--green)">Setujui</a>
              <a href="?verifikasi=tolak&id=<?= $r['id_transaksi'] ?>" class="btn-mini" style="background:var(--red)">Tolak</a>
            <?php else: ?>
              <span class="text-muted" style="font-size:.78rem">—</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
