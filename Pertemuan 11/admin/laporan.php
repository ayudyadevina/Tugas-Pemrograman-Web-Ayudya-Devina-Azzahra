<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
$ta = setting('tahun_ajaran'); $idj = (int)($_GET['jurusan'] ?? 0); $st = $_GET['status'] ?? '';
$sql = 'SELECT p.no_pendaftaran, s.nisn, s.nama_lengkap, s.jenis_kelamin, s.asal_sekolah, j.kode_jurusan, p.status FROM pendaftaran p
        JOIN siswa s USING(id_siswa) JOIN jurusan j USING(id_jurusan) WHERE p.tahun_ajaran=? AND (?=0 OR p.id_jurusan=?) AND (?="" OR p.status=?) ORDER BY j.kode_jurusan, s.nama_lengkap';
$rows = q($sql, [$ta, $idj, $idj, $st, $st])->fetchAll();
if (isset($_GET['export'])) {           // ekspor CSV (dapat dibuka di Excel)
    header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="laporan-ppdb-' . date('Ymd') . '.csv"');
    $o = fopen('php://output', 'w'); fwrite($o, "\xEF\xBB\xBF");
    fputcsv($o, ['No. Pendaftaran', 'NISN', 'Nama', 'JK', 'Asal Sekolah', 'Jurusan', 'Status']);
    foreach ($rows as $r) fputcsv($o, array_values($r)); exit;
}
$L = count(array_filter($rows, fn($r) => $r['jenis_kelamin'] === 'L'));
$jur = q('SELECT id_jurusan, kode_jurusan FROM jurusan ORDER BY kode_jurusan')->fetchAll();
layout_start('Laporan', 'laporan');
?>
<h3 class="fw-bold mb-3">Laporan PPDB</h3>
<form class="row g-2 mb-3"><div class="col-md-4"><select class="form-select" name="jurusan"><option value="0">Semua jurusan</option>
<?php foreach ($jur as $j) echo '<option value="' . $j['id_jurusan'] . '" ' . ($idj == $j['id_jurusan'] ? 'selected' : '') . '>' . e($j['kode_jurusan']) . '</option>'; ?></select></div>
<div class="col-md-4"><select class="form-select" name="status"><option value="">Semua status</option><?php foreach (STATUS_LIST as $x) echo '<option ' . ($st === $x ? 'selected' : '') . '>' . $x . '</option>'; ?></select></div>
<div class="col-md-4 d-flex gap-2"><button class="btn btn-primary">Terapkan</button><button class="btn btn-outline-primary" name="export" value="1">Ekspor CSV</button></div></form>
<div class="row g-3 mb-3"><div class="col-4"><div class="stat"><span class="text-muted small">Total</span><b><?= count($rows) ?></b></div></div>
<div class="col-4"><div class="stat sky"><span class="text-muted small">Laki-laki</span><b><?= $L ?></b></div></div>
<div class="col-4"><div class="stat"><span class="text-muted small">Perempuan</span><b><?= count($rows) - $L ?></b></div></div></div>
<div class="panel p-0 table-responsive"><table class="table mb-0"><thead><tr><th>No.</th><th>NISN</th><th>Nama</th><th>JK</th><th>Jurusan</th><th>Status</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['no_pendaftaran']) ?></td><td><?= e($r['nisn']) ?></td><td><?= e($r['nama_lengkap']) ?></td><td><?= $r['jenis_kelamin'] ?></td><td><?= e($r['kode_jurusan']) ?></td><td><?= badge($r['status']) ?></td></tr>
<?php endforeach; if (!$rows) echo '<tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>'; ?></tbody></table></div>
<?php layout_end();
