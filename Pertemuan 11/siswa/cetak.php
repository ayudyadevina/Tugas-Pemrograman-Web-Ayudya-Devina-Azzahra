<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
if (!$p) redirect('siswa/pendaftaran.php');
layout_start('Bukti pendaftaran', 'pendaftaran');
?>
<div class="panel" style="max-width:640px"><h4 class="fw-bold">Bukti Pendaftaran PPDB SMK Nusantara</h4>
<p class="text-muted">Tahun ajaran <?= e($p['tahun_ajaran']) ?></p>
<table class="table"><tr><th>No. pendaftaran</th><td><?= e($p['no_pendaftaran']) ?></td></tr>
<tr><th>Nama</th><td><?= e($s['nama_lengkap']) ?></td></tr><tr><th>NISN</th><td><?= e($s['nisn']) ?></td></tr>
<tr><th>Asal sekolah</th><td><?= e($s['asal_sekolah']) ?></td></tr><tr><th>Jurusan</th><td><?= e($p['nama_jurusan']) ?></td></tr>
<tr><th>Tanggal daftar</th><td><?= tgl($p['tanggal_daftar']) ?></td></tr></table>
<button class="btn btn-primary no-print" onclick="window.print()">Cetak</button></div>
<?php layout_end();
