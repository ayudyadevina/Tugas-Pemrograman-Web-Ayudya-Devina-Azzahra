<?php
require_once __DIR__ . '/../includes/functions.php';
if (user()) redirect('admin/index.php');
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $st = db()->prepare("SELECT * FROM users WHERE username=?"); $st->execute([trim($_POST['username'] ?? '')]);
    $u = $st->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $u['id'], 'username' => $u['username'], 'fullname' => $u['fullname'], 'role' => $u['role']];
        redirect('admin/index.php');
    }
    $err = 'Username atau password salah.';
}
?>
<!DOCTYPE html><html lang="id" data-theme="light"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk — <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>"></head><body>
<div class="login-box"><h1>Masuk dashboard</h1><small>Khusus admin dan editor <?= SITE_NAME ?>.</small>
<?php if ($err): ?><p class="notice bad"><?= e($err) ?></p><?php endif; ?>
<form method="post" data-validate novalidate><?= csrf_field() ?>
<p class="notice bad js-msg" hidden></p>
<label>Username<input name="username" required autocomplete="username" autofocus></label>
<label>Password<input type="password" name="password" required minlength="6" autocomplete="current-password"></label>
<button class="btn">Masuk</button></form>
<p><a href="<?= url() ?>">Kembali ke beranda</a></p></div>
<script src="<?= url('assets/js/main.js') ?>"></script></body></html>
