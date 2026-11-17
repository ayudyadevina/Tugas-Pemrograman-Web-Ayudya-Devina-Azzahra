<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
layout_start('Dashboard siswa', 'dashboard');
$next = !$s ? ['biodata', 'Lengkapi biodata kamu terlebih dahulu.'] : (!$p ? ['pendaftaran', 'Biodata tersimpan. Pilih jurusan dan kirim pendaftaran.'] :
        ($p['status'] === 'Menunggu' ? ['berkas', 'Pendaftaran terkirim. Unggah semua berkas agar admin dapat memverifikasi.'] : ['status', 'Pantau hasil seleksi di halaman status.']));
?>
<h3 class="fw-bold mb-4">Halo, <?= e(user()['nama']) ?></h3>
<div class="row g-3 mb-4">
  <div class="col-md-4"><div class="stat"><span class="text-muted small">Nomor pendaftaran</span><b class="fs-5 mt-1"><?= $p ? e($p['no_pendaftaran']) : 'Belum ada' ?></b></div></div>
  <div class="col-md-4"><div class="stat sky"><span class="text-muted small">Status</span><b class="fs-5 mt-1"><?= $p ? e($p['status']) : 'Belum mendaftar' ?></b></div></div>
  <div class="col-md-4"><div class="stat"><span class="text-muted small">Jurusan pilihan</span><b class="fs-5 mt-1"><?= $p ? e($p['nama_jurusan']) : '-' ?></b></div></div>
</div>
<div class="panel mb-3"><h5 class="fw-bold">Langkah berikutnya</h5><p><?= e($next[1]) ?></p><a class="btn btn-primary btn-sm" href="<?= $next[0] ?>.php">Lanjutkan</a></div>
<div class="panel"><h5 class="fw-bold">Jadwal PPDB <?= e(setting('tahun_ajaran')) ?></h5>
  <p class="mb-1">Dibuka: <b><?= tgl(setting('buka')) ?></b> · Ditutup: <b><?= tgl(setting('tutup')) ?></b></p>
  <p class="mb-0">Pengumuman hasil: <b><?= tgl(setting('tgl_pengumuman')) ?></b></p></div>
<?php layout_end();
