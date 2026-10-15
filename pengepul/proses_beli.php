<?php require 'helper.php';
if ($idPengepul <= 0) die('Akun pengepul belum terhubung.');
$idSampah=(int)($_POST['id_sampah'] ?? 0); $berat=(float)($_POST['berat_kg'] ?? 0);
if($idSampah<=0 || $berat<=0) die('Data pembelian tidak valid.');
mysqli_begin_transaction($koneksi);
try {
  $q=mysqli_query($koneksi,"SELECT harga_per_kg, stok_kg FROM sampah WHERE id_sampah=$idSampah FOR UPDATE");
  $s=mysqli_fetch_assoc($q); if(!$s || $berat>(float)$s['stok_kg']) throw new Exception('Stok tidak mencukupi.');
  $total=$berat*(float)$s['harga_per_kg']; $tanggal=date('Y-m-d'); $uid=(int)$sessionUserId;
  $stmt=mysqli_prepare($koneksi,"INSERT INTO transaksi (id_user,id_sampah,id_pengepul,tipe,berat_kg,total_rp,status,tanggal) VALUES (?,?,?,?,?,?,?,?)");
  $tipe='beli'; $status='menunggu'; mysqli_stmt_bind_param($stmt,'iiidsdss',$uid,$idSampah,$idPengepul,$tipe,$berat,$total,$status,$tanggal);
  if(!mysqli_stmt_execute($stmt)) throw new Exception(mysqli_error($koneksi));
  mysqli_commit($koneksi); header('Location: riwayat.php?success=1'); exit;
} catch(Exception $e){mysqli_rollback($koneksi); die('Pembelian gagal: '.h($e->getMessage()));}
