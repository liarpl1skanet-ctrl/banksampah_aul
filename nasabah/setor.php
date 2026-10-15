<?php
require "../auth.php"; wajib_role("nasabah"); require "../config.php";
$uid = (int)$_SESSION['id_user'];
$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sampah = (int)($_POST['id_sampah'] ?? 0);
    $berat = (float)($_POST['berat'] ?? 0);
    $s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM sampah WHERE id_sampah=$id_sampah"));
    if ($s && $berat > 0) {
        $total = $berat * (float)$s['harga_per_kg'];
        $stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi (id_user,id_sampah,tipe,berat_kg,total_rp,status,tanggal) VALUES (?,?,'setor',?,?,'menunggu',CURDATE())");
        mysqli_stmt_bind_param($stmt, "iidd", $uid, $id_sampah, $berat, $total);
        mysqli_stmt_execute($stmt);
        $pesan = "success";
    } else {
        $pesan = "error";
    }
}

$sampah = mysqli_query($koneksi, "SELECT * FROM sampah ORDER BY jenis_sampah ASC");
$page_title = "Setor Sampah - Nasabah"; $active = "setor"; $base = "../";
require "../includes/header.php";
?>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card-soft">
      <div class="card-title"><h3>♻️ Setor Sampah</h3></div>
      <?php if ($pesan === 'success'): ?>
        <div class="alert alert-success">Setoran berhasil diajukan! Menunggu verifikasi admin. ✨</div>
      <?php elseif ($pesan === 'error'): ?>
        <div class="alert alert-danger">Data tidak valid, coba lagi.</div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label fw-semibold">Jenis Sampah</label>
          <select name="id_sampah" class="form-select" required>
            <option value="">-- Pilih jenis sampah --</option>
            <?php mysqli_data_seek($sampah, 0); while ($r = mysqli_fetch_assoc($sampah)): ?>
              <option value="<?= $r['id_sampah'] ?>">♻️ <?= htmlspecialchars($r['jenis_sampah']) ?> — <?= rupiah($r['harga_per_kg']) ?>/kg</option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Berat (kg)</label>
          <input type="number" step="0.1" min="0.1" name="berat" class="form-control" placeholder="contoh: 3.5" required>
        </div>
        <button class="btn btn-pink w-100">Ajukan Setoran</button>
      </form>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card-soft">
      <div class="card-title"><h3>💡 Harga Sampah Saat Ini</h3></div>
      <table class="table">
        <thead><tr><th>Jenis</th><th>Harga/kg</th></tr></thead>
        <tbody>
        <?php mysqli_data_seek($sampah, 0); while ($r = mysqli_fetch_assoc($sampah)): ?>
          <tr><td>♻️ <?= htmlspecialchars($r['jenis_sampah']) ?></td><td><?= rupiah($r['harga_per_kg']) ?></td></tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require "../includes/footer.php"; ?>
