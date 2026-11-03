<?php
// ====== PENGATURAN — sesuaikan dengan lingkungan kamu ======
define('DB_HOST', 'localhost');
define('DB_NAME', 'news_portal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', '/newsportal');   // nama folder proyek di htdocs; '' jika di root
define('SITE_NAME', 'Warta');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_IMG_SIZE', 2 * 1024 * 1024); // 2 MB
define('PER_PAGE', 9);

date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
