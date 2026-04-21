<?php
session_start();

include 'db.php';

if (isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0) {
    header('Location: index.php');
    exit;
}

$errorMessage = '';
$successMessage = '';
$signupUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $signupUsername = trim((string) ($_POST['signup_username'] ?? ''));
    $signupPassword = (string) ($_POST['signup_password'] ?? '');
    $signupConfirm = (string) ($_POST['signup_confirm'] ?? '');

    if ($signupUsername === '' || $signupPassword === '' || $signupConfirm === '') {
        $errorMessage = 'Semua field wajib diisi.';
    } elseif (strlen($signupUsername) < 3) {
        $errorMessage = 'Username minimal 3 karakter.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $signupUsername)) {
        $errorMessage = 'Username hanya boleh huruf, angka, dan underscore.';
    } elseif (strlen($signupPassword) < 6) {
        $errorMessage = 'Password minimal 6 karakter.';
    } elseif ($signupPassword !== $signupConfirm) {
        $errorMessage = 'Konfirmasi password tidak sama.';
    } else {
        try {
            $hash = password_hash($signupPassword, PASSWORD_BCRYPT);
            $insertStmt = $db_conn->prepare('INSERT INTO public.users (username, password_hash) VALUES (:username, :password_hash)');
            $insertStmt->execute([
                ':username' => $signupUsername,
                ':password_hash' => $hash,
            ]);

            $successMessage = 'Signup berhasil. Silakan login.';
            $signupUsername = '';
        } catch (PDOException $e) {
            $sqlState = $e->getCode();
            if ($sqlState === '23505') {
                $errorMessage = 'Username sudah dipakai.';
            } else {
                $errorMessage = 'Signup gagal. Cek permission tabel users di PostgreSQL.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup - QuickNotes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="wrap auth-wrap">
        <header class="hero">
            <h1>Signup QuickNotes</h1>
            <p>Buat akun baru untuk menyimpan notes pribadi Anda.</p>
        </header>

        <?php include __DIR__ . '/templates/alert.php'; ?>

        <?php include __DIR__ . '/templates/signup_panel.php'; ?>
    </main>
    <script src="auth-ui.js"></script>
</body>

</html>
