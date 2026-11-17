<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
$ta = setting('tahun_ajaran');
$c = ['total' => 0] + array_fill_keys(STATUS_LIST, 0);
foreach (q('SELECT status, COUNT(*) n FROM pendaftaran WHERE tahun_ajaran=? GROUP BY status', [$ta]) as $r) { $c[$r['status']] = (int)$r['n']; $c['total'] += (int)$r['n']; }
$jur = q("SELECT j.*, (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_jurusan=j.id_jurusan AND p.tahun_ajaran=?) pendaftar,
          (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_jurusan=j.id_jurusan AND p.tahun_ajaran=? AND p.status='Diterima') diterima
          FROM jurusan j ORDER BY kode_jurusan", [$ta, $ta])->fetchAll();
layout_start('Dashboard admin', 'dashboard');
?>
<h3 class="fw-bold mb-4">Dashboard admin <span class="text-muted fs-6">TA <?= e($ta) ?></span></h3>
<div class="row g-3 mb-4">
<?php foreach (['Total pendaftar' => $c['total'], 'Menunggu' => $c['Menunggu'], 'Diverifikasi' => $c['Diverifikasi'], 'Diterima' => $c['Diterima'], 'Ditolak' => $c['Ditolak']] as $k => $v): ?>
  <div class="col-6 col-lg"><div class="stat <?= $k === 'Menunggu' || $k === 'Ditolak' ? 'sky' : '' ?>"><span class="text-muted small"><?= $k ?></span><b><?= $v ?></b></div></div>
<?php endforeach; ?></div>
<div class="panel"><h5 class="fw-bold mb-3">Kuota per jurusan</h5>
<?php foreach ($jur as $j): $pc = $j['kuota'] ? min(100, $j['diterima'] / $j['kuota'] * 100) : 0; ?>
  <p class="mb-1 mt-3"><b><?= e($j['kode_jurusan']) ?></b> · <?= $j['diterima'] ?> dari <?= $j['kuota'] ?> diterima · <?= $j['pendaftar'] ?> pendaftar</p>
  <div class="progress"><div class="progress-bar" style="width:<?= $pc ?>%"></div></div>
<?php endforeach; ?></div>
<?php layout_end();
