<?php
// Daftar pendaftar + verifikasi + seleksi (ubah status) dalam satu halaman
require_once __DIR__ . '/../includes/layout.php'; need('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $err = ubah_status($id, $_POST['status'] ?? '', trim($_POST['catatan'] ?? ''), user()['id']);
    flash($err ? 'danger' : 'success', $err ?: 'Status pendaftaran diperbarui.');
    redirect('admin/pendaftaran.php?id=' . $id);
}
$ta = setting('tahun_ajaran');
if (isset($_GET['id'])) {
    $p = q('SELECT p.*, j.nama_jurusan, j.kode_jurusan FROM pendaftaran p JOIN jurusan j USING(id_jurusan) WHERE p.id_pendaftaran=?', [(int)$_GET['id']])->fetch();
    if (!$p) redirect('admin/pendaftaran.php');
    $s = q('SELECT s.*, u.email FROM siswa s JOIN users u USING(id_user) WHERE id_siswa=?', [$p['id_siswa']])->fetch();
    $bk = q('SELECT * FROM berkas WHERE id_pendaftaran=?', [$p['id_pendaftaran']])->fetchAll();
    $riw = q('SELECT r.*, u.nama admin FROM riwayat_status r LEFT JOIN users u ON u.id_user=r.id_admin WHERE id_pendaftaran=? ORDER BY id_riwayat DESC', [$p['id_pendaftaran']])->fetchAll();
    layout_start('Verifikasi', 'pendaftaran');
?>
<a href="pendaftaran.php" class="small">&larr; Kembali ke daftar</a>
<h3 class="fw-bold mt-2"><?= e($p['no_pendaftaran']) ?> <?= badge($p['status']) ?></h3>
<div class="row g-3"><div class="col-lg-6"><div class="panel h-100"><h6 class="fw-bold">Biodata</h6>
<table class="table table-sm mb-0"><tr><th>Nama</th><td><?= e($s['nama_lengkap']) ?></td></tr><tr><th>NISN</th><td><?= e($s['nisn']) ?></td></tr>
<tr><th>JK</th><td><?= $s['jenis_kelamin'] ?></td></tr><tr><th>TTL</th><td><?= e($s['tempat_lahir']) ?>, <?= tgl($s['tanggal_lahir']) ?></td></tr>
<tr><th>Alamat</th><td><?= e($s['alamat']) ?></td></tr><tr><th>Asal sekolah</th><td><?= e($s['asal_sekolah']) ?></td></tr>
<tr><th>HP / Email</th><td><?= e($s['no_hp']) ?> / <?= e($s['email']) ?></td></tr><tr><th>Jurusan</th><td><?= e($p['nama_jurusan']) ?></td></tr></table></div></div>
<div class="col-lg-6"><div class="panel h-100"><h6 class="fw-bold">Berkas</h6>
<?php foreach (JENIS_BERKAS as $k => $l) { $f = array_values(array_filter($bk, fn($x) => $x['jenis_berkas'] === $k))[0] ?? null;
  echo '<p class="mb-1">' . e($l) . ': ' . ($f ? '<a target="_blank" href="../file.php?id=' . $f['id_berkas'] . '">Lihat berkas</a>' : '<span class="text-danger">belum ada</span>') . '</p>'; } ?>
<hr><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $p['id_pendaftaran'] ?>">
<label class="form-label">Catatan</label><textarea class="form-control mb-2" name="catatan" rows="2"></textarea>
<div class="d-flex flex-wrap gap-2">
<button class="btn btn-primary btn-sm" name="status" value="Diverifikasi">Verifikasi</button>
<button class="btn btn-success btn-sm" name="status" value="Diterima">Terima</button>
<button class="btn btn-danger btn-sm" name="status" value="Ditolak">Tolak</button>
<button class="btn btn-outline-primary btn-sm" name="status" value="Menunggu">Minta perbaikan</button></div></form></div></div></div>
<div class="panel mt-3"><h6 class="fw-bold">Riwayat status</h6>
<?php foreach ($riw as $r) echo '<div class="timeline-item"><b>' . e(($r['status_sebelumnya'] ?: '-') . ' → ' . $r['status_baru']) . '</b> <span class="text-muted small">' . tgl($r['tanggal_perubahan']) . ' · ' . e($r['admin'] ?: 'Sistem') . '</span><br>' . e($r['catatan']) . '</div>'; ?></div>
<?php layout_end(); exit; }
$st = $_GET['status'] ?? ''; $cari = trim($_GET['q'] ?? '');
$rows = q('SELECT p.*, s.nama_lengkap, j.kode_jurusan FROM pendaftaran p JOIN siswa s USING(id_siswa) JOIN jurusan j USING(id_jurusan)
           WHERE p.tahun_ajaran=? AND (?="" OR p.status=?) AND (s.nama_lengkap LIKE ? OR p.no_pendaftaran LIKE ?) ORDER BY p.id_pendaftaran DESC',
          [$ta, $st, $st, "%$cari%", "%$cari%"])->fetchAll();
layout_start('Pendaftaran & verifikasi', 'pendaftaran');
?>
<h3 class="fw-bold mb-3">Pendaftaran & verifikasi</h3>
<form class="row g-2 mb-3"><div class="col-md-6"><input class="form-control" name="q" value="<?= e($cari) ?>" placeholder="Cari nama atau nomor"></div>
<div class="col-md-4"><select class="form-select" name="status"><option value="">Semua status</option><?php foreach (STATUS_LIST as $x) echo '<option ' . ($st === $x ? 'selected' : '') . '>' . $x . '</option>'; ?></select></div>
<div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div></form>
<div class="panel p-0 table-responsive"><table class="table mb-0"><thead><tr><th>No.</th><th>Nama</th><th>Jurusan</th><th>Tanggal</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['no_pendaftaran']) ?></td><td><?= e($r['nama_lengkap']) ?></td><td><?= e($r['kode_jurusan']) ?></td><td><?= tgl($r['tanggal_daftar']) ?></td><td><?= badge($r['status']) ?></td>
<td><a class="btn btn-sm btn-primary" href="?id=<?= $r['id_pendaftaran'] ?>">Periksa</a></td></tr>
<?php endforeach; if (!$rows) echo '<tr><td colspan="6" class="text-center text-muted">Tidak ada data yang cocok.</td></tr>'; ?></tbody></table></div>
<?php layout_end();
