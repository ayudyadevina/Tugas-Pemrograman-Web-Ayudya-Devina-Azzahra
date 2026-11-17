<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
$c = ['total' => 0] + array_fill_keys(STATUS_LIST, 0);
foreach (q('SELECT status, COUNT(*) n FROM pendaftaran WHERE tahun_ajaran=? GROUP BY status', [TAHUN_AJARAN]) as $r) { $c[$r['status']] = (int)$r['n']; $c['total'] += (int)$r['n']; }
$jur = q('SELECT j.kode_jurusan, j.nama_jurusan, (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_jurusan=j.id_jurusan AND p.tahun_ajaran=?) pendaftar FROM jurusan j ORDER BY kode_jurusan', [TAHUN_AJARAN])->fetchAll();
$jmlSiswa = (int)q('SELECT COUNT(*) FROM siswa')->fetchColumn();
layout_start('Dashboard admin', 'dashboard');
?>
<h3 class="fw-bold mb-4">Dashboard admin <span class="text-muted fs-6">TA <?= e(TAHUN_AJARAN) ?></span></h3>
<div class="row g-3 mb-4">
<?php foreach (['Total pendaftar' => $c['total'], 'Menunggu' => $c['Menunggu'], 'Diverifikasi' => $c['Diverifikasi'], 'Ditolak' => $c['Ditolak'], 'Data siswa' => $jmlSiswa] as $k => $v): ?>
  <div class="col-6 col-lg"><div class="stat <?= $k === 'Menunggu' || $k === 'Ditolak' ? 'sky' : '' ?>"><span class="text-muted small"><?= $k ?></span><b><?= $v ?></b></div></div>
<?php endforeach; ?></div>
<div class="panel p-0 table-responsive"><table class="table mb-0"><thead><tr><th>Kode</th><th>Jurusan</th><th>Jumlah pendaftar</th></tr></thead><tbody>
<?php foreach ($jur as $j) echo '<tr><td>' . e($j['kode_jurusan']) . '</td><td>' . e($j['nama_jurusan']) . '</td><td>' . (int)$j['pendaftar'] . '</td></tr>'; ?></tbody></table></div>
<?php layout_end();
