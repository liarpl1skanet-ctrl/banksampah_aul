<?php
require "../auth.php"; wajib_role("nasabah"); require "../config.php";
$uid = (int)$_SESSION['id_user'];
$saldo = (float)(mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT saldo FROM user WHERE id_user=$uid"))['saldo'] ?? 0);
$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = (float)($_POST['jumlah'] ?? 0);
    if ($jumlah > 0 && $jumlah <= $saldo) {
        $sTipe = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_sampah FROM sampah LIMIT 1"));
        $idSampahDummy = $sTipe['id_sampah'] ?? 1;
        $stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi (id_user,id_sampah,tipe,berat_kg,total_rp,status,tanggal) VALUES (?,?,'tarik',0,?,'disetujui',CURDATE())");
        mysqli_stmt_bind_param($stmt, "iid", $uid, $idSampahDummy, $jumlah);
        mysqli_stmt_execute($stmt);
        mysqli_query($koneksi, "UPDATE user SET saldo = saldo - $jumlah WHERE id_user=$uid");
        $_SESSION['saldo'] = $saldo - $jumlah;
        $saldo -= $jumlah;
        $pesan = "success";
    } else {
        $pesan = "error";
    }
}

$page_title = "Tarik Saldo - Nasabah"; $active = "tarik"; $base = "../";
require "../includes/header.php";
?>
<div class="row g-4">
  <div class="col-lg-6">
    <div class="card-soft">
      <div class="card-title"><h3>💸 Tarik Saldo</h3></div>
      <?php if ($pesan === 'success'): ?>
        <div class="alert alert-success">Penarikan berhasil diproses! ✨</div>
      <?php elseif ($pesan === 'error'): ?>
        <div class="alert alert-danger">Jumlah tidak valid atau melebihi saldo.</div>
      <?php endif; ?>
      <div class="stat-card mb-3">
        <div class="stat-top"><div class="stat-icon icon-pink">💰</div><div class="stat-label">Saldo Tersedia</div></div>
        <div class="stat-number"><?= rupiah($saldo) ?></div>
      </div>
      <form method="post">
        <div class="mb-3">
          <label class="form-label fw-semibold">Jumlah Penarikan (Rp)</label>
          <input type="number" step="1" min="1" max="<?= (int)$saldo ?>" name="jumlah" class="form-control" placeholder="contoh: 20000" required>
        </div>
        <button class="btn btn-pink w-100">Ajukan Penarikan</button>
      </form>
    </div>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
