<?php
require_once __DIR__ . '/includes/layout.php';
if ($u = user()) redirect($u['role'] === 'admin' ? 'admin/dashboard.php' : 'siswa/dashboard.php');
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $row = q('SELECT * FROM users WHERE email=?', [strtolower(trim($_POST['email'] ?? ''))])->fetch();
    if ($row && password_verify($_POST['password'] ?? '', $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $row['id_user'], 'nama' => $row['nama'], 'email' => $row['email'], 'role' => $row['role']];
        redirect($row['role'] === 'admin' ? 'admin/dashboard.php' : 'siswa/dashboard.php');
    }
    $err = 'Email atau password salah. Periksa lagi atau buat akun baru.';
}
layout_start('Masuk');
?>
<div class="container py-5" style="max-width:440px"><div class="panel">
<h3 class="fw-bold mb-3">Masuk</h3>
<?php if ($err) echo '<div class="alert alert-danger py-2">' . e($err) . '</div>'; ?>
<form method="post"><?= csrf_field() ?>
  <label class="form-label">Email</label><input class="form-control" type="email" name="email" required>
  <label class="form-label mt-3">Password</label><input class="form-control" type="password" name="password" required>
  <button class="btn btn-primary w-100 mt-4">Masuk</button>
</form><p class="text-muted small mt-3 mb-0">Belum punya akun? <a href="register.php">Daftar</a></p></div></div>
<?php layout_end();
