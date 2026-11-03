<?php
require_once 'includes/functions.php';
$st = db()->prepare(NEWS_SELECT . " WHERE n.slug=? AND n.status='published' AND n.published_at<=NOW()");
$st->execute([$_GET['slug'] ?? '']);
$n = $st->fetch();
if (!$n) { http_response_code(404); $pageTitle = 'Berita tidak ditemukan'; include 'includes/header.php'; echo '<p class="empty">Berita tidak ditemukan atau belum terbit.</p>'; include 'includes/footer.php'; exit; }

$err = $ok = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $cm = trim($_POST['comment'] ?? '');
    if ($name === '' || $cm === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $err = 'Isi nama, email yang valid, dan komentar.';
    elseif (mb_strlen($cm) > 1000) $err = 'Komentar maksimal 1000 karakter.';
    else {
        db()->prepare("INSERT INTO comments (news_id,name,email,comment) VALUES (?,?,?,?)")->execute([$n['id'], mb_substr($name, 0, 100), $email, $cm]);
        flash('Komentar terkirim.'); redirect('news.php?slug=' . $n['slug'] . '#komentar');
    }
}
$cm = db()->prepare("SELECT * FROM comments WHERE news_id=? ORDER BY created_at DESC"); $cm->execute([$n['id']]); $comments = $cm->fetchAll();
$related = news_list('n.category_id=? AND n.id<>?', [$n['category_id'], $n['id']], 3);
$pageTitle = $n['title'];
include 'includes/header.php';
$f = flash();
?>
<article class="detail">
  <a class="tag" href="<?= url('category.php?slug=' . $n['cat_slug']) ?>"><?= e($n['cat_name']) ?></a>
  <h1><?= e($n['title']) ?></h1>
  <p class="byline">Oleh <strong><?= e($n['author']) ?></strong> &middot; <?= tgl($n['published_at'], true) ?> &middot; <?= read_time($n['content']) ?> menit baca</p>
  <?php if ($n['image']): ?><figure><img src="<?= img_url($n['image']) ?>" alt="<?= e($n['title']) ?>"></figure><?php endif; ?>
  <div class="body"><?= render_body($n['content']) ?></div>
</article>

<section id="komentar" class="comments">
  <h2>Komentar (<?= count($comments) ?>)</h2>
  <?php if ($f): ?><p class="notice"><?= e($f[0]) ?></p><?php endif; ?>
  <?php if ($err): ?><p class="notice bad"><?= e($err) ?></p><?php endif; ?>
  <form method="post" class="cform" data-validate>
    <?= csrf_field() ?>
    <label>Nama<input name="name" required maxlength="100" value="<?= e($_POST['name'] ?? '') ?>"></label>
    <label>Email<input type="email" name="email" required maxlength="150" value="<?= e($_POST['email'] ?? '') ?>"></label>
    <label class="full">Komentar<textarea name="comment" rows="4" required maxlength="1000"><?= e($_POST['comment'] ?? '') ?></textarea></label>
    <button class="btn">Kirim komentar</button>
  </form>
  <?php foreach ($comments as $c): ?>
  <div class="comment"><strong><?= e($c['name']) ?></strong> <small><?= tgl($c['created_at'], true) ?></small><p><?= nl2br(e($c['comment'])) ?></p></div>
  <?php endforeach; ?>
</section>

<?php if ($related): ?>
<section class="cat-block"><div class="cat-head"><h2>Berita terkait</h2></div>
<div class="grid g3"><?php foreach ($related as $r) echo card($r); ?></div></section>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
