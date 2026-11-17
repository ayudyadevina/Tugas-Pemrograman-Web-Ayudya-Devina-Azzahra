<?php
// Koneksi database memakai PDO (prepared statement + mode exception)
const DB_HOST = 'localhost';
const DB_NAME = 'db_ppdb_smk';
const DB_USER = 'root';
const DB_PASS = '';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal. Periksa config/database.php dan pastikan MySQL aktif.');
}
