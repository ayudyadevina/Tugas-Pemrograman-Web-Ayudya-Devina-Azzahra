<?php
require_once '_layout.php';
$edit = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); $do = $_POST['do'] ?? ''; $id = (int)($_POST['id'] ?? 0);
    if ($do === 'delete') {
        $c = db()->prepare("SELECT COUNT(*) FROM news WHERE category_id=?"); $c->execute([$id]);
        if ($c->fetchColumn()) flash('Kategori masih dipakai berita. Pindahkan atau hapus beritanya dulu.', 'err');
        else { db()->prepare("DELETE FROM categories WHERE id=?")->execute([$id]); flash('Kategori dihapus.'); }
    } else {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') flash('Nama kategori wajib diisi.', 'err');
        else {
            $slug = unique_slug('categories', slugify($name), $id); $desc = trim($_POST['description'] ?? '');
            if ($id) db()->prepare("UPDATE categories SET name=?,slug=?,description=? WHERE id=?")->execute([$name, $slug, $desc, $id]);
            else db()->prepare("INSERT INTO categories (name,slug,description) VALUES (?,?,?)")->execute([$name, $slug, $desc]);
            flash('Kategori disimpan.');
        }
    }
    redirect('admin/categories.php');
}
if (!empty($_GET['edit'])) { $st = db()->prepare("SELECT * FROM categories WHERE id=?"); $st->execute([(int)$_GET['edit']]); $edit = $st->fetch(); }
$rows = db()->query("SELECT c.*, (SELECT COUNT(*) FROM news WHERE category_id=c.id) total FROM categories c ORDER BY name")->fetchAll();
admin_head('Kategori', 'categories'); ?>
<form class="form" method="post" data-validate novalidate style="margin-bottom:2rem"><?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>"><p class="notice bad js-msg" hidden></p>
  <div class="row"><label>Nama<input name="name" required maxlength="100" value="<?= e($edit['name'] ?? '') ?>"></label>
  <label>Deskripsi<input name="description" value="<?= e($edit['description'] ?? '') ?>"></label></div>
  <div class="acts"><button class="btn"><?= $edit ? 'Simpan perubahan' : 'Tambah kategori' ?></button><?php if ($edit): ?><a href="categories.php">Batal</a><?php endif; ?></div>
</form>
<div class="tbl-wrap"><table><tr><th>Nama</th><th>Slug</th><th>Deskripsi</th><th>Berita</th><th>Aksi</th></tr>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['name']) ?></td><td><?= e($r['slug']) ?></td><td><?= e($r['description']) ?></td><td><?= $r['total'] ?></td>
<td class="acts"><a class="btn sm" href="?edit=<?= $r['id'] ?>">Edit</a>
<form method="post" onsubmit="return confirm('Hapus kategori ini?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn sm danger">Hapus</button></form></td></tr>
<?php endforeach; ?></table></div>
<?php admin_foot();
