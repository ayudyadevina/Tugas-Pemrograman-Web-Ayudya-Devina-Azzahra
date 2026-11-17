<?php
require_once __DIR__ . '/includes/layout.php';
$jur = q('SELECT * FROM jurusan WHERE aktif=1 ORDER BY kode_jurusan')->fetchAll();
layout_start('Beranda', '', true);
?>
<section class="hero">
  <div class="hero-photo"><img src="<?= BASE_URL ?>/assets/images/hero.jpg" alt="Gedung SMK Nusantara" onerror="this.remove()"></div>
  <div class="container"><div class="row"><div class="col-lg-6">
    <span class="hero-tag">PPDB <?= e(TAHUN_AJARAN) ?></span>
    <h1 class="mt-3">Mulai masa depan cerahmu di SMK Nusantara</h1>
    <p class="lead mb-4">Pendaftaran dilakukan online. Isi biodata, pilih jurusan, lalu pantau status pendaftaranmu dari akun.</p>
    <a class="btn btn-light btn-lg me-2" href="<?= BASE_URL ?>/register.php">Buat akun</a>
    <a class="btn btn-outline-light btn-lg" href="<?= BASE_URL ?>/login.php">Masuk</a>
  </div></div></div>
</section>
<section class="container" id="jadwal"><div class="row g-3 sched">
  <?php foreach (JADWAL as $label => $tanggal): ?>
  <div class="col-md-4"><div class="panel shadow-sm"><span class="text-muted small d-block"><?= e($label) ?></span><b class="fs-5"><?= tgl($tanggal) ?></b></div></div>
  <?php endforeach; ?>
</div></section>
<section class="py-5" id="jurusan"><div class="container">
  <h2 class="fw-bold mb-4">Program keahlian</h2>
  <div class="row g-3"><?php foreach ($jur as $j): ?>
    <div class="col-md-6 col-lg-4"><div class="jur-card"><div class="bar"></div><div class="bd">
      <h5 class="fw-bold"><?= e($j['nama_jurusan']) ?></h5><p class="text-muted mb-0"><?= e($j['deskripsi']) ?></p></div></div></div>
  <?php endforeach; ?></div>
</div></section>
<section class="py-5 tint" id="syarat"><div class="container">
  <h2 class="fw-bold mb-3">Persyaratan pendaftaran</h2>
  <ul><li>Lulusan SMP/MTs atau sederajat</li><li>Memiliki NISN (10 digit)</li><li>Memiliki nomor HP aktif</li><li>Mengisi biodata dengan lengkap dan benar</li></ul>
</div></section>
<?php layout_end();
