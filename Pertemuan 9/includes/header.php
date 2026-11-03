<?php require_once __DIR__ . '/functions.php'; $cats = categories(); $pageTitle = $pageTitle ?? 'Berita terkini'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> — <?= SITE_NAME ?></title>
<script>try{document.documentElement.dataset.theme=localStorage.getItem('theme')||'light'}catch(e){}</script>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<header class="site-head">
  <div class="wrap bar">
    <a class="brand" href="<?= url() ?>"><?= SITE_NAME ?></a>
    <form class="search" action="<?= url('search.php') ?>" role="search">
      <input type="search" name="q" placeholder="Cari berita…" aria-label="Cari berita" value="<?= e($_GET['q'] ?? '') ?>">
      <button>Cari</button>
    </form>
    <div class="tools">
      <button id="themeBtn" type="button" aria-label="Ganti mode terang/gelap">Mode</button>
      <a class="btn-line" href="<?= url('admin/login.php') ?>">Masuk</a>
    </div>
  </div>
  <nav class="wrap cats" aria-label="Kategori">
    <a href="<?= url() ?>">Beranda</a>
    <?php foreach ($cats as $c): ?><a href="<?= url('category.php?slug=' . $c['slug']) ?>"><?= e($c['name']) ?></a><?php endforeach; ?>
  </nav>
</header>
<main class="wrap">
