<?php
require_once '_layout.php';
$q = fn($sql) => (int)db()->query($sql)->fetchColumn();
$recent = db()->query("SELECT n.id,n.title,n.status,n.updated_at,c.name cat FROM news n JOIN categories c ON c.id=n.category_id ORDER BY n.updated_at DESC LIMIT 6")->fetchAll();
admin_head('Dashboard', 'index'); ?>
<div class="stats">
  <div class="stat"><b><?= $q("SELECT COUNT(*) FROM news WHERE status='published'") ?></b>Berita terbit</div>
  <div class="stat"><b><?= $q("SELECT COUNT(*) FROM news WHERE status='draft'") ?></b>Draf</div>
  <div class="stat"><b><?= $q("SELECT COUNT(*) FROM categories") ?></b>Kategori</div>
  <div class="stat"><b><?= $q("SELECT COUNT(*) FROM comments") ?></b>Komentar</div>
</div>
<div class="toolbar"><h2>Terakhir diubah</h2><a class="btn" href="news_form.php">Tulis berita</a></div>
<div class="tbl-wrap"><table><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Diubah</th></tr>
<?php foreach ($recent as $r): ?><tr><td><a href="news_form.php?id=<?= $r['id'] ?>"><?= e($r['title']) ?></a></td><td><?= e($r['cat']) ?></td>
<td><span class="badge <?= $r['status'] ?>"><?= $r['status'] === 'published' ? 'Terbit' : 'Draf' ?></span></td><td><?= tgl($r['updated_at'], true) ?></td></tr>
<?php endforeach; if (!$recent): ?><tr><td colspan="4">Belum ada berita.</td></tr><?php endif; ?></table></div>
<?php admin_foot();
