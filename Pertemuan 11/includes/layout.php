<?php
require_once __DIR__ . '/functions.php';
function layout_start($title, $active = '', $public = false) {
    $u = user(); $f = flash(); $menu = [];
    if ($u && $u['role'] === 'admin') $menu = ['dashboard' => 'Dashboard', 'siswa' => 'Data siswa', 'jurusan' => 'Data jurusan', 'pendaftaran' => 'Pendaftaran & verifikasi', 'laporan' => 'Laporan', 'pengaturan' => 'Pengaturan'];
        $dir = $u ? $u['role'] : '';
    if ($u && $u['role'] === 'siswa') $menu = ['dashboard' => 'Dashboard', 'biodata' => 'Biodata', 'pendaftaran' => 'Pendaftaran', 'berkas' => 'Upload berkas', 'status' => 'Status & pengumuman'];
        if ($public) { $menu = []; $GLOBALS['pub'] = true; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> | SMK Nusantara</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md bg-white border-bottom sticky-top">
  <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php"><img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo SMK Nusantara" class="brand-logo" height="36" style="height:36px;width:auto;max-width:48px;object-fit:contain" onerror="this.outerHTML='<span class=&quot;logo-dot&quot;></span>'">SMK Nusantara</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nv"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nv">
      <ul class="navbar-nav ms-auto align-items-md-center gap-md-2">
      <?php if ($u): ?>
        <li class="nav-item text-muted small me-md-2"><?= e($u['nama']) ?></li>
        <li class="nav-item"><a class="btn btn-outline-primary btn-sm" href="<?= BASE_URL ?>/logout.php">Keluar</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#jurusan">Jurusan</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#syarat">Persyaratan</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#jadwal">Jadwal</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login.php">Masuk</a></li>
        <li class="nav-item"><a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/register.php">Daftar akun</a></li>
      <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<?php if ($menu): ?>
<div class="app-shell">
  <aside class="sidebar">
    <?php foreach ($menu as $k => $v): ?>
      <a href="<?= BASE_URL ?>/<?= $dir ?>/<?= $k ?>.php" class="<?= $k === $active ? 'active' : '' ?>"><?= e($v) ?></a>
    <?php endforeach; ?>
  </aside>
  <main class="content">
<?php endif;
    if ($f) echo '<div class="alert alert-' . e($f[0]) . ' alert-dismissible fade show">' . e($f[1]) . '<button class="btn-close" data-bs-dismiss="alert"></button></div>';
}
function layout_end() {
    if (user() && empty($GLOBALS['pub'])) echo '</main></div>';
    $b = BASE_URL; $h = fn($x) => htmlspecialchars($x, ENT_QUOTES, 'UTF-8');
    echo '<style>
    .site-footer{background:#123578;color:#fff;padding:52px 0 0;margin-top:48px}
    .site-footer h6{color:#fff;font-weight:700;margin-bottom:14px}
    .site-footer ul{list-style:none;padding:0;margin:0}
    .site-footer li{margin-bottom:8px;color:#cfe6f7;font-size:.95rem}
    .site-footer a{color:#cfe6f7;text-decoration:none}
    .site-footer a:hover{color:#fff;text-decoration:underline}
    .site-footer .f-brand{display:flex;align-items:center;gap:12px;margin-bottom:14px}
    .site-footer .f-logo{width:48px;height:48px;flex:0 0 48px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;padding:6px}
    .site-footer .f-logo img{max-width:100%;max-height:100%;width:auto;height:auto;object-fit:contain}
    .site-footer .f-name{font-size:1.25rem;font-weight:800;color:#fff;white-space:nowrap}
    .site-footer .f-desc{color:#cfe6f7;font-size:.95rem;max-width:380px;margin:0}
    .site-footer .f-bottom{border-top:1px solid rgba(255,255,255,.2);margin-top:36px;padding:18px 0;text-align:center;font-size:.88rem;color:#a9c9e6}
    </style>
    <footer class="site-footer"><div class="container"><div class="row g-4">
      <div class="col-lg-5">
        <div class="f-brand"><span class="f-logo"><img src="' . $b . '/assets/images/logo.png" alt="Logo SMK Nusantara" onerror="this.parentNode.style.display=\'none\'"></span><span class="f-name">SMK Nusantara</span></div>
        <p class="f-desc">Sekolah Menengah Kejuruan yang menyiapkan lulusan siap kerja, siap kuliah, dan siap berwirausaha.</p>
      </div>
      <div class="col-6 col-lg-3"><h6>Tautan</h6><ul>
        <li><a href="' . $b . '/index.php#jurusan">Program keahlian</a></li>
        <li><a href="' . $b . '/index.php#syarat">Persyaratan</a></li>
        <li><a href="' . $b . '/index.php#jadwal">Jadwal PPDB</a></li>
        <li><a href="' . $b . '/login.php">Masuk</a></li></ul></div>
      <div class="col-6 col-lg-4"><h6>Kontak</h6><ul>
        <li>' . $h(SCHOOL_ALAMAT) . '</li><li>' . $h(SCHOOL_TELP) . '</li><li>' . $h(SCHOOL_EMAIL) . '</li></ul></div>
    </div><div class="f-bottom">&copy; ' . date('Y') . ' SMK Nusantara. Hak cipta dilindungi.</div></div></footer>';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}