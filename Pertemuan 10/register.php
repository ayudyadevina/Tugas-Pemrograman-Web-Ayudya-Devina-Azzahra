<?php
require_once __DIR__ . '/includes/layout.php';
if (user()) redirect('index.php');
$err = []; $v = ['nama' => '', 'email' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $v = ['nama' => trim($_POST['nama'] ?? ''), 'email' => strtolower(trim($_POST['email'] ?? ''))];
    $pw = $_POST['password'] ?? ''; $pw2 = $_POST['password2'] ?? '';
    if ($v['nama'] === '' || mb_strlen($v['nama']) > 100) $err[] = 'Nama lengkap wajib diisi (maksimal 100 karakter).';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $err[] = 'Format email tidak valid.';
    if (strlen($pw) < 6) $err[] = 'Password minimal 6 karakter.';
    if ($pw !== $pw2) $err[] = 'Konfirmasi password tidak sama.';
    if (!$err && q('SELECT 1 FROM users WHERE email=?', [$v['email']])->fetch()) $err[] = 'Email sudah terdaftar. Gunakan email lain atau masuk.';
    if (!$err) {
        q('INSERT INTO users (nama,email,password,role) VALUES (?,?,?,"siswa")', [$v['nama'], $v['email'], password_hash($pw, PASSWORD_DEFAULT)]);
        flash('success', 'Akun berhasil dibuat. Silakan masuk.'); redirect('login.php');
    }
}
layout_start('Buat akun');
?>
<div class="container py-5" style="max-width:480px"><div class="panel">
<h3 class="fw-bold mb-3">Buat akun calon siswa</h3>
<?php foreach ($err as $x) echo '<div class="alert alert-danger py-2">' . e($x) . '</div>'; ?>
<form method="post"><?= csrf_field() ?>
  <label class="form-label mt-2">Nama lengkap</label><input class="form-control" name="nama" value="<?= e($v['nama']) ?>" required>
  <label class="form-label mt-3">Email</label><input class="form-control" type="email" name="email" value="<?= e($v['email']) ?>" required>
  <label class="form-label mt-3">Password</label><input class="form-control" type="password" name="password" minlength="6" required>
  <label class="form-label mt-3">Konfirmasi password</label><input class="form-control" type="password" name="password2" required>
  <button class="btn btn-primary w-100 mt-4">Buat akun</button>
</form><p class="text-muted small mt-3 mb-0">Sudah punya akun? <a href="login.php">Masuk</a></p></div></div>
<?php layout_end();
