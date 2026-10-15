<?php
require "../auth.php"; wajib_role("pengepul"); require "../config.php";
$uid=(int)$_SESSION['id_user'];
function angka($koneksi,$sql,$field='total'){ $q=mysqli_query($koneksi,$sql); if(!$q) return 0; $r=mysqli_fetch_assoc($q); return $r[$field] ?? 0; }
$totalPembelian=angka($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE id_user=$uid AND tipe='beli'");
$totalDisetujui=angka($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE id_user=$uid AND tipe='beli' AND status='disetujui'");
$totalKg=angka($koneksi,"SELECT COALESCE(SUM(berat_kg),0) AS total FROM transaksi WHERE id_user=$uid AND tipe='beli' AND status='disetujui'");
$totalRp=angka($koneksi,"SELECT COALESCE(SUM(total_rp),0) AS total FROM transaksi WHERE id_user=$uid AND tipe='beli' AND status='disetujui'");
$menunggu=angka($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE id_user=$uid AND tipe='beli' AND status='menunggu'");
$riwayat=mysqli_query($koneksi,"SELECT t.*,s.jenis_sampah FROM transaksi t JOIN sampah s ON t.id_sampah=s.id_sampah WHERE t.id_user=$uid AND t.tipe='beli' ORDER BY t.id_transaksi DESC LIMIT 5");
$page_title="Dashboard Pengepul - Bank Sampah AUL"; $active="dashboard"; $base="../"; require "../includes/header.php";
?>
<section class="hero"><div><h1>Halo, <?=htmlspecialchars($_SESSION['nama'])?>! 👋</h1><p>Selamat datang di ruang pembelian sampah Bank Sampah AUL.</p></div><div class="hero-side">🛒 Belanja sampah dengan mudah</div></section>
<div class="grid grid-stats mb-4">
<div class="stat-card"><div class="stat-top"><div class="stat-icon icon-pink">🛒</div><div class="stat-label">Total Pengajuan</div></div><div class="stat-number"><?=number_format((int)$totalPembelian)?> kali</div><div class="stat-trend trend-flat">Semua pembelian</div></div>
<div class="stat-card"><div class="stat-top"><div class="stat-icon icon-teal">♻️</div><div class="stat-label">Sampah Disetujui</div></div><div class="stat-number"><?=number_format((float)$totalKg,1,',','.')?> kg</div><div class="stat-trend trend-flat"><?=$totalDisetujui?> transaksi disetujui</div></div>
<div class="stat-card"><div class="stat-top"><div class="stat-icon icon-blue">💵</div><div class="stat-label">Total Belanja Disetujui</div></div><div class="stat-number"><?=rupiah($totalRp)?></div><div class="stat-trend trend-flat">Pembelian selesai</div></div>
<div class="stat-card"><div class="stat-top"><div class="stat-icon icon-orange">⏳</div><div class="stat-label">Menunggu</div></div><div class="stat-number"><?=$menunggu?> transaksi</div><div class="stat-trend trend-warn"><a href="transaksi.php" style="color:inherit">Lihat status →</a></div></div>
</div>
<div class="grid grid-2 mb-4"><div class="card-soft"><div class="card-title"><h3>🛍️ Mulai Belanja</h3></div><p class="text-muted">Pilih sampah yang tersedia, tentukan jumlah kilogram, lalu kirim permintaan pembelian kepada Admin.</p><a href="beli.php" class="btn btn-pink w-100">🛒 Beli Sampah Sekarang</a></div><div class="card-soft"><div class="card-title"><h3>🌱 Panduan Pengepul</h3></div><p class="mb-2">1. Pilih jenis sampah.</p><p class="mb-2">2. Masukkan berat pembelian.</p><p class="mb-0">3. Tunggu persetujuan Admin.</p></div></div>
<div class="card-soft"><div class="card-title"><h3>🧾 Riwayat Pembelian Terbaru</h3><a href="transaksi.php" class="btn-mini">Lihat semua</a></div><div class="table-responsive"><table class="table"><thead><tr><th>Tanggal</th><th>Jenis Sampah</th><th>Berat</th><th>Total</th><th>Status</th></tr></thead><tbody>
<?php if(!$riwayat || mysqli_num_rows($riwayat)===0): ?><tr><td colspan="5" class="empty">Belum ada pembelian. Yuk mulai belanja sampah! 🛒</td></tr><?php else: while($r=mysqli_fetch_assoc($riwayat)): ?><tr><td><?=date('d M Y',strtotime($r['tanggal']))?></td><td><?=htmlspecialchars($r['jenis_sampah'])?></td><td><?=number_format((float)$r['berat_kg'],1,',','.')?> kg</td><td><?=rupiah($r['total_rp'])?></td><td><span class="status-pill status-<?=$r['status']?>"><?=ucfirst($r['status'])?></span></td></tr><?php endwhile; endif; ?></tbody></table></div></div>
<?php require "../includes/footer.php"; ?>
