<?php
require_once __DIR__ . '/../includes/layout.php'; need('siswa');
$s = siswa_saya(); $p = $s ? pendaftaran_saya($s['id_siswa']) : null;
layout_start('Status & pengumuman', 'status');
echo '<h3 class="fw-bold mb-3">Status & pengumuman</h3>';
if (!$p) { echo '<div class="panel">Belum ada pendaftaran.</div>'; layout_end(); exit; }
$umum = (bool)setting('pengumuman_aktif'); $final = in_array($p['status'], ['Diterima', 'Ditolak'], true);
$riw = q('SELECT * FROM riwayat_status WHERE id_pendaftaran=? ORDER BY id_riwayat DESC', [$p['id_pendaftaran']])->fetchAll();
?>
<div class="panel mb-4"><h5 class="fw-bold"><?= e($p['no_pendaftaran']) ?></h5>
<p>Status: <?= ($final && !$umum) ? '<span class="badge rounded-pill bg-info text-dark">Sedang diseleksi</span>' : badge($p['status']) ?></p>
<?php if ($final && $umum): ?>
  <p class="fw-bold mb-0"><?= $p['status'] === 'Diterima' ? 'Selamat! Kamu diterima di ' . e($p['nama_jurusan']) . '.' : 'Mohon maaf, kamu belum diterima.' ?></p>
<?php elseif ($final): ?><p class="text-muted mb-0">Hasil seleksi diumumkan <?= tgl(setting('tgl_pengumuman')) ?>.</p><?php endif; ?>
<?php if ($p['catatan'] && (!$final || $umum)) echo '<p class="mt-2 mb-0">Catatan admin: ' . e($p['catatan']) . '</p>'; ?></div>
<h5 class="fw-bold">Riwayat</h5>
<?php foreach ($riw as $r) {
    if (in_array($r['status_baru'], ['Diterima', 'Ditolak'], true) && !$umum) continue;
    echo '<div class="timeline-item"><b>' . e($r['status_baru']) . '</b> <span class="text-muted small">' . tgl($r['tanggal_perubahan']) . '</span><br>' . e($r['catatan']) . '</div>';
} layout_end();
