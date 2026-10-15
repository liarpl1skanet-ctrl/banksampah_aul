<?php
require "../auth.php";
wajib_role("admin");
require "../config.php";

$totalUser = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM user WHERE role<>'admin'"))['t'] ?? 0;
$totalTransaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM transaksi"))['t'] ?? 0;
$totalMenunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM transaksi WHERE status='menunggu'"))['t'] ?? 0;
$totalPengepul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM pengepul"))['t'] ?? 0;

// grafik 6 bulan terakhir
$bulanLabel = []; $bulanData = [];
for ($i = 5; $i >= 0; $i--) {
    $label = date('M', strtotime("-$i months"));
    $ym = date('Y-m', strtotime("-$i months"));
    $c = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) t FROM transaksi WHERE DATE_FORMAT(tanggal,'%Y-%m')='$ym'"))['t'] ?? 0;
    $bulanLabel[] = $label; $bulanData[] = (int)$c;
}

// status donut
$statusRows = mysqli_query($koneksi, "SELECT status, COUNT(*) t FROM transaksi GROUP BY status");
$statusMap = ['disetujui'=>0,'menunggu'=>0,'ditolak'=>0];
while ($r = mysqli_fetch_assoc($statusRows)) { $statusMap[$r['status']] = (int)$r['t']; }

$recent = mysqli_query($koneksi, "SELECT t.*, u.nama AS nama_user, s.jenis_sampah FROM transaksi t
    JOIN user u ON t.id_user=u.id_user JOIN sampah s ON t.id_sampah=s.id_sampah
    ORDER BY t.id_transaksi DESC LIMIT 6");

$page_title = "Dashboard Admin - Bank Sampah AUL";
$active = "dashboard";
$base = "../";
require "../includes/header.php";
?>
<section class="hero">
  <div>
    <h1>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>! 👑</h1>
    <p>Kelola seluruh aktivitas Bank Sampah AUL dengan mudah.</p>
  </div>
  <div class="hero-side">📅 <?= date('l, d F Y') ?></div>
</section>

<div class="grid grid-stats mb-4">
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-pink">👤</div><div class="stat-label">Total Pengguna</div></div>
    <div class="stat-number"><?= $totalUser ?></div>
    <div class="stat-trend trend-up">Nasabah &amp; pengguna terdaftar</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-purple">💰</div><div class="stat-label">Total Transaksi</div></div>
    <div class="stat-number"><?= $totalTransaksi ?></div>
    <div class="stat-trend trend-up">Sepanjang waktu</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-orange">⏳</div><div class="stat-label">Transaksi Menunggu</div></div>
    <div class="stat-number"><?= $totalMenunggu ?></div>
    <div class="stat-trend trend-warn">Perlu verifikasi</div>
  </div>
  <div class="stat-card">
    <div class="stat-top"><div class="stat-icon icon-blue">🚚</div><div class="stat-label">Total Pengepul</div></div>
    <div class="stat-number"><?= $totalPengepul ?></div>
    <div class="stat-trend trend-flat">Mitra aktif</div>
  </div>
</div>

<div class="grid grid-2 mb-4">
  <div class="card-soft">
    <div class="card-title"><h3>📊 Grafik Transaksi Bulanan</h3></div>
    <canvas id="chartBulanan" height="110"></canvas>
  </div>
  <div class="card-soft">
    <div class="card-title"><h3>🍩 Status Transaksi</h3></div>
    <canvas id="chartStatus" height="180"></canvas>
  </div>
</div>

<div class="card-soft">
  <div class="card-title"><h3>🧾 Transaksi Terbaru</h3></div>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>No</th><th>Nama</th><th>Jenis Sampah</th><th>Berat (kg)</th><th>Total (Rp)</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($recent) === 0): ?>
          <tr><td colspan="8" class="empty">Belum ada transaksi.</td></tr>
        <?php else: $no=1; while ($r = mysqli_fetch_assoc($recent)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($r['nama_user']) ?></td>
          <td><?= htmlspecialchars($r['jenis_sampah']) ?></td>
          <td><?= number_format((float)$r['berat_kg'],1) ?></td>
          <td><?= rupiah($r['total_rp']) ?></td>
          <td><span class="status-pill status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
          <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
          <td><a href="transaksi.php" class="btn-mini">Detail</a></td>
        </tr>
        <?php endwhile; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$extra_script = "<script>
new Chart(document.getElementById('chartBulanan'), {
  type: 'bar',
  data: {
    labels: " . json_encode($bulanLabel) . ",
    datasets: [{ label: 'Transaksi', data: " . json_encode($bulanData) . ", backgroundColor: '#ff4f93', borderRadius: 8, maxBarThickness: 36 }]
  },
  options: { plugins:{legend:{display:false}}, scales:{ y:{ beginAtZero:true, ticks:{precision:0} } } }
});
new Chart(document.getElementById('chartStatus'), {
  type: 'doughnut',
  data: {
    labels: ['Disetujui','Menunggu','Ditolak'],
    datasets: [{ data: [" . $statusMap['disetujui'] . "," . $statusMap['menunggu'] . "," . $statusMap['ditolak'] . "], backgroundColor: ['#22c55e','#ff9f43','#f14668'], borderWidth:0 }]
  },
  options: { plugins:{legend:{position:'bottom'}}, cutout:'65%' }
});
</script>";
require "../includes/footer.php";
?>
