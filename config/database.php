<?php

require_once __DIR__ . '/environment.php';

loadEnvironment(dirname(__DIR__) . '/.env');

$host = env('DB_HOST', 'localhost');
$port = env('DB_PORT', '5432');
$dbname = env('DB_NAME', 'kampusdb');
$user = env('DB_USER', 'postgres');
$password = env('DB_PASS', '');

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $db_conn = new PDO($dsn, $user, $password);
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
