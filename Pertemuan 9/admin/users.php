<?php
require_once '_layout.php'; require_login('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); $do = $_POST['do'] ?? ''; $id = (int)($_POST['id'] ?? 0);
    try {
        if ($do === 'add') {
            $un = trim($_POST['username'] ?? ''); $fn = trim($_POST['fullname'] ?? ''); $pw = $_POST['password'] ?? '';
            $role = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor';
            if (!preg_match('/^[A-Za-z0-9_.]{3,50}$/', $un) || $fn === '' || strlen($pw) < 6) throw new Exception('Username 3–50 karakter (huruf/angka), nama lengkap wajib, password minimal 6 karakter.');
            db()->prepare("INSERT INTO users (username,password,fullname,role) VALUES (?,?,?,?)")->execute([$un, password_hash($pw, PASSWORD_BCRYPT), $fn, $role]);
            flash('Pengguna ditambahkan.');
        } elseif ($do === 'role' && $id != user()['id']) {
            db()->prepare("UPDATE users SET role=? WHERE id=?")->execute([($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor', $id]); flash('Level diubah.');
        } elseif ($do === 'reset') {
            $pw = $_POST['password'] ?? ''; if (strlen($pw) < 6) throw new Exception('Password baru minimal 6 karakter.');
            db()->prepare("UPDATE users SET password=? WHERE id=?")->execute([password_hash($pw, PASSWORD_BCRYPT), $id]); flash('Password direset.');
        } elseif ($do === 'delete' && $id != user()['id']) {
            db()->prepare("DELETE FROM users WHERE id=?")->execute([$id]); flash('Pengguna dihapus.');
        }
    } catch (PDOException $ex) { flash($ex->getCode() == 23000 ? 'Username sudah dipakai, atau pengguna masih punya berita.' : 'Terjadi kesalahan database.', 'err'); }
    catch (Exception $ex) { flash($ex->getMessage(), 'err'); }
    redirect('admin/users.php');
}
$rows = db()->query("SELECT * FROM users ORDER BY created_at")->fetchAll();
admin_head('Pengguna', 'users'); ?>
<form class="form" method="post" data-validate novalidate style="margin-bottom:2rem"><?= csrf_field() ?><input type="hidden" name="do" value="add"><p class="notice bad js-msg" hidden></p>
  <div class="row"><label>Username<input name="username" required></label><label>Nama lengkap<input name="fullname" required></label></div>
  <div class="row"><label>Password<input type="password" name="password" required minlength="6"></label>
  <label>Level<select name="role"><option value="editor">Editor</option><option value="admin">Admin</option></select></label></div>
  <button class="btn">Tambah pengguna</button></form>
<div class="tbl-wrap"><table><tr><th>Username</th><th>Nama</th><th>Level</th><th>Reset password</th><th>Aksi</th></tr>
<?php foreach ($rows as $r): $me = $r['id'] == user()['id']; ?><tr><td><?= e($r['username']) ?></td><td><?= e($r['fullname']) ?></td>
<td><?php if ($me): ?><span class="badge">admin (kamu)</span><?php else: ?><form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="do" value="role"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<select name="role" onchange="this.form.submit()"><option value="editor" <?= $r['role'] === 'editor' ? 'selected' : '' ?>>Editor</option><option value="admin" <?= $r['role'] === 'admin' ? 'selected' : '' ?>>Admin</option></select></form><?php endif; ?></td>
<td><form method="post" class="acts"><?= csrf_field() ?><input type="hidden" name="do" value="reset"><input type="hidden" name="id" value="<?= $r['id'] ?>"><input type="password" name="password" placeholder="Password baru" minlength="6" required style="width:150px"><button class="btn sm">Reset</button></form></td>
<td><?php if (!$me): ?><form method="post" onsubmit="return confirm('Hapus pengguna ini?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn sm danger">Hapus</button></form><?php endif; ?></td></tr>
<?php endforeach; ?></table></div>
<?php admin_foot();
