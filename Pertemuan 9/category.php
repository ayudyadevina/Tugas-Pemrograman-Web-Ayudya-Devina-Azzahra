<?php
require_once 'includes/functions.php';
$st = db()->prepare("SELECT * FROM categories WHERE slug=?"); $st->execute([$_GET['slug'] ?? '']);
$cat = $st->fetch();
if (!$cat) { http_response_code(404); $pageTitle = 'Kategori tidak ditemukan'; include 'includes/header.php'; echo '<p class="empty">Kategori tidak ditemukan.</p>'; include 'includes/footer.php'; exit; }
$page = max(1, (int)($_GET['page'] ?? 1));
$total = news_count('n.category_id=?', [$cat['id']]);
$items = news_list('n.category_id=?', [$cat['id']], PER_PAGE, ($page - 1) * PER_PAGE);
$pageTitle = $cat['name'];
include 'includes/header.php';
?>
<div class="page-head"><h1><?= e($cat['name']) ?></h1><p><?= e($cat['description']) ?></p></div>
<?php if (!$items): ?><p class="empty">Belum ada berita di kategori ini.</p><?php endif; ?>
<div class="grid"><?php foreach ($items as $n) echo card($n); ?></div>
<?= pagination($total, $page, url('category.php?slug=' . $cat['slug'])) ?>
<?php include 'includes/footer.php'; ?>
