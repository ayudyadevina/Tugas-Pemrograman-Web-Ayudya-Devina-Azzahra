<?php
require_once __DIR__ . '/db.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function url($p = '') { return BASE_URL . '/' . ltrim($p, '/'); }
function redirect($p) { header('Location: ' . (str_starts_with($p, 'http') ? $p : url($p))); exit; }

function slugify($t) {
    $t = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $t), '-'));
    return $t ?: 'berita';
}
function unique_slug($table, $base, $ignoreId = 0) {
    $slug = $base; $i = 2;
    while (true) {
        $st = db()->prepare("SELECT COUNT(*) FROM $table WHERE slug=? AND id<>?");
        $st->execute([$slug, $ignoreId]);
        if (!$st->fetchColumn()) return $slug;
        $slug = $base . '-' . $i++;
    }
}
function tgl($d, $withTime = false) {
    if (!$d) return '-';
    $b = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $t = strtotime($d);
    return date('j', $t) . ' ' . $b[date('n', $t) - 1] . ' ' . date('Y', $t) . ($withTime ? ', ' . date('H.i', $t) . ' WIB' : '');
}
function excerpt($s, $n = 140) {
    $s = preg_replace('/^!\[.*?\]\(.*?\)\s*$/m', '', $s);
    $s = preg_replace(['/^(##|>)\s+/m', '/\*+|==/'], '', $s);
    $s = trim(preg_replace('/\s+/', ' ', strip_tags($s)));
    return mb_strlen($s) > $n ? mb_substr($s, 0, $n) . '…' : $s;
}
function render_body($text) {
    $inline = function ($s) {
        $s = e($s);
        $s = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $s);
        $s = preg_replace('/\*(.+?)\*/u', '<em>$1</em>', $s);
        return preg_replace('/==(.+?)==/u', '<mark>$1</mark>', $s);
    };
    $out = '';
    foreach (preg_split('/\R/', (string)$text) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        if (preg_match('/^!\[(.*?)\]\(([A-Za-z0-9._-]+\.(?:jpg|png|webp))\)$/i', $line, $m)) {
            $cap = trim($m[1]);
            $out .= '<figure><img src="' . img_url($m[2]) . '" alt="' . e($cap) . '" loading="lazy">'
            . ($cap !== '' ? '<figcaption>' . e($cap) . '</figcaption>' : '') . '</figure>';
            continue;
        }
        if (str_starts_with($line, '## '))     $out .= '<h2>' . $inline(substr($line, 3)) . '</h2>';
        elseif (str_starts_with($line, '> ')) $out .= '<blockquote>' . $inline(substr($line, 2)) . '</blockquote>';
        else                                   $out .= '<p>' . $inline($line) . '</p>';
    }
    return $out;
}
function read_time($s) { return max(1, (int)ceil(str_word_count(strip_tags($s)) / 200)); }

// --- CSRF & flash ---
function csrf() { return $_SESSION['csrf'] ??= bin2hex(random_bytes(16)); }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf() . '">'; }
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Sesi tidak valid. Muat ulang halaman.'); }
}
function flash($msg = null, $type = 'ok') {
    if ($msg !== null) { $_SESSION['flash'] = [$msg, $type]; return; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}

// --- Auth ---
function user() { return $_SESSION['user'] ?? null; }
function require_login($role = null) {
    if (!user()) redirect('admin/login.php');
    if ($role && user()['role'] !== $role) { http_response_code(403); exit('Akses ditolak: khusus ' . e($role) . '.'); }
}

// --- Gambar ---
function img_url($f) { return $f ? url('uploads/' . rawurlencode($f)) : null; }
function upload_image($file, $old = null) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) return $old;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception('Upload gambar gagal.');
    if ($file['size'] > MAX_IMG_SIZE) throw new Exception('Ukuran gambar maksimal 2 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if (!$ext) throw new Exception('Format gambar harus JPG, PNG, atau WEBP.');
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) throw new Exception('Gagal menyimpan gambar.');
    if ($old && is_file(UPLOAD_DIR . $old)) @unlink(UPLOAD_DIR . $old);
    return $name;
}

// --- Query berita ---
const NEWS_SELECT = "SELECT n.*, c.name AS cat_name, c.slug AS cat_slug, u.fullname AS author
  FROM news n JOIN categories c ON c.id=n.category_id JOIN users u ON u.id=n.author_id";
function news_list($where = '1', $params = [], $limit = 10, $offset = 0) {
    $st = db()->prepare(NEWS_SELECT . " WHERE n.status='published' AND n.published_at<=NOW() AND $where
        ORDER BY n.published_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset);
    $st->execute($params); return $st->fetchAll();
}
function news_count($where = '1', $params = []) {
    $st = db()->prepare("SELECT COUNT(*) FROM news n WHERE n.status='published' AND n.published_at<=NOW() AND $where");
    $st->execute($params); return (int)$st->fetchColumn();
}
function categories() { return db()->query("SELECT * FROM categories ORDER BY name")->fetchAll(); }

// --- Komponen tampilan ---
function card($n) {
    $img = img_url($n['image']); $link = url('news.php?slug=' . $n['slug']);
    $h = '<article class="card"><a class="card-img" href="' . $link . '" tabindex="-1" aria-hidden="true">';
    $h .= $img ? '<img src="' . $img . '" alt="" loading="lazy">' : '<span class="ph"></span>';
    $h .= '</a><div class="card-body"><a class="tag" href="' . url('category.php?slug=' . $n['cat_slug']) . '">' . e($n['cat_name']) . '</a>';
    $h .= '<h3><a href="' . $link . '">' . e($n['title']) . '</a></h3>';
    $h .= '<p>' . e(excerpt($n['content'])) . '</p><small>' . tgl($n['published_at']) . ' &middot; ' . e($n['author']) . '</small></div></article>';
    return $h;
}
function pagination($total, $page, $base) {
    $pages = (int)ceil($total / PER_PAGE); if ($pages < 2) return '';
    $sep = str_contains($base, '?') ? '&' : '?';
    $h = '<nav class="pager" aria-label="Halaman">';
    if ($page > 1) $h .= '<a href="' . $base . $sep . 'page=' . ($page - 1) . '">Sebelumnya</a>';
    for ($i = 1; $i <= $pages; $i++) $h .= $i == $page ? "<span aria-current=\"page\">$i</span>" : '<a href="' . $base . $sep . "page=$i\">$i</a>";
    if ($page < $pages) $h .= '<a href="' . $base . $sep . 'page=' . ($page + 1) . '">Berikutnya</a>';
    return $h . '</nav>';
}
