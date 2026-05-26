<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/NoteController.php';

session_start();

$controller = new \NoteController($db_conn);
$authController = new \AuthController($db_conn);

$currentUserId = $_SESSION['user_id'] ?? 0;
$currentUsername = $_SESSION['username'] ?? '';
$errorMessage = '';
$successMessage = '';
$loginUsernameValue = '';
$notes = [];

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    if (isset($_POST['login_username'], $_POST['login_password'])) {
        $authController->login();
    } elseif (isset($_POST['logout'])) {
        $authController->logout();
    } elseif ($currentUserId > 0 && isset($_POST['content'])) {
        $controller->addNote($currentUserId);
    } elseif ($currentUserId > 0 && isset($_POST['delete_id'])) {
        $controller->deleteNote($currentUserId);
    }
}

if ($currentUserId > 0) {
    $notes = $controller->getNotes($currentUserId);
}

// Render the view
require_once __DIR__ . '/../views/notes/index.php';
