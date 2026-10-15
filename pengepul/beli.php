<?php
require "../auth.php"; wajib_role("pengepul"); require "../config.php";
$uid=(int)$_SESSION['id_user']; $pesan='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['id_sampah']??0); $berat=(float)($_POST['berat']??0);
 $stmt=mysqli_prepare($koneksi,"SELECT * FROM sampah WHERE id_sampah=? AND stok_kg>0"); mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt); $s=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
 if($s && $berat>0 && $berat <= (float)$s['stok_kg']){
  $total=$berat*(float)$s['harga_per_kg'];
  $pstmt=mysqli_prepare($koneksi,"SELECT id_pengepul FROM pengepul WHERE username=? LIMIT 1"); mysqli_stmt_bind_param($pstmt,'s',$_SESSION['username']); mysqli_stmt_execute($pstmt); $pi=mysqli_fetch_assoc(mysqli_stmt_get_result($pstmt)); $pid=$pi['id_pengepul']??null;
  $q=mysqli_prepare($koneksi,"INSERT INTO transaksi(id_user,id_sampah,id_pengepul,tipe,berat_kg,total_rp,status,tanggal) VALUES(?,?,?,'beli',?,?,'menunggu',CURDATE())"); mysqli_stmt_bind_param($q,'iiidd',$uid,$id,$pid,$berat,$total);
  if(mysqli_stmt_execute($q)) $pesan='success'; else $pesan='error';
 } else $pesan='invalid';
}
$sampah=mysqli_query($koneksi,"SELECT * FROM sampah WHERE stok_kg>0 ORDER BY jenis_sampah ASC");
$page_title='Beli Sampah - Pengepul'; $active='beli'; $base='../'; require '../includes/header.php';
?>
<div class="row g-4"><div class="col-lg-7"><div class="card-soft"><div class="card-title"><h3>🛒 Beli Sampah</h3></div>
<?php if($pesan==='success'): ?><div class="alert alert-success">Permintaan pembelian berhasil dikirim. Tunggu verifikasi Admin. ✨</div><?php elseif($pesan==='invalid'): ?><div class="alert alert-warning">Jumlah melebihi stok atau data tidak valid.</div><?php elseif($pesan==='error'): ?><div class="alert alert-danger">Pembelian gagal disimpan.</div><?php endif; ?>
<form method="post"><div class="mb-3"><label class="form-label fw-semibold">Jenis Sampah</label><select name="id_sampah" id="id_sampah" class="form-select" required><option value="">-- Pilih sampah --</option><?php while($r=mysqli_fetch_assoc($sampah)): ?><option value="<?=$r['id_sampah']?>" data-harga="<?=$r['harga_per_kg']?>" data-stok="<?=$r['stok_kg']?>"><?=htmlspecialchars($r['jenis_sampah'])?> — <?=rupiah($r['harga_per_kg'])?>/kg (stok <?=$r['stok_kg']?> kg)</option><?php endwhile; ?></select></div><div class="mb-3"><label class="form-label fw-semibold">Berat Pembelian (kg)</label><input type="number" name="berat" id="berat" step="0.1" min="0.1" class="form-control" placeholder="Contoh: 5" required></div><div class="alert alert-light">Total perkiraan: <strong id="total">Rp 0</strong></div><button class="btn btn-pink w-100">Kirim Permintaan Pembelian</button></form></div></div><div class="col-lg-5"><div class="card-soft"><div class="card-title"><h3>💡 Cara Membeli</h3></div><p>1. Pilih jenis sampah yang tersedia.</p><p>2. Masukkan jumlah kilogram.</p><p>3. Periksa total harga.</p><p class="mb-0">4. Kirim dan tunggu persetujuan Admin.</p></div></div></div>
<?php $extra_script="<script>const s=document.getElementById('id_sampah'),b=document.getElementById('berat'),t=document.getElementById('total');function hitung(){const o=s.options[s.selectedIndex];const h=parseFloat(o?.dataset.harga||0);const w=parseFloat(b.value||0);t.textContent='Rp '+Math.round(h*w).toLocaleString('id-ID')}s.addEventListener('change',hitung);b.addEventListener('input',hitung);</script>"; require '../includes/footer.php'; ?>
