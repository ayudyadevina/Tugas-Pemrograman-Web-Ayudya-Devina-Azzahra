<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
layout_start('Status pendaftaran', 'status');
echo '<h3 class="fw-bold mb-3">Status pendaftaran</h3>';
if (!$p) { echo '<div class="panel">Belum ada pendaftaran.</div>'; layout_end(); exit; }
?>
<div class="panel"><h5 class="fw-bold"><?= e($p['no_pendaftaran']) ?></h5>
<p class="mb-1">Jurusan: <?= e($p['nama_jurusan']) ?></p>
<p class="mb-1">Tanggal daftar: <?= tgl($p['tanggal_daftar']) ?></p>
<p>Status: <?= badge($p['status']) ?></p>
<?php if ($p['catatan']) echo '<p class="mb-0">Catatan admin: ' . e($p['catatan']) . '</p>'; ?>
<?php if ($p['status'] === 'Menunggu') echo '<p class="text-muted small mt-2 mb-0">Pendaftaran sedang menunggu pemeriksaan admin.</p>'; ?></div>
<?php layout_end();
