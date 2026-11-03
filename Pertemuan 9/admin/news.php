<?php
require_once '_layout.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') === 'delete') {
    csrf_check();
    $st = db()->prepare("SELECT * FROM news WHERE id=?"); $st->execute([(int)$_POST['id']]); $n = $st->fetch();
    if ($n && (user()['role'] === 'admin' || $n['author_id'] == user()['id'])) {
        db()->prepare("DELETE FROM news WHERE id=?")->execute([$n['id']]);
        if ($n['image'] && is_file(UPLOAD_DIR . $n['image'])) @unlink(UPLOAD_DIR . $n['image']);
        flash('Berita dihapus.');
    } else flash('Kamu tidak berhak menghapus berita ini.', 'err');
    redirect('admin/news.php');
}
$page = max(1, (int)($_GET['page'] ?? 1)); $per = 15;
$total = (int)db()->query("SELECT COUNT(*) FROM news")->fetchColumn();
$rows = db()->query("SELECT n.id,n.title,n.status,n.author_id,n.published_at,c.name cat,u.fullname author FROM news n
  JOIN categories c ON c.id=n.category_id JOIN users u ON u.id=n.author_id ORDER BY n.created_at DESC LIMIT $per OFFSET " . (($page - 1) * $per))->fetchAll();
admin_head('Berita', 'news'); ?>
<div class="toolbar"><span><?= $total ?> berita</span><a class="btn" href="news_form.php">Tulis berita</a></div>
<div class="tbl-wrap"><table><tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Terbit</th><th>Aksi</th></tr>
<?php foreach ($rows as $r): $can = user()['role'] === 'admin' || $r['author_id'] == user()['id']; ?>
<tr><td><?= e($r['title']) ?></td><td><?= e($r['cat']) ?></td><td><?= e($r['author']) ?></td>
<td><span class="badge <?= $r['status'] ?>"><?= $r['status'] === 'published' ? 'Terbit' : 'Draf' ?></span></td><td><?= tgl($r['published_at']) ?></td>
<td class="acts"><?php if ($can): ?><a class="btn sm" href="news_form.php?id=<?= $r['id'] ?>">Edit</a>
<form method="post" onsubmit="return confirm('Hapus berita ini?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn sm danger">Hapus</button></form><?php endif; ?></td></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="6">Belum ada berita.</td></tr><?php endif; ?></table></div>
<?php
$pages = (int)ceil($total / $per);
if ($pages > 1) { echo '<nav class="pager">'; for ($i = 1; $i <= $pages; $i++) echo $i == $page ? "<span>$i</span>" : "<a href=\"?page=$i\">$i</a>"; echo '</nav>'; }
admin_foot();
