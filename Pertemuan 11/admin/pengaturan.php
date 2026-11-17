<?php
require_once __DIR__ . '/../includes/layout.php'; need('admin');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = ['tahun_ajaran' => trim($_POST['tahun_ajaran'] ?? ''), 'buka' => $_POST['buka'] ?? '', 'tutup' => $_POST['tutup'] ?? '',
          'tgl_pengumuman' => $_POST['tgl_pengumuman'] ?? '', 'pengumuman_aktif' => isset($_POST['pengumuman_aktif']) ? '1' : '0'];
    if (!preg_match('/^\d{4}\/\d{4}$/', $d['tahun_ajaran']) || $d['tutup'] < $d['buka']) flash('danger', 'Tahun ajaran harus berformat 2026/2027 dan tanggal tutup tidak boleh sebelum tanggal buka.');
    else { foreach ($d as $k => $v) q('UPDATE pengaturan SET nilai=? WHERE kunci=?', [$v, $k]); flash('success', 'Pengaturan disimpan.'); }
    redirect('admin/pengaturan.php');
}
layout_start('Pengaturan', 'pengaturan');
?>
<h3 class="fw-bold mb-3">Pengaturan PPDB</h3>
<form method="post" class="panel"><?= csrf_field() ?><div class="row g-3">
  <div class="col-md-6"><label class="form-label">Tahun ajaran</label><input class="form-control" name="tahun_ajaran" value="<?= e(setting('tahun_ajaran')) ?>" required></div>
  <div class="col-md-6"><label class="form-label">Tanggal pengumuman</label><input class="form-control" type="date" name="tgl_pengumuman" value="<?= e(setting('tgl_pengumuman')) ?>"></div>
  <div class="col-md-6"><label class="form-label">Pendaftaran dibuka</label><input class="form-control" type="date" name="buka" value="<?= e(setting('buka')) ?>"></div>
  <div class="col-md-6"><label class="form-label">Pendaftaran ditutup</label><input class="form-control" type="date" name="tutup" value="<?= e(setting('tutup')) ?>"></div>
</div>
<div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="pengumuman_aktif" id="pa" <?= setting('pengumuman_aktif') ? 'checked' : '' ?>><label class="form-check-label" for="pa">Tampilkan hasil seleksi ke siswa</label></div>
<button class="btn btn-primary mt-3">Simpan pengaturan</button></form>
<?php layout_end();
