<?php
require_once '_layout.php';
$id = (int)($_GET['id'] ?? 0); $n = null;
if ($id) {
    $st = db()->prepare("SELECT * FROM news WHERE id=?"); $st->execute([$id]); $n = $st->fetch();
    if (!$n || (user()['role'] !== 'admin' && $n['author_id'] != user()['id'])) { flash('Berita tidak ditemukan atau bukan milikmu.', 'err'); redirect('admin/news.php'); }
}
$cats = categories(); $err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title'] ?? ''); $content = trim($_POST['content'] ?? '');
    $cat = (int)($_POST['category_id'] ?? 0); $status = ($_POST['status'] ?? '') === 'published' ? 'published' : 'draft';
    try {
        if ($title === '' || mb_strlen($title) > 200) throw new Exception('Judul wajib diisi (maksimal 200 karakter).');
        if ($content === '') throw new Exception('Isi berita wajib diisi.');
        if (!in_array($cat, array_column($cats, 'id'))) throw new Exception('Pilih kategori.');
        $image = upload_image($_FILES['image'] ?? null, $n['image'] ?? null);
        if (!empty($_POST['remove_image']) && $image && !($_FILES['image']['name'] ?? '')) { @unlink(UPLOAD_DIR . $image); $image = null; }
        $pub = $_POST['published_at'] ? date('Y-m-d H:i:s', strtotime($_POST['published_at'])) : ($n['published_at'] ?? null);
        if ($status === 'published' && !$pub) $pub = date('Y-m-d H:i:s');
        $slug = unique_slug('news', slugify($title), $id);
        if ($n) {
            db()->prepare("UPDATE news SET category_id=?,title=?,slug=?,content=?,image=?,published_at=?,status=? WHERE id=?")
              ->execute([$cat, $title, $slug, $content, $image, $pub, $status, $id]);
        } else {
            db()->prepare("INSERT INTO news (category_id,title,slug,content,image,author_id,published_at,status) VALUES (?,?,?,?,?,?,?,?)")
              ->execute([$cat, $title, $slug, $content, $image, user()['id'], $pub, $status]);
        }
        flash('Berita disimpan.'); redirect('admin/news.php');
    } catch (Exception $ex) { $err = $ex->getMessage(); $n = array_merge($n ?? [], $_POST, ['image' => $n['image'] ?? null]); }
}
$v = fn($k, $d = '') => e($n[$k] ?? $d);
admin_head($id ? 'Edit berita' : 'Tulis berita', 'news'); ?>
<?php if ($err): ?><p class="notice bad"><?= e($err) ?></p><?php endif; ?>
<form class="form" method="post" enctype="multipart/form-data" data-validate novalidate><?= csrf_field() ?>
  <p class="notice bad js-msg" hidden></p>
  <label>Judul<input name="title" maxlength="200" required value="<?= $v('title') ?>"></label>
  <div class="row">
    <label>Kategori<select name="category_id" required><option value="">Pilih kategori</option>
      <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= ($n['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></label>
    <label>Status<select name="status"><option value="draft" <?= ($n['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draf</option><option value="published" <?= ($n['status'] ?? '') === 'published' ? 'selected' : '' ?>>Terbit</option></select></label>
  </div>
  <label>Waktu terbit <small>Kosongkan untuk memakai waktu saat ini</small><input type="datetime-local" name="published_at" value="<?= !empty($n['published_at']) ? date('Y-m-d\TH:i', strtotime($n['published_at'])) : '' ?>"></label>
  <label>Gambar utama <small>JPG, PNG, atau WEBP, maksimal 2 MB</small><input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
  <?php if (!empty($n['image'])): ?><div><img class="thumb" src="<?= img_url($n['image']) ?>" alt=""><label style="flex-direction:row;gap:.5rem;margin-top:.5rem"><input type="checkbox" name="remove_image" value="1"> Hapus gambar ini</label></div><?php endif; ?>
    <label>Isi berita<textarea id="content" name="content" rows="14" required><?= $v('content') ?></textarea></label>
  <div class="notice" id="inlineBox">
    <strong>Sisipkan gambar ke isi berita</strong><br>
    <small>Letakkan kursor di teks di tempat gambar akan muncul, pilih gambar, lalu klik tombol.</small><br>
    <input type="file" id="inlineFile" accept="image/jpeg,image/png,image/webp">
    <input type="text" id="inlineCap" placeholder="Keterangan gambar (opsional)">
    <button type="button" class="btn sm" id="inlineBtn">Unggah dan sisipkan</button>
    <p id="inlineMsg" style="margin:.4rem 0 0"></p>
  </div>
  <script>
  document.getElementById('inlineBtn').addEventListener('click', function () {
    var f = document.getElementById('inlineFile').files[0], msg = document.getElementById('inlineMsg');
    if (!f) { msg.textContent = 'Pilih gambar dulu.'; return; }
    var fd = new FormData();
    fd.append('image', f); fd.append('csrf', document.querySelector('input[name=csrf]').value);
    msg.textContent = 'Mengunggah…';
    fetch('upload_inline.php', { method: 'POST', body: fd }).then(function (r) { return r.json(); }).then(function (d) {
      if (!d.ok) { msg.textContent = d.error; return; }
      var t = document.getElementById('content'), cap = document.getElementById('inlineCap').value.replace(/[\[\]\r\n]/g, ' ').trim();
      var tag = '\n\n![' + cap + '](' + d.name + ')\n\n', p = t.selectionStart;
      t.value = t.value.slice(0, p) + tag + t.value.slice(t.selectionEnd);
      t.focus(); t.selectionStart = t.selectionEnd = p + tag.length;
      document.getElementById('inlineFile').value = ''; document.getElementById('inlineCap').value = '';
      msg.textContent = 'Gambar disisipkan. Jangan lupa klik Simpan berita.';
    }).catch(function () { msg.textContent = 'Unggah gagal. Coba lagi.'; });
  });
  </script>
  <div class="acts"><button class="btn">Simpan berita</button><a class="btn-line" style="color:var(--fg);border-color:var(--line)" href="news.php">Batal</a></div>
</form>
<?php admin_foot();
