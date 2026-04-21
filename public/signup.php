<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

session_start();

$authController = new AuthController($db_conn);

$errorMessage = '';
$successMessage = '';
$signupUsername = '';

if (isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->signup();
}

// Render the view
require_once __DIR__ . '/../views/auth/signup.php';
