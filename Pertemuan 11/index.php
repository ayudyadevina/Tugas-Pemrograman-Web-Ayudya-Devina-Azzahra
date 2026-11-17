<?php
require_once __DIR__ . '/includes/layout.php';
$jur = q('SELECT * FROM jurusan WHERE aktif=1 ORDER BY kode_jurusan')->fetchAll();
layout_start('Beranda', '', true);
?>
<style>
.hero{position:relative;overflow:hidden;min-height:540px;display:flex;align-items:center;padding:70px 0 110px}
.hero .container{position:relative;z-index:2;width:100%}
.hero .hero-photo{position:absolute;top:0;right:0;bottom:0;width:52%;z-index:1;aspect-ratio:auto;border:0;border-radius:0;box-shadow:none;background:none;overflow:visible;
  -webkit-mask-image:linear-gradient(to right,transparent 0,rgba(0,0,0,.6) 18%,#000 42%),linear-gradient(to bottom,transparent 0,#000 20%,#000 80%,transparent 100%);
  -webkit-mask-composite:source-in;
  mask-image:linear-gradient(to right,transparent 0,rgba(0,0,0,.6) 18%,#000 42%),linear-gradient(to bottom,transparent 0,#000 20%,#000 80%,transparent 100%);
  mask-composite:intersect}
.hero .hero-photo img{position:static;display:block;width:100%;height:100%;object-fit:cover;object-position:center;z-index:auto}
.hero .hero-photo::after{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(26,69,160,.30),rgba(26,69,160,0) 70%)}
@media(max-width:991px){
  .hero .hero-photo{width:100%;opacity:.22;-webkit-mask-image:none;mask-image:none}
}
</style>

<section class="hero">
  <div class="hero-photo">
    <img src="<?= BASE_URL ?>/assets/images/hero.jpg" alt="Gedung SMK Nusantara" onerror="this.remove()">
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <span class="hero-tag">PPDB <?= e(setting('tahun_ajaran')) ?></span>
        <h1 class="mt-3">Mulai masa depan cerahmu di SMK Nusantara</h1>
        <p class="lead mb-4">Pendaftaran dilakukan online. Isi biodata, pilih jurusan, unggah berkas, lalu pantau hasilnya dari akunmu.</p>
        <a class="btn btn-light btn-lg me-2" href="<?= BASE_URL ?>/register.php">Buat akun</a>
        <a class="btn btn-outline-light btn-lg" href="<?= BASE_URL ?>/login.php">Masuk</a>
      </div>
    </div>
  </div>
</section>
<section class="container" id="jadwal"><div class="row g-3 sched">
  <?php foreach ([['Pendaftaran dibuka', 'buka'], ['Pendaftaran ditutup', 'tutup'], ['Pengumuman hasil', 'tgl_pengumuman']] as $x): ?>
  <div class="col-md-4"><div class="panel shadow-sm"><span class="text-muted small d-block"><?= $x[0] ?></span><b class="fs-5"><?= tgl(setting($x[1])) ?></b></div></div>
  <?php endforeach; ?>
</div></section>
<section class="py-5" id="jurusan"><div class="container">
  <h2 class="fw-bold mb-4">Program keahlian</h2>
  <div class="row g-3"><?php foreach ($jur as $j): ?>
    <div class="col-md-6 col-lg-4"><div class="jur-card"><div class="bar"></div><div class="bd">
      <h5 class="fw-bold"><?= e($j['nama_jurusan']) ?></h5><p class="text-muted"><?= e($j['deskripsi']) ?></p>
      <b>Kuota <?= (int)$j['kuota'] ?> siswa</b></div></div></div>
  <?php endforeach; ?></div>
</div></section>
<section class="py-5 tint" id="syarat"><div class="container">
  <h2 class="fw-bold mb-3">Persyaratan dokumen</h2>
  <ul><?php foreach (JENIS_BERKAS as $v) echo '<li>' . e($v) . '</li>'; ?></ul>
  <p class="text-muted">Format JPG, JPEG, PNG, atau PDF dengan ukuran maksimal 2 MB per file.</p>
</div></section>
<?php layout_end();