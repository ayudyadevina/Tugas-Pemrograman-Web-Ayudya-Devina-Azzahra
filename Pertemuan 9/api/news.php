<?php
// Endpoint JSON (opsional): /api/news.php?page=1&category=slug&q=kata
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
$page = max(1, (int)($_GET['page'] ?? 1)); $w = '1'; $p = [];
if (!empty($_GET['category'])) { $w .= ' AND c.slug=?'; $p[] = $_GET['category']; }
if (!empty($_GET['q'])) { $w .= ' AND (n.title LIKE ? OR n.content LIKE ?)'; $p[] = $p[] = '%' . $_GET['q'] . '%'; }
$st = db()->prepare(NEWS_SELECT . " WHERE n.status='published' AND n.published_at<=NOW() AND $w ORDER BY n.published_at DESC LIMIT " . PER_PAGE . " OFFSET " . (($page - 1) * PER_PAGE));
$st->execute($p);
echo json_encode(['page' => $page, 'data' => array_map(fn($n) => [
    'title' => $n['title'], 'slug' => $n['slug'], 'excerpt' => excerpt($n['content']), 'category' => $n['cat_name'],
    'author' => $n['author'], 'image' => $n['image'] ? img_url($n['image']) : null, 'published_at' => $n['published_at'],
], $st->fetchAll())], JSON_UNESCAPED_UNICODE);
