<?php
require_once __DIR__ . '/includes/functions.php';
$u = user(); if (!$u) redirect('login.php');
$b = q('SELECT b.*, s.id_user FROM berkas b JOIN pendaftaran p USING(id_pendaftaran) JOIN siswa s USING(id_siswa) WHERE b.id_berkas=?', [(int)($_GET['id'] ?? 0)])->fetch();
if (!$b || ($u['role'] !== 'admin' && $b['id_user'] != $u['id'])) { http_response_code(403); die('Akses ditolak.'); }
$path = __DIR__ . '/uploads/' . basename($b['nama_file']);
if (!is_file($path)) { http_response_code(404); die('File tidak ditemukan.'); }
header('Content-Type: ' . mime_content_type($path));
header('Content-Disposition: inline; filename="' . basename($b['nama_asli']) . '"');
header('X-Content-Type-Options: nosniff');
readfile($path);
