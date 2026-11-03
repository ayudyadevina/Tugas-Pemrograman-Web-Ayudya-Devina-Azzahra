<?php
require_once 'includes/functions.php';
$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$items = []; $total = 0;
if ($q !== '') {
    $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
    $w = '(n.title LIKE ? OR n.content LIKE ?)';
    $total = news_count($w, [$like, $like]);
    $items = news_list($w, [$like, $like], PER_PAGE, ($page - 1) * PER_PAGE);
}
$pageTitle = $q !== '' ? 'Cari: ' . $q : 'Pencarian';
include 'includes/header.php';
?>
<div class="page-head">
  <h1>Pencarian</h1>
  <form class="search search-big" action="" role="search">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Ketik kata kunci…" aria-label="Kata kunci" required>
    <button>Cari</button>
  </form>
  <?php if ($q !== ''): ?><p><?= $total ?> hasil untuk &ldquo;<?= e($q) ?>&rdquo;</p><?php endif; ?>
</div>
<?php if ($q !== '' && !$items): ?><p class="empty">Tidak ada berita yang cocok. Coba kata kunci lain yang lebih singkat.</p><?php endif; ?>
<div class="grid"><?php foreach ($items as $n) echo card($n); ?></div>
<?= pagination($total, $page, url('search.php?q=' . urlencode($q))) ?>
<?php include 'includes/footer.php'; ?>
