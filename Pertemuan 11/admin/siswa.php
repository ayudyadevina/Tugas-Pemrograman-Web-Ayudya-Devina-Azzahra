<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if (q('SELECT 1 FROM pendaftaran WHERE id_siswa=?', [$id])->fetch()) flash('danger', 'Siswa sudah punya pendaftaran dan tidak dapat dihapus.');
    else { q('DELETE FROM siswa WHERE id_siswa=?', [$id]); flash('success', 'Data siswa dihapus.'); }
    redirect('admin/siswa.php');
}
$cari = trim($_GET['q'] ?? '');
$rows = q('SELECT * FROM siswa WHERE nama_lengkap LIKE ? OR nisn LIKE ? ORDER BY nama_lengkap', ["%$cari%", "%$cari%"])->fetchAll();
layout_start('Data siswa', 'siswa');
?>
<h3 class="fw-bold mb-3">Data siswa</h3>
<form class="d-flex gap-2 mb-3"><input class="form-control" name="q" value="<?= e($cari) ?>" placeholder="Cari nama atau NISN"><button class="btn btn-primary">Cari</button></form>
<div class="panel p-0 table-responsive"><table class="table mb-0"><thead><tr><th>NISN</th><th>Nama</th><th>JK</th><th>Asal sekolah</th><th>No. HP</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['nisn']) ?></td><td><?= e($r['nama_lengkap']) ?></td><td><?= $r['jenis_kelamin'] ?></td><td><?= e($r['asal_sekolah']) ?></td><td><?= e($r['no_hp']) ?></td>
<td><form method="post" onsubmit="return confirm('Hapus data siswa ini?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id_siswa'] ?>"><button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>
<?php endforeach; if (!$rows) echo '<tr><td colspan="6" class="text-center text-muted">Belum ada data siswa.</td></tr>'; ?></tbody></table></div>
<?php layout_end();
