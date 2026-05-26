<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/NoteController.php';

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

$controller = new \NoteController($db_conn);
$authController = new \AuthController($db_conn);

$currentUserId = $_SESSION['user_id'] ?? 0;
$currentUsername = $_SESSION['username'] ?? '';
$errorMessage = '';
$successMessage = '';
$loginUsernameValue = '';
$notes = [];
$csrfToken = (string) $_SESSION['csrf_token'];
$searchQuery = trim((string) ($_GET['q'] ?? ''));
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$totalPages = 1;
$totalNotes = 0;
$editNoteId = max(0, (int) ($_GET['edit_id'] ?? 0));
$editNoteContent = '';

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    if (isset($_POST['login_username'], $_POST['login_password'])) {
        $authController->login();
    } elseif (isset($_POST['logout'])) {
        $authController->logout();
    } elseif ($currentUserId > 0 && isset($_POST['update_id'], $_POST['content'])) {
        $controller->updateNote($currentUserId);
    } elseif ($currentUserId > 0 && isset($_POST['content'])) {
        $controller->addNote($currentUserId);
    } elseif ($currentUserId > 0 && isset($_POST['delete_id'])) {
        $controller->deleteNote($currentUserId);
    }
}

if ($currentUserId > 0) {
    $noteListResult = $controller->getNotes($currentUserId, $searchQuery, $currentPage);
    $notes = $noteListResult['items'] ?? [];
    $currentPage = (int) ($noteListResult['page'] ?? 1);
    $totalPages = (int) ($noteListResult['totalPages'] ?? 1);
    $totalNotes = (int) ($noteListResult['total'] ?? 0);
    $searchQuery = (string) ($noteListResult['query'] ?? $searchQuery);

    if ($editNoteId > 0) {
        $editNote = $controller->getNoteForEdit($currentUserId, $editNoteId);
        if ($editNote) {
            $editNoteContent = (string) ($editNote['content'] ?? '');
        } else {
            $editNoteId = 0;
        }
    }
}

// Render the view
require_once __DIR__ . '/../views/notes/index.php';
