<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
if (!$s) { flash('warning', 'Lengkapi biodata sebelum mendaftar.'); redirect('siswa/biodata.php'); }
$ta = setting('tahun_ajaran'); $today = date('Y-m-d');
if (!$p && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idj = (int)($_POST['id_jurusan'] ?? 0);
    $ok = q('SELECT 1 FROM jurusan WHERE id_jurusan=? AND aktif=1', [$idj])->fetch();
    if ($today < setting('buka') || $today > setting('tutup')) flash('danger', 'Pendaftaran sedang ditutup.');
    elseif (!$ok) flash('danger', 'Pilih jurusan yang tersedia.');
    else {
        $pdo->beginTransaction();
        try {
            q('INSERT INTO pendaftaran (id_siswa,id_jurusan,tahun_ajaran) VALUES (?,?,?)', [$s['id_siswa'], $idj, $ta]);
            $id = (int)$pdo->lastInsertId();
            q('UPDATE pendaftaran SET no_pendaftaran=? WHERE id_pendaftaran=?', ['PPDB-' . substr($ta, 0, 4) . '-' . str_pad($id, 4, '0', STR_PAD_LEFT), $id]);
            q('INSERT INTO riwayat_status (id_pendaftaran,status_sebelumnya,status_baru,catatan) VALUES (?,NULL,"Menunggu","Pendaftaran dikirim")', [$id]);
            $pdo->commit(); flash('success', 'Pendaftaran terkirim. Lanjutkan dengan mengunggah berkas.'); redirect('siswa/berkas.php');
        } catch (PDOException $e) { $pdo->rollBack(); flash('danger', 'Pendaftaran gagal disimpan. Coba lagi.'); }
    }
    redirect('siswa/pendaftaran.php');
}
$jur = q('SELECT * FROM jurusan WHERE aktif=1 ORDER BY kode_jurusan')->fetchAll();
layout_start('Pendaftaran', 'pendaftaran');
?>
<h3 class="fw-bold mb-3">Pendaftaran</h3>
<?php if ($p): ?>
<div class="panel"><p class="mb-1 text-muted">Kamu sudah mendaftar tahun ajaran <?= e($p['tahun_ajaran']) ?>.</p>
  <h4 class="fw-bold"><?= e($p['no_pendaftaran']) ?></h4><p><?= e($p['nama_jurusan']) ?> <?= badge($p['status']) ?></p>
  <a class="btn btn-primary btn-sm" target="_blank" href="cetak.php">Cetak bukti pendaftaran</a></div>
<?php else: ?>
<form method="post" class="panel"><?= csrf_field() ?>
  <label class="form-label">Program keahlian</label>
  <select class="form-select" name="id_jurusan" required><option value="">Pilih jurusan</option>
  <?php foreach ($jur as $j) echo '<option value="' . $j['id_jurusan'] . '">' . e($j['nama_jurusan']) . ' (kuota ' . (int)$j['kuota'] . ')</option>'; ?></select>
  <button class="btn btn-primary mt-3">Kirim pendaftaran</button>
</form>
<?php endif; layout_end();
