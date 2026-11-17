<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = $_POST['aksi'] ?? ''; $id = (int)($_POST['id'] ?? 0);
    if ($a === 'toggle') { q('UPDATE jurusan SET aktif=1-aktif WHERE id_jurusan=?', [$id]); flash('success', 'Status jurusan diperbarui.'); }
    else {
        $kode = strtoupper(trim($_POST['kode'] ?? '')); $nama = trim($_POST['nama'] ?? ''); $kuota = (int)($_POST['kuota'] ?? 0); $desk = trim($_POST['deskripsi'] ?? '');
        if ($kode === '' || $nama === '' || $kuota < 1) flash('danger', 'Kode, nama, dan kuota (minimal 1) wajib diisi.');
        elseif ($id && $kuota < jml_diterima($id)) flash('danger', 'Kuota tidak boleh lebih kecil dari jumlah siswa yang sudah diterima (' . jml_diterima($id) . ').');
        else try {
            if ($id) q('UPDATE jurusan SET kode_jurusan=?,nama_jurusan=?,kuota=?,deskripsi=? WHERE id_jurusan=?', [$kode, $nama, $kuota, $desk, $id]);
            else q('INSERT INTO jurusan (kode_jurusan,nama_jurusan,kuota,deskripsi) VALUES (?,?,?,?)', [$kode, $nama, $kuota, $desk]);
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
  <div class="col-md-2"><input class="form-control" type="number" min="1" name="kuota" placeholder="Kuota" value="<?= e($edit['kuota'] ?? '') ?>" required></div>
  <div class="col-md-4"><input class="form-control" name="deskripsi" placeholder="Deskripsi singkat" value="<?= e($edit['deskripsi'] ?? '') ?>"></div></div>
  <button class="btn btn-primary mt-3">Simpan jurusan</button> <?php if ($edit) echo '<a class="btn btn-outline-primary mt-3" href="jurusan.php">Batal</a>'; ?>
</form>
<div class="panel p-0 table-responsive"><table class="table mb-0"><thead><tr><th>Kode</th><th>Nama</th><th>Kuota</th><th>Diterima</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['kode_jurusan']) ?></td><td><?= e($r['nama_jurusan']) ?></td><td><?= $r['kuota'] ?></td><td><?= jml_diterima($r['id_jurusan']) ?></td>
<td><?= $r['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
<td class="text-nowrap"><a class="btn btn-sm btn-primary" href="?edit=<?= $r['id_jurusan'] ?>">Ubah</a>
<form method="post" class="d-inline"><?= csrf_field() ?><input type="hidden" name="aksi" value="toggle"><input type="hidden" name="id" value="<?= $r['id_jurusan'] ?>"><button class="btn btn-sm btn-outline-primary"><?= $r['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?></button></form></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php layout_end();
