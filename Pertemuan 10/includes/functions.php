<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
// Sesuaikan dengan nama folder proyek di htdocs
define('BASE_URL', '/ppdb_smk_awal');
// Pengaturan PPDB (versi dasar: masih berupa konstanta, belum bisa diubah dari dashboard)
const TAHUN_AJARAN = '2026/2027';
const JADWAL = ['Pendaftaran dibuka' => '2026-10-01', 'Pendaftaran ditutup' => '2026-12-15', 'Pengumuman hasil' => '2026-12-20'];
// Data kontak sekolah (tampil di footer), ubah sesuai data sekolah
const SCHOOL_ALAMAT = 'Jl. Pendidikan No. 123, Kota Anda';
const SCHOOL_TELP = '(021) 1234 5678';
const SCHOOL_EMAIL = 'info@smknusantara.sch.id';
const STATUS_LIST = ['Menunggu', 'Diverifikasi', 'Ditolak'];

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect($p) { header('Location: ' . BASE_URL . '/' . $p); exit; }
function flash($t = null, $m = null) {
    if ($t) { $_SESSION['flash'] = [$t, $m]; return null; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function csrf() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf() . '">'; }
function csrf_check() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); die('Sesi tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}
function user() { return $_SESSION['user'] ?? null; }
function need($role) {            // otorisasi berbasis role
    $u = user();
    if (!$u) redirect('login.php');
    if ($u['role'] !== $role) redirect($u['role'] === 'admin' ? 'admin/dashboard.php' : 'siswa/dashboard.php');
    csrf_check();
}
function q($sql, $p = []) { global $pdo; $s = $pdo->prepare($sql); $s->execute($p); return $s; }
function tgl($d) { return $d ? date('d-m-Y', strtotime($d)) : '-'; }
function badge($s) {
    $c = ['Menunggu' => 'warning text-dark', 'Diverifikasi' => 'info text-dark', 'Ditolak' => 'danger'][$s] ?? 'secondary';
    return "<span class='badge rounded-pill bg-$c'>" . e($s) . '</span>';
}
function siswa_saya() { return q('SELECT * FROM siswa WHERE id_user=?', [user()['id']])->fetch(); }
function pendaftaran_saya($idSiswa) {
    return q('SELECT p.*, j.nama_jurusan, j.kode_jurusan FROM pendaftaran p JOIN jurusan j USING(id_jurusan)
              WHERE p.id_siswa=? AND p.tahun_ajaran=?', [$idSiswa, TAHUN_AJARAN])->fetch();
}
