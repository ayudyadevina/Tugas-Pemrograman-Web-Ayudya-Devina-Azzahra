<?php
require_once 'includes/functions.php';
$pageTitle = 'Berita terkini';
$page = max(1, (int)($_GET['page'] ?? 1));
$slides = $page === 1 ? news_list("n.image IS NOT NULL", [], 5) : [];
$total = news_count();
$latest = news_list('1', [], PER_PAGE, ($page - 1) * PER_PAGE);
include 'includes/header.php';
?>
<?php if ($slides): ?>
<section class="slider" aria-roledescription="carousel" aria-label="Berita utama">
  <?php foreach ($slides as $i => $s): ?>
  <article class="slide<?= $i === 0 ? ' on' : '' ?>">
    <img src="<?= img_url($s['image']) ?>" alt="">
    <div class="slide-txt">
      <a class="tag" href="<?= url('category.php?slug=' . $s['cat_slug']) ?>"><?= e($s['cat_name']) ?></a>
      <h2><a href="<?= url('news.php?slug=' . $s['slug']) ?>"><?= e($s['title']) ?></a></h2>
      <small><?= tgl($s['published_at']) ?> &middot; <?= e($s['author']) ?></small>
    </div>
  </article>
  <?php endforeach; ?>
  <div class="slider-ctl">
    <button type="button" data-dir="-1" aria-label="Sebelumnya">&lsaquo;</button>
    <button type="button" data-dir="1" aria-label="Berikutnya">&rsaquo;</button>
  </div>
</section>
<?php endif; ?>

<h2 class="sec-title">Berita terbaru</h2>
<?php if (!$latest): ?><p class="empty">Belum ada berita yang terbit. Masuk ke dashboard untuk menulis berita pertama.</p><?php endif; ?>
<div class="grid"><?php foreach ($latest as $n) echo card($n); ?></div>
<?= pagination($total, $page, url('index.php')) ?>

<?php if ($page === 1): foreach ($cats as $c):
  $items = news_list('n.category_id=?', [$c['id']], 3);
  if (!$items) continue; ?>
<section class="cat-block">
  <div class="cat-head"><h2><?= e($c['name']) ?></h2><a href="<?= url('category.php?slug=' . $c['slug']) ?>">Lihat semua</a></div>
  <div class="grid g3"><?php foreach ($items as $n) echo card($n); ?></div>
</section>
<?php endforeach; endif; ?>
<?php include 'includes/footer.php'; ?>
