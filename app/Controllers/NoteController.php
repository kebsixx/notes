<?php

class NoteController
{
    private PDO $db_conn;
    private $perPage = 5;

    public function __construct(PDO $db_conn)
    {
        $this->db_conn = $db_conn;
    }

    public function getNotes(int $userId, string $searchQuery = '', int $page = 1): array
    {
        global $errorMessage;

        $page = max(1, $page);
        $offset = ($page - 1) * $this->perPage;
        $searchQuery = trim($searchQuery);
        try {
            $whereSql = 'WHERE user_id = :user_id';
            $params = [':user_id' => $userId];

            if ($searchQuery !== '') {
                $whereSql .= ' AND content ILIKE :search';
                $params[':search'] = '%' . $searchQuery . '%';
            }

            $countStmt = $this->db_conn->prepare("SELECT COUNT(*) AS total FROM public.notes {$whereSql}");
            $countStmt->execute($params);
            $total = (int) ($countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            $totalPages = max(1, (int) ceil($total / $this->perPage));

            if ($page > $totalPages) {
                $page = $totalPages;
                $offset = ($page - 1) * $this->perPage;
            }

            $notesSql = "SELECT id, content, created_at FROM public.notes {$whereSql} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $notesStmt = $this->db_conn->prepare($notesSql);

            foreach ($params as $key => $value) {
                $notesStmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $notesStmt->bindValue(':limit', $this->perPage, PDO::PARAM_INT);
            $notesStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $notesStmt->execute();

            return [
                'items' => $notesStmt->fetchAll(PDO::FETCH_ASSOC) ?: [],
                'total' => $total,
                'page' => $page,
                'totalPages' => $totalPages,
                'query' => $searchQuery,
                'perPage' => $this->perPage,
            ];
        } catch (PDOException $e) {
            $errorMessage = 'Gagal mengambil notes.';
            return [
                'items' => [],
                'total' => 0,
                'page' => 1,
                'totalPages' => 1,
                'query' => $searchQuery,
                'perPage' => $this->perPage,
            ];
        }
    }

    public function getNoteForEdit(int $userId, int $noteId): ?array
    {
        global $errorMessage;

        if ($noteId <= 0) {
            return null;
        }

        try {
            $stmt = $this->db_conn->prepare('SELECT id, content FROM public.notes WHERE id = :id AND user_id = :user_id LIMIT 1');
            $stmt->execute([
                ':id' => $noteId,
                ':user_id' => $userId,
            ]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal memuat note untuk diedit.';
            return null;
        }
    }

    public function addNote(int $userId)
    {
        global $errorMessage;

        if (!$this->isValidCsrfToken()) {
            $errorMessage = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
            return;
        }

        $rawContent = (string) ($_POST['content'] ?? '');
        $content = trim($rawContent);

        // sanitize: remove HTML tags and normalize whitespace
        $content = strip_tags($content);
        $content = preg_replace('/\s+/', ' ', $content);

        if ($content === '') {
            $errorMessage = 'Isi note tidak boleh kosong.';
            return;
        }

        try {
            $insertStmt = $this->db_conn->prepare('INSERT INTO public.notes (id, content, user_id) SELECT COALESCE(MAX(id), 0) + 1, :content, :user_id FROM public.notes');
            $insertStmt->execute([
                ':content' => $content,
                ':user_id' => $userId,
            ]);

            // flash success
            $_SESSION['flash_success'] = 'Note berhasil disimpan.';

            $redirectParams = [];
            $postedQ = (string) ($_POST['redirect_q'] ?? '');
            $postedPage = (int) ($_POST['redirect_page'] ?? 0);
            if ($postedQ !== '') {
                $redirectParams['q'] = $postedQ;
            }
            if ($postedPage > 1) {
                $redirectParams['page'] = $postedPage;
            }

            $location = 'index.php';
            if (!empty($redirectParams)) {
                $location .= '?' . http_build_query($redirectParams);
            }

            header('Location: ' . $location);
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menyimpan note.';
            error_log(sprintf('[notes] addNote failed for user_id=%d: %s', $userId, $e->getMessage()));
            $_SESSION['flash_error'] = $errorMessage;
        }
    }

    public function deleteNote(int $userId)
    {
        global $errorMessage;

        if (!$this->isValidCsrfToken()) {
            $errorMessage = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
            return;
        }

        $deleteId = (int) ($_POST['delete_id'] ?? 0);

        if ($deleteId <= 0) {
            return;
        }

        try {
            $deleteStmt = $this->db_conn->prepare('DELETE FROM public.notes WHERE id = :id AND user_id = :user_id');
            $deleteStmt->execute([
                ':id' => $deleteId,
                ':user_id' => $userId,
            ]);

            $_SESSION['flash_success'] = 'Note berhasil dihapus.';

            $redirectParams = [];
            $postedQ = (string) ($_POST['redirect_q'] ?? '');
            $postedPage = (int) ($_POST['redirect_page'] ?? 0);
            if ($postedQ !== '') {
                $redirectParams['q'] = $postedQ;
            }
            if ($postedPage > 1) {
                $redirectParams['page'] = $postedPage;
            }

            $location = 'index.php';
            if (!empty($redirectParams)) {
                $location .= '?' . http_build_query($redirectParams);
            }

            header('Location: ' . $location);
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menghapus note.';
            error_log(sprintf('[notes] deleteNote failed for user_id=%d id=%d: %s', $userId, $deleteId, $e->getMessage()));
            $_SESSION['flash_error'] = $errorMessage;
        }
    }

    public function updateNote(int $userId)
    {
        global $errorMessage;

        if (!$this->isValidCsrfToken()) {
            $errorMessage = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
            return;
        }

        $updateId = (int) ($_POST['update_id'] ?? 0);
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($updateId <= 0) {
            $errorMessage = 'Note yang ingin diedit tidak valid.';
            return;
        }

        if ($content === '') {
            $errorMessage = 'Isi note tidak boleh kosong.';
            return;
        }

        try {
            $updateStmt = $this->db_conn->prepare('UPDATE public.notes SET content = :content WHERE id = :id AND user_id = :user_id');
            $updateStmt->execute([
                ':content' => $content,
                ':id' => $updateId,
                ':user_id' => $userId,
            ]);

            $_SESSION['flash_success'] = 'Note berhasil diperbarui.';

            $redirectParams = [];
            $postedQ = (string) ($_POST['redirect_q'] ?? '');
            $postedPage = (int) ($_POST['redirect_page'] ?? 0);
            if ($postedQ !== '') {
                $redirectParams['q'] = $postedQ;
            }
            if ($postedPage > 1) {
                $redirectParams['page'] = $postedPage;
            }

            $location = 'index.php';
            if (!empty($redirectParams)) {
                $location .= '?' . http_build_query($redirectParams);
            }

            header('Location: ' . $location);
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal memperbarui note.';
            error_log(sprintf('[notes] updateNote failed for user_id=%d id=%d: %s', $userId, $updateId, $e->getMessage()));
            $_SESSION['flash_error'] = $errorMessage;
        }
    }

    private function isValidCsrfToken(): bool
    {
        $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');
        $postedToken = (string) ($_POST['csrf_token'] ?? '');

        return $sessionToken !== '' && $postedToken !== '' && hash_equals($sessionToken, $postedToken);
    }
}
