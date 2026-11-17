<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
if (!$p) { flash('warning', 'Kirim pendaftaran terlebih dahulu untuk mengunggah berkas.'); redirect('siswa/pendaftaran.php'); }
$locked = $p['status'] !== 'Menunggu';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis'] ?? ''; $f = $_FILES['file'] ?? null;
    $ext = $f ? strtolower(pathinfo($f['name'], PATHINFO_EXTENSION)) : '';
    $mimeOk = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'pdf' => 'application/pdf'];
    if ($locked) flash('warning', 'Berkas terkunci selama proses verifikasi.');
    elseif (!isset(JENIS_BERKAS[$jenis]) || !$f || $f['error'] !== UPLOAD_ERR_OK) flash('danger', 'Pilih file yang akan diunggah.');
    elseif ($f['size'] > 2 * 1024 * 1024) flash('danger', 'Ukuran file melebihi 2 MB.');
    elseif (!isset($mimeOk[$ext]) || (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']) !== $mimeOk[$ext]) flash('danger', 'Format harus JPG, JPEG, PNG, atau PDF.');
    else {
        $nama = bin2hex(random_bytes(12)) . '.' . $ext;
        if (move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $nama)) {
            $old = q('SELECT nama_file FROM berkas WHERE id_pendaftaran=? AND jenis_berkas=?', [$p['id_pendaftaran'], $jenis])->fetchColumn();
            if ($old) @unlink(__DIR__ . '/../uploads/' . basename($old));
            q('REPLACE INTO berkas (id_pendaftaran,jenis_berkas,nama_file,nama_asli) VALUES (?,?,?,?)', [$p['id_pendaftaran'], $jenis, $nama, substr($f['name'], 0, 150)]);
            flash('success', 'Berkas diunggah.');
        } else flash('danger', 'Berkas gagal disimpan di server.');
    }
    redirect('siswa/berkas.php');
}
$b = []; foreach (q('SELECT * FROM berkas WHERE id_pendaftaran=?', [$p['id_pendaftaran']]) as $r) $b[$r['jenis_berkas']] = $r;
layout_start('Upload berkas', 'berkas');
?>
<h3 class="fw-bold mb-3">Upload berkas</h3>
<?php if ($locked) echo '<div class="alert alert-info">Berkas terkunci selama proses verifikasi.</div>'; ?>
<div class="row g-3"><?php foreach (JENIS_BERKAS as $k => $label): ?>
  <div class="col-md-6"><form method="post" enctype="multipart/form-data" class="panel"><?= csrf_field() ?>
    <input type="hidden" name="jenis" value="<?= $k ?>">
    <h6 class="fw-bold"><?= e($label) ?></h6>
    <p class="small text-muted"><?= isset($b[$k]) ? 'Terunggah: <a target="_blank" href="../file.php?id=' . $b[$k]['id_berkas'] . '">' . e($b[$k]['nama_asli']) . '</a>' : 'Belum diunggah' ?></p>
    <input class="form-control mb-2" type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" <?= $locked ? 'disabled' : '' ?>>
    <button class="btn btn-primary btn-sm" <?= $locked ? 'disabled' : '' ?>>Unggah</button></form></div>
<?php endforeach; ?></div>
<p class="text-muted small mt-3">JPG, JPEG, PNG, atau PDF. Maksimal 2 MB per file.</p>
<?php layout_end();
