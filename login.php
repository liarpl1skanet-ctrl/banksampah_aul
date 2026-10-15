<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - Bank Sampah AUL</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
<div class="login-card">
    <div class="brand-icon">♻️</div>
    <h2>Bank Sampah AUL</h2>
    <p class="text-muted mb-4">Kelola Sampah, Raih Masa Depan 🌷</p>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Username, password, atau pilihan role salah.</div>
    <?php endif; ?>

    <form action="proses_login.php" method="post" class="text-start">
        <!-- 1. Username di Paling Atas -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>

        <!-- 2. Option Role di Bagian Tengah -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Login Sebagai</label>
            <select name="role" class="form-select" required>
                <option value="" disabled selected>-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="pengepul">Pengepul</option>
                <option value="nasabah">Nasabah / User</option>
            </select>
        </div>

        <!-- 3. Password di Paling Bawah -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="text-center mt-3">
        <small class="text-muted">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar sebagai Nasabah</a></small>
    </div>

        <button class="btn btn-pink w-100 mt-2">Masuk ke Aplikasi</button>
    </form>
</div>
</body>
</html>