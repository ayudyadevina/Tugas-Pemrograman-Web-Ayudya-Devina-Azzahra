<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
// Sesuaikan dengan nama folder proyek di htdocs
define('BASE_URL', '/ppdb_smk');
const SCHOOL_ALAMAT = 'Jl. Pendidikan No. 123, Surabaya';
const SCHOOL_TELP = '(021) 1234 5678';
const SCHOOL_EMAIL = 'info@smknusantara.sch.id';
const JENIS_BERKAS = ['KK' => 'Kartu Keluarga (KK)', 'AKTA' => 'Akta kelahiran', 'RAPOR' => 'Rapor', 'FOTO' => 'Pas foto'];
const STATUS_LIST = ['Menunggu', 'Diverifikasi', 'Diterima', 'Ditolak'];

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
function setting($k) { return q('SELECT nilai FROM pengaturan WHERE kunci=?', [$k])->fetchColumn(); }
function tgl($d) { return $d ? date('d-m-Y', strtotime($d)) : '-'; }
function badge($s) {
    $c = ['Menunggu' => 'warning text-dark', 'Diverifikasi' => 'info text-dark', 'Diterima' => 'success', 'Ditolak' => 'danger'][$s] ?? 'secondary';
    return "<span class='badge rounded-pill bg-$c'>" . e($s) . '</span>';
}
function siswa_saya() { return q('SELECT * FROM siswa WHERE id_user=?', [user()['id']])->fetch(); }
function pendaftaran_saya($idSiswa) {
    return q('SELECT p.*, j.nama_jurusan, j.kode_jurusan FROM pendaftaran p JOIN jurusan j USING(id_jurusan)
              WHERE p.id_siswa=? AND p.tahun_ajaran=?', [$idSiswa, setting('tahun_ajaran')])->fetch();
}
function jml_diterima($idJurusan) {
    return (int)q("SELECT COUNT(*) FROM pendaftaran WHERE id_jurusan=? AND status='Diterima' AND tahun_ajaran=?",
                  [$idJurusan, setting('tahun_ajaran')])->fetchColumn();
}
// Ubah status + catat riwayat dalam satu transaksi. Mengembalikan pesan error atau null.
function ubah_status($id, $baru, $catatan, $idAdmin) {
    global $pdo;
    if (!in_array($baru, STATUS_LIST, true)) return 'Status tidak valid.';
    $pdo->beginTransaction();
    try {
        $p = q('SELECT * FROM pendaftaran WHERE id_pendaftaran=? FOR UPDATE', [$id])->fetch();
        if (!$p) throw new Exception('Pendaftaran tidak ditemukan.');
        if ($p['status'] === $baru) throw new Exception('Status sudah ' . $baru . '.');
        if ($baru === 'Diterima') {
            if ($p['status'] !== 'Diverifikasi') throw new Exception('Verifikasi pendaftaran terlebih dahulu sebelum menerima.');
            $j = q('SELECT kuota, kode_jurusan FROM jurusan WHERE id_jurusan=?', [$p['id_jurusan']])->fetch();
            if (jml_diterima($p['id_jurusan']) >= $j['kuota']) throw new Exception('Kuota ' . $j['kode_jurusan'] . ' sudah penuh.');
        }
        q('UPDATE pendaftaran SET status=?, catatan=? WHERE id_pendaftaran=?', [$baru, $catatan, $id]);
        q('INSERT INTO riwayat_status (id_pendaftaran,status_sebelumnya,status_baru,catatan,id_admin) VALUES (?,?,?,?,?)',
          [$id, $p['status'], $baru, $catatan, $idAdmin]);
        $pdo->commit();
        return null;
    } catch (Exception $ex) { $pdo->rollBack(); return $ex->getMessage(); }
}
