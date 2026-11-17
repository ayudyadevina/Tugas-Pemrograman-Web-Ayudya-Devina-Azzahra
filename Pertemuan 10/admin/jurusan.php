<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = $_POST['aksi'] ?? ''; $id = (int)($_POST['id'] ?? 0);
    if ($a === 'toggle') { q('UPDATE jurusan SET aktif=1-aktif WHERE id_jurusan=?', [$id]); flash('success', 'Status jurusan diperbarui.'); }
    elseif ($a === 'hapus') {
        if (q('SELECT 1 FROM pendaftaran WHERE id_jurusan=?', [$id])->fetch()) flash('danger', 'Jurusan sudah dipilih pendaftar dan tidak dapat dihapus. Nonaktifkan saja.');
        else { q('DELETE FROM jurusan WHERE id_jurusan=?', [$id]); flash('success', 'Jurusan dihapus.'); }
    } else {
        $kode = strtoupper(trim($_POST['kode'] ?? '')); $nama = trim($_POST['nama'] ?? ''); $desk = trim($_POST['deskripsi'] ?? '');
        if ($kode === '' || $nama === '') flash('danger', 'Kode dan nama jurusan wajib diisi.');
        else try {
            if ($id) q('UPDATE jurusan SET kode_jurusan=?,nama_jurusan=?,deskripsi=? WHERE id_jurusan=?', [$kode, $nama, $desk, $id]);
            else q('INSERT INTO jurusan (kode_jurusan,nama_jurusan,deskripsi) VALUES (?,?,?)', [$kode, $nama, $desk]);
            flash('success', 'Jurusan disimpan.');
        } catch (PDOException $e) { flash('danger', 'Kode jurusan sudah dipakai.'); }
    }
    redirect('admin/jurusan.php');
}
$edit = isset($_GET['edit']) ? q('SELECT * FROM jurusan WHERE id_jurusan=?', [(int)$_GET['edit']])->fetch() : null;
$rows = q('SELECT * FROM jurusan ORDER BY kode_jurusan')->fetchAll();
layout_start('Data jurusan', 'jurusan');
?>
<h3 class="fw-bold mb-3">Data jurusan</h3>
<form method="post" class="panel mb-4"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $edit['id_jurusan'] ?? 0 ?>">
  <h6 class="fw-bold"><?= $edit ? 'Ubah jurusan' : 'Tambah jurusan' ?></h6>
  <div class="row g-2"><div class="col-md-2"><input class="form-control" name="kode" placeholder="Kode" value="<?= e($edit['kode_jurusan'] ?? '') ?>" required></div>
  <div class="col-md-4"><input class="form-control" name="nama" placeholder="Nama jurusan" value="<?= e($edit['nama_jurusan'] ?? '') ?>" required></div>
  <div class="col-md-6"><input class="form-control" name="deskripsi" placeholder="Deskripsi singkat" value="<?= e($edit['deskripsi'] ?? '') ?>"></div></div>
  <button class="btn btn-primary mt-3">Simpan jurusan</button> <?php if ($edit) echo '<a class="btn btn-outline-primary mt-3" href="jurusan.php">Batal</a>'; ?>
</form>
<div class="panel p-0 table-responsive"><table class="table mb-0"><thead><tr><th>Kode</th><th>Nama</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['kode_jurusan']) ?></td><td><?= e($r['nama_jurusan']) ?></td>
<td><?= $r['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
<td class="text-nowrap"><a class="btn btn-sm btn-primary" href="?edit=<?= $r['id_jurusan'] ?>">Ubah</a>
<form method="post" class="d-inline"><?= csrf_field() ?><input type="hidden" name="aksi" value="toggle"><input type="hidden" name="id" value="<?= $r['id_jurusan'] ?>"><button class="btn btn-sm btn-outline-primary"><?= $r['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?></button></form>
<form method="post" class="d-inline" onsubmit="return confirm('Hapus jurusan ini?')"><?= csrf_field() ?><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= $r['id_jurusan'] ?>"><button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php layout_end();
