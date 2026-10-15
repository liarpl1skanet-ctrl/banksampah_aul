<?php
require "../auth.php"; wajib_role("pengepul"); require "../config.php";
$uid=(int)$_SESSION['id_user'];
$data=mysqli_query($koneksi,"SELECT t.*,s.jenis_sampah FROM transaksi t JOIN sampah s ON t.id_sampah=s.id_sampah WHERE t.id_user=$uid AND t.tipe='beli' ORDER BY t.id_transaksi DESC");
$page_title="Riwayat Pembelian - Pengepul"; $active="riwayat"; $base="../"; require "../includes/header.php";
?>
<div class="card-soft"><div class="card-title"><h3>📜 Riwayat Pembelian Saya</h3></div><div class="table-responsive"><table class="table"><thead><tr><th>Tanggal</th><th>Jenis Sampah</th><th>Berat</th><th>Total</th><th>Status</th></tr></thead><tbody>
<?php if(!$data || mysqli_num_rows($data)===0): ?><tr><td colspan="5" class="empty">Belum ada riwayat pembelian.</td></tr><?php else: while($r=mysqli_fetch_assoc($data)): ?><tr><td><?=date('d M Y',strtotime($r['tanggal']))?></td><td><?=htmlspecialchars($r['jenis_sampah'])?></td><td><?=number_format((float)$r['berat_kg'],1,',','.')?> kg</td><td><?=rupiah($r['total_rp'])?></td><td><span class="status-pill status-<?=$r['status']?>"><?=ucfirst($r['status'])?></span></td></tr><?php endwhile; endif; ?></tbody></table></div></div>
<?php require "../includes/footer.php"; ?>
