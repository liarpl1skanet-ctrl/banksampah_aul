<?php
require "../auth.php";
wajib_role("nasabah");
require "../config.php";

$uid = (int)$_SESSION['id_user'];

$saldo = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT saldo FROM user WHERE id_user=$uid"))['saldo'] ?? 0;
$totalSetor = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM transaksi WHERE id_user=$uid AND tipe='setor'"))['t'] ?? 0;
$totalKg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(berat_kg),0) t FROM transaksi WHERE id_user=$uid AND tipe='setor'"))['t'] ?? 0;
$menunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM transaksi WHERE id_user=$uid AND status='menunggu'"))['t'] ?? 0;

$bulanLabel = []; $bulanData = [];
for ($i = 5; $i >= 0; $i--) {
    $label = date('M', strtotime("-$i months"));
    $ym = date('Y-m', strtotime("-$i months"));
    $c = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(berat_kg),0) t FROM transaksi WHERE id_user=$uid AND tipe='setor' AND DATE_FORMAT(tanggal,'%Y-%m')='$ym'"))['t'] ?? 0;
    $bulanLabel[] = $label; $bulanData[] = (float)$c;
}

$riwayat = mysqli_query($koneksi, "SELECT t.*, s.jenis_sampah FROM transaksi t JOIN sampah s ON t.id_sampah=s.id_sampah
    WHERE t.id_user=$uid AND t.tipe='setor' ORDER BY t.id_transaksi DESC LIMIT 5");

$page_title = "Dashboard Nasabah - Bank Sampah AUL";
$active = "dashboard";
$base = "../";
require "../includes/header.php";
?>
<section class="hero">
  <div>
    <h1>Halo, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</h1>
    <p>Selamat datang kembali di Bank Sampah AUL.</p>
  </div>
  <div class="hero-side">🌱 Setiap sampah punya nilai — jaga lingkungan bersama kami</div>
</section>

<div class="grid grid-stats mb-4">
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-pink">💰</div><div class="stat-label">Saldo Saya</div></div>
    <div class="stat-number"><?= rupiah($saldo) ?></div>
    <div class="stat-trend trend-up"><a href="riwayat.php" style="color:inherit">Lihat detail →</a></div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-teal">♻️</div><div class="stat-label">Total Setoran</div></div>
    <div class="stat-number"><?= $totalSetor ?> kali</div>
    <div class="stat-trend trend-flat">Sepanjang waktu</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-blue">📦</div><div class="stat-label">Total Sampah</div></div>
    <div class="stat-number"><?= number_format((float)$totalKg,1) ?> kg</div>
    <div class="stat-trend trend-flat">Terkumpul</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-orange">⏳</div><div class="stat-label">Status Transaksi</div></div>
    <div class="stat-number"><?= $menunggu ?> menunggu</div>
    <div class="stat-trend trend-warn"><a href="riwayat.php" style="color:inherit">Lihat semua →</a></div>
  </div>
</div>

<div class="grid grid-2 mb-4">
  <div class="card-soft">
    <div class="card-title"><h3>📈 Grafik Setoran Sampah (6 Bulan Terakhir)</h3></div>
    <canvas id="chartSetoran" height="110"></canvas>
  </div>
  <div>
    <a href="setor.php" class="action-card action-pink">
      <div class="a-ic">♻️</div>
      <div>Setor Sampah<small>Mulai setor sampah sekarang</small></div>
    </a>
    <a href="tarik.php" class="action-card action-purple">
      <div class="a-ic">💸</div>
      <div>Tarik Saldo<small>Ajukan penarikan saldo</small></div>
    </a>
    <div class="eco-quote">"Jangan berhenti jadi baik, meski kecil. Karena langkah kecilmu menyelamatkan bumi kita." 🌍</div>
  </div>
</div>

<div class="card-soft">
  <div class="card-title"><h3>🧾 Riwayat Setoran Terbaru</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>Tanggal</th><th>Jenis Sampah</th><th>Berat (kg)</th><th>Total (Rp)</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($riwayat) === 0): ?>
          <tr><td colspan="6" class="empty">Belum ada setoran. Yuk mulai setor sampah pertamamu!</td></tr>
        <?php else: while ($r = mysqli_fetch_assoc($riwayat)): ?>
        <tr>
          <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
          <td><?= htmlspecialchars($r['jenis_sampah']) ?></td>
          <td><?= number_format((float)$r['berat_kg'],1) ?></td>
          <td><?= rupiah($r['total_rp']) ?></td>
          <td><span class="status-pill status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
          <td><a href="riwayat.php" class="btn-mini">Detail</a></td>
        </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$extra_script = "<script>
new Chart(document.getElementById('chartSetoran'), {
  type: 'line',
  data: {
    labels: " . json_encode($bulanLabel) . ",
    datasets: [{ label: 'Berat (kg)', data: " . json_encode($bulanData) . ", borderColor:'#ff4f93', backgroundColor:'rgba(255,79,147,.12)', tension:.4, fill:true, pointBackgroundColor:'#ff4f93' }]
  },
  options: { plugins:{legend:{display:false}}, scales:{ y:{ beginAtZero:true } } }
});
</script>";
require "../includes/footer.php";
?>
