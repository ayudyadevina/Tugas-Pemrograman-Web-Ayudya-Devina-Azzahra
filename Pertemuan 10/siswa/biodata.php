<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
$locked = $p && $p['status'] !== 'Menunggu';
$err = []; $v = $s ?: array_fill_keys(['nisn','nama_lengkap','jenis_kelamin','tempat_lahir','tanggal_lahir','alamat','asal_sekolah','no_hp'], '');
if (!$s) $v['nama_lengkap'] = user()['nama'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($locked) { flash('warning', 'Biodata terkunci selama proses verifikasi.'); redirect('siswa/biodata.php'); }
    foreach ($v as $k => $_) $v[$k] = trim($_POST[$k] ?? '');
    if (!preg_match('/^\d{10}$/', $v['nisn'])) $err[] = 'NISN harus 10 digit angka.';
    if ($v['nama_lengkap'] === '') $err[] = 'Nama lengkap wajib diisi.';
    if (!in_array($v['jenis_kelamin'], ['L', 'P'], true)) $err[] = 'Pilih jenis kelamin.';
    if ($v['tempat_lahir'] === '' || !strtotime($v['tanggal_lahir'])) $err[] = 'Tempat dan tanggal lahir wajib diisi dengan benar.';
    if ($v['alamat'] === '' || $v['asal_sekolah'] === '') $err[] = 'Alamat dan asal sekolah wajib diisi.';
    if (!preg_match('/^08\d{8,12}$/', $v['no_hp'])) $err[] = 'Nomor HP harus diawali 08 (10-14 digit).';
    if (!$err && q('SELECT 1 FROM siswa WHERE nisn=? AND id_user<>?', [$v['nisn'], user()['id']])->fetch()) $err[] = 'NISN sudah terdaftar pada akun lain.';
    if (!$err) {
        $d = [$v['nisn'],$v['nama_lengkap'],$v['jenis_kelamin'],$v['tempat_lahir'],$v['tanggal_lahir'],$v['alamat'],$v['asal_sekolah'],$v['no_hp']];
        if ($s) q('UPDATE siswa SET nisn=?,nama_lengkap=?,jenis_kelamin=?,tempat_lahir=?,tanggal_lahir=?,alamat=?,asal_sekolah=?,no_hp=? WHERE id_user=?', [...$d, user()['id']]);
        else q('INSERT INTO siswa (nisn,nama_lengkap,jenis_kelamin,tempat_lahir,tanggal_lahir,alamat,asal_sekolah,no_hp,id_user) VALUES (?,?,?,?,?,?,?,?,?)', [...$d, user()['id']]);
        flash('success', 'Biodata tersimpan.'); redirect('siswa/pendaftaran.php');
    }
}
$dis = $locked ? 'disabled' : '';
layout_start('Biodata', 'biodata');
?>
<h3 class="fw-bold mb-3">Biodata siswa</h3>
<?php if ($locked) echo '<div class="alert alert-info">Biodata terkunci selama proses verifikasi.</div>';
foreach ($err as $x) echo '<div class="alert alert-danger py-2">' . e($x) . '</div>'; ?>
<form method="post" class="panel"><?= csrf_field() ?><div class="row g-3">
  <div class="col-md-6"><label class="form-label">NISN</label><input class="form-control" name="nisn" value="<?= e($v['nisn']) ?>" maxlength="10" <?= $dis ?> required></div>
  <div class="col-md-6"><label class="form-label">Nama lengkap</label><input class="form-control" name="nama_lengkap" value="<?= e($v['nama_lengkap']) ?>" <?= $dis ?> required></div>
  <div class="col-md-6"><label class="form-label">Jenis kelamin</label><select class="form-select" name="jenis_kelamin" <?= $dis ?>>
    <option value="L" <?= $v['jenis_kelamin']==='L'?'selected':'' ?>>Laki-laki</option><option value="P" <?= $v['jenis_kelamin']==='P'?'selected':'' ?>>Perempuan</option></select></div>
  <div class="col-md-6"><label class="form-label">Nomor HP</label><input class="form-control" name="no_hp" value="<?= e($v['no_hp']) ?>" <?= $dis ?> required></div>
  <div class="col-md-6"><label class="form-label">Tempat lahir</label><input class="form-control" name="tempat_lahir" value="<?= e($v['tempat_lahir']) ?>" <?= $dis ?> required></div>
  <div class="col-md-6"><label class="form-label">Tanggal lahir</label><input class="form-control" type="date" name="tanggal_lahir" value="<?= e($v['tanggal_lahir']) ?>" <?= $dis ?> required></div>
  <div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2" <?= $dis ?> required><?= e($v['alamat']) ?></textarea></div>
  <div class="col-12"><label class="form-label">Asal sekolah</label><input class="form-control" name="asal_sekolah" value="<?= e($v['asal_sekolah']) ?>" <?= $dis ?> required></div>
</div><button class="btn btn-primary mt-4" <?= $dis ?>>Simpan biodata</button></form>
<?php layout_end();
