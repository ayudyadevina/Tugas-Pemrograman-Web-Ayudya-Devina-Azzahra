<?php
require_once '_layout.php';
header('Content-Type: application/json; charset=utf-8');
try {
    csrf_check();
    $name = upload_image($_FILES['image'] ?? null);
    if (!$name) throw new Exception('Pilih gambar dulu.');
    echo json_encode(['ok' => true, 'name' => $name]);
} catch (Exception $ex) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $ex->getMessage()]);
}