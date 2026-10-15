<?php
require "../auth.php";
wajib_role("user");
require "../config.php";

$uid = (int)$_SESSION['id_user'];
$saldo = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT saldo FROM user WHERE id_user=$uid"))['saldo'] ?? 0;
$totalSetor = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM transaksi WHERE id_user=$uid AND tipe='setor'"))['t'] ?? 0;
$totalKg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(berat_kg),0) t FROM transaksi WHERE id_user=$uid AND tipe='setor'"))['t'] ?? 0;

$bulanLabel = []; $bulanData = [];
for ($i = 5; $i >= 0; $i--) {
    $label = date('M', strtotime("-$i months"));
    $ym = date('Y-m', strtotime("-$i months"));
    $c = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(berat_kg),0) t FROM transaksi WHERE id_user=$uid AND tipe='setor' AND DATE_FORMAT(tanggal,'%Y-%m')='$ym'"))['t'] ?? 0;
    $bulanLabel[] = $label; $bulanData[] = (float)$c;
}

$sampah = mysqli_query($koneksi, "SELECT * FROM sampah ORDER BY harga_per_kg DESC");

$page_title = "Dashboard User - Bank Sampah AUL";
$active = "dashboard";
$base = "../";
require "../includes/header.php";
?>
<div class="grid grid-2 mb-4">
  <div class="card-soft" style="background:linear-gradient(120deg,#fff,var(--pink-soft));display:flex;align-items:center;gap:18px;flex-wrap:wrap">
    <div style="flex:1;min-width:220px">
      <h2 style="margin:0 0 6px">Halo, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</h2>
      <p style="margin:0 0 14px;color:var(--ink-soft)">Yuk, mulai dari hal kecil untuk dampak besar! Setiap sampah yang kamu pilah berarti masa depan yang lebih hijau. 🌍</p>
      <a href="../nasabah/setor.php" class="btn btn-pink">Setor Sekarang →</a>
    </div>
    <div style="font-size:4rem">🌍♻️</div>
  </div>
  <div class="grid" style="grid-template-columns:1fr;gap:14px">
    <div class="stat-card"><div class="stat-top"><div class="stat-icon icon-pink">💰</div><div class="stat-label">Saldo</div></div><div class="stat-number"><?= rupiah($saldo) ?></div></div>
    <div class="stat-card"><div class="stat-top"><div class="stat-icon icon-teal">♻️</div><div class="stat-label">Total Setoran</div></div><div class="stat-number"><?= $totalSetor ?> kali</div></div>
    <div class="stat-card"><div class="stat-top"><div class="stat-icon icon-blue">📦</div><div class="stat-label">Total Sampah</div></div><div class="stat-number"><?= number_format((float)$totalKg,1) ?> kg</div></div>
  </div>
</div>

<div class="grid grid-2 mb-4">
  <div class="card-soft">
    <div class="card-title" style="justify-content:space-between;display:flex"><h3>📦 Informasi Jenis Sampah</h3><a href="sampah.php" class="btn-mini" style="background:var(--pink-soft);color:var(--pink-dark)">Lihat Semua</a></div>
    <div class="table-responsive">
      <table class="table">
        <thead><tr><th>Jenis Sampah</th><th>Harga /kg</th><th>Keterangan</th></tr></thead>
        <tbody>
        <?php if (mysqli_num_rows($sampah) === 0): ?>
          <tr><td colspan="3" class="empty">Belum ada data sampah.</td></tr>
        <?php else: while ($r = mysqli_fetch_assoc($sampah)): ?>
          <tr>
            <td>♻️ <?= htmlspecialchars($r['jenis_sampah']) ?></td>
            <td><?= rupiah($r['harga_per_kg']) ?></td>
            <td class="text-muted">Stok <?= number_format((float)$r['stok_kg'],1) ?> kg</td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-soft">
    <div class="card-title"><h3>📈 Grafik Setoran Sampah (6 Bulan Terakhir)</h3></div>
    <canvas id="chartSetoran" height="150"></canvas>
  </div>
</div>

<div class="card-soft">
  <div class="card-title"><h3>🧭 Panduan Setor Sampah</h3></div>
  <div class="row g-3">
    <div class="col-md-4"><div class="stat-card"><div class="stat-top"><div class="stat-icon icon-pink">1️⃣</div><div class="stat-label">Pilih Sampah</div></div><p class="mb-0 text-muted" style="font-size:.85rem">Pilah dan siapkan sampah yang ingin disetor.</p></div></div>
    <div class="col-md-4"><div class="stat-card"><div class="stat-top"><div class="stat-icon icon-purple">2️⃣</div><div class="stat-label">Timbang di Lokasi/Pos</div></div><p class="mb-0 text-muted" style="font-size:.85rem">Bawa ke pos setor Bank Sampah AUL terdekat.</p></div></div>
    <div class="col-md-4"><div class="stat-card"><div class="stat-top"><div class="stat-icon icon-teal">3️⃣</div><div class="stat-label">Dapatkan Saldo</div></div><p class="mb-0 text-muted" style="font-size:.85rem">Saldo otomatis masuk setelah diverifikasi.</p></div></div>
  </div>
</div>

<?php
$extra_script = "<script>
new Chart(document.getElementById('chartSetoran'), {
  type: 'bar',
  data: {
    labels: " . json_encode($bulanLabel) . ",
    datasets: [{ label: 'Berat (kg)', data: " . json_encode($bulanData) . ", backgroundColor:'#ff4f93', borderRadius:8, maxBarThickness:34 }]
  },
  options: { plugins:{legend:{display:false}}, scales:{ y:{ beginAtZero:true } } }
});
</script>";
require "../includes/footer.php";
?>
