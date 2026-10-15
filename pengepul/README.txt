FOLDER PENGEPUL BANK SAMPAH AUL

Cara pemasangan:
1. Salin folder pengepul ini ke banksampah_aul/pengepul dan timpa file lama.
2. Pastikan file ../auth.php, ../config.php, ../includes/header.php dan ../includes/footer.php tetap ada.
3. Jalankan database_patch_pengepul.sql dari folder utama jika akun pengepul belum terhubung.
4. Login memakai akun dengan role pengepul.

Alur:
Dashboard -> Beli Sampah -> Ajukan Pembelian -> Admin menyetujui -> Riwayat menampilkan status.

Catatan:
- Pembelian disimpan sebagai tipe 'beli'.
- Stok sebaiknya dikurangi pada proses persetujuan Admin, bukan ketika pengajuan dibuat.
- Sesuaikan file admin/transaksi.php agar memproses tipe 'beli'.
