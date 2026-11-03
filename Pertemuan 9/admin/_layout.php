<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
function admin_head($title, $active) {
    $items = ['index' => 'Dashboard', 'news' => 'Berita', 'categories' => 'Kategori'];
    if (user()['role'] === 'admin') $items['users'] = 'Pengguna';
    $f = flash(); ?>
<!DOCTYPE html><html lang="id" data-theme="light"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> — Dashboard <?= SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>"></head><body>
<div class="admin"><aside class="side"><a class="brand" href="<?= url() ?>" target="_blank"><?= SITE_NAME ?></a>
<?php foreach ($items as $k => $v): ?><a href="<?= url("admin/$k.php") ?>" class="<?= $k === $active ? 'on' : '' ?>"><?= $v ?></a><?php endforeach; ?>
<div class="who"><?= e(user()['fullname']) ?> (<?= e(user()['role']) ?>)<br><a href="<?= url('admin/logout.php') ?>">Keluar</a></div></aside>
<section class="panel"><h1><?= e($title) ?></h1>
<?php if ($f): ?><p class="notice <?= $f[1] === 'err' ? 'bad' : '' ?>"><?= e($f[0]) ?></p><?php endif;
}
function admin_foot() { echo '</section></div></body></html>'; }
