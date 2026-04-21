<?php

class NoteController
{
    private $db_conn;

    public function __construct($db_conn)
    {
        $this->db_conn = $db_conn;
    }

    public function getNotes(int $userId)
    {
        global $errorMessage;
        try {
            $notesStmt = $this->db_conn->prepare('SELECT id, content, created_at FROM public.notes WHERE user_id = :user_id ORDER BY created_at DESC');
            $notesStmt->execute([':user_id' => $userId]);
            return $notesStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            $errorMessage = 'Gagal mengambil notes.';
            return [];
        }
    }

    public function addNote(int $userId)
    {
        global $errorMessage;

        $content = trim((string) ($_POST['content'] ?? ''));

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
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menyimpan note.';
        }
    }

    public function deleteNote(int $userId)
    {
        global $errorMessage;

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
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menghapus note.';
        }
    }
}
