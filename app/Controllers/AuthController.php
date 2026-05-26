<?php

class AuthController
{
    private PDO $db_conn;

    public function __construct(PDO $db_conn)
    {
        $this->db_conn = $db_conn;
    }

    public function login()
    {
        global $errorMessage, $currentUserId, $currentUsername, $loginUsernameValue;

        if (!$this->isValidCsrfToken()) {
            $errorMessage = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
            return;
        }

        $loginUsername = trim((string) ($_POST['login_username'] ?? ''));
        $loginPassword = (string) ($_POST['login_password'] ?? '');
        $loginUsernameValue = $loginUsername;

        if ($loginUsername === '' || $loginPassword === '') {
            $errorMessage = 'Username dan password wajib diisi.';
            return;
        }

        try {
            $loginStmt = $this->db_conn->prepare('SELECT id, username, password_hash FROM public.users WHERE username = :username LIMIT 1');
            $loginStmt->execute([':username' => $loginUsername]);
            $userRow = $loginStmt->fetch(PDO::FETCH_ASSOC);

            if ($userRow && password_verify($loginPassword, $userRow['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $userRow['id'];
                $_SESSION['username'] = (string) $userRow['username'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                // optional flash
                $_SESSION['flash_success'] = 'Login berhasil.';
                header('Location: index.php');
                exit;
            }

            $errorMessage = 'Login gagal. Periksa username atau password.';
        } catch (PDOException $e) {
            error_log('Login database error: ' . $e->getMessage());
            $errorMessage = 'Gagal memproses login.';
        }
    }

    public function logout()
    {
        if (!$this->isValidCsrfToken()) {
            header('Location: index.php');
            exit;
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function signup()
    {
        global $errorMessage, $successMessage, $signupUsername;

        if (!$this->isValidCsrfToken()) {
            $errorMessage = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
            return;
        }

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
                $insertStmt = $this->db_conn->prepare('INSERT INTO public.users (username, password_hash) VALUES (:username, :password_hash)');
                $insertStmt->execute([
                    ':username' => $signupUsername,
                    ':password_hash' => $hash,
                ]);

                $successMessage = 'Signup berhasil. Silakan login.';
                $_SESSION['flash_success'] = $successMessage;
                $signupUsername = '';
                header('Location: ../public/signup.php');
                exit;
            } catch (PDOException $e) {
                $sqlState = $e->getCode();
                if ($sqlState === '23505') {
                    $errorMessage = 'Username sudah dipakai.';
                } else {
                    $errorMessage = 'Signup gagal. Cek permission tabel users di PostgreSQL.';
                }
                error_log(sprintf('[auth] signup failed for username=%s: %s', $signupUsername, $e->getMessage()));
                $_SESSION['flash_error'] = $errorMessage;
            }
        }
    }

    private function isValidCsrfToken(): bool
    {
        $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');
        $postedToken = (string) ($_POST['csrf_token'] ?? '');

        return $sessionToken !== '' && $postedToken !== '' && hash_equals($sessionToken, $postedToken);
    }
}
