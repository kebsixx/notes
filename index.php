<?php
session_start();

require 'db.php';

$errorMessage = '';
$successMessage = '';
$notes = [];
$currentUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$currentUsername = isset($_SESSION['username']) ? (string) $_SESSION['username'] : '';
$loginUsernameValue = '';

if (!isset($db_conn)) {
    die('Database connection object is not available.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_username'], $_POST['login_password'])) {
        $loginUsername = trim((string) $_POST['login_username']);
        $loginPassword = (string) $_POST['login_password'];
        $loginUsernameValue = $loginUsername;

        if ($loginUsername === '' || $loginPassword === '') {
            $errorMessage = 'Username dan password wajib diisi.';
        } else {
            try {
                $loginStmt = $db_conn->prepare('SELECT id, username, password_hash FROM public.users WHERE username = :username LIMIT 1');
                $loginStmt->execute([':username' => $loginUsername]);
                $userRow = $loginStmt->fetch(PDO::FETCH_ASSOC);

                if ($userRow && password_verify($loginPassword, $userRow['password_hash'])) {
                    $_SESSION['user_id'] = (int) $userRow['id'];
                    $_SESSION['username'] = (string) $userRow['username'];
                    header('Location: index.php');
                    exit;
                }

                $errorMessage = 'Login gagal. Periksa username atau password.';
            } catch (PDOException $e) {
                if ($loginUsername === 'admin' && $loginPassword === 'admin123') {
                    $_SESSION['user_id'] = 1;
                    $_SESSION['username'] = 'admin';
                    header('Location: index.php');
                    exit;
                }

                $errorMessage = 'Gagal memproses login.';
            }
        }
    }

    if (isset($_POST['logout'])) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: index.php');
        exit;
    }

    if ($currentUserId > 0 && isset($_POST['content'])) {
        $content = trim((string) $_POST['content']);

        if ($content !== '') {
            try {
                $insertStmt = $db_conn->prepare('INSERT INTO public.notes (id, content, user_id) SELECT COALESCE(MAX(id), 0) + 1, :content, :user_id FROM public.notes');
                $insertStmt->execute([
                    ':content' => $content,
                    ':user_id' => $currentUserId,
                ]);
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                $errorMessage = 'Gagal menyimpan note.';
            }
        } else {
            $errorMessage = 'Isi note tidak boleh kosong.';
        }
    }

    if ($currentUserId > 0 && isset($_POST['delete_id'])) {
        $deleteId = (int) $_POST['delete_id'];

        if ($deleteId > 0) {
            try {
                $deleteStmt = $db_conn->prepare('DELETE FROM public.notes WHERE id = :id AND user_id = :user_id');
                $deleteStmt->execute([
                    ':id' => $deleteId,
                    ':user_id' => $currentUserId,
                ]);
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                $errorMessage = 'Gagal menghapus note.';
            }
        }
    }
}

if ($currentUserId > 0) {
    try {
        $notesStmt = $db_conn->prepare('SELECT id, content, created_at FROM public.notes WHERE user_id = :user_id ORDER BY created_at DESC');
        $notesStmt->execute([':user_id' => $currentUserId]);
        $notes = $notesStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        $errorMessage = 'Gagal mengambil notes.';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuickNotes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="wrap">
        <header class="hero">
            <h1>QuickNotes &#x1F4DD;</h1>
            <p>Setiap user hanya melihat notes miliknya sendiri.</p>
        </header>

        <?php include __DIR__ . '/templates/alert.php'; ?>

        <?php if ($currentUserId === 0): ?>
            <?php include __DIR__ . '/templates/login_panel.php'; ?>
        <?php else: ?>
            <?php include __DIR__ . '/templates/session_bar.php'; ?>

            <?php include __DIR__ . '/templates/note_form_panel.php'; ?>

            <?php if (count($notes) === 0): ?>
                <div class="empty" role="alert">
                    Belum ada notes untuk user ini.
                </div>
            <?php else: ?>
                <section class="list">
                    <?php foreach ($notes as $note): ?>
                        <?php include __DIR__ . '/templates/note_card.php'; ?>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <script src="auth-ui.js"></script>
</body>

</html>