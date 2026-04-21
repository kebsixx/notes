<?php
$host = 'localhost';
$port = '5432';
$dbname = 'kampusdb';
$user = 'mahasiswa';
$password = 'password123';

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $db_conn = new PDO($dsn, $user, $password);
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
