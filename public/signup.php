<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$authController = new \AuthController($db_conn);

$errorMessage = '';
$successMessage = '';
$signupUsername = '';
$csrfToken = (string) $_SESSION['csrf_token'];

if (isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0) {
    header('Location: index.php');
    exit;
}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    $authController->signup();
}

// Render the view
require_once __DIR__ . '/../views/auth/signup.php';
