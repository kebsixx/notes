<?php
$note = isset($note) && is_array($note) ? $note : [];
$noteContent = (string) ($note['content'] ?? '');
$noteCreatedAt = (string) ($note['created_at'] ?? '');
$noteId = (int) ($note['id'] ?? 0);
?>
<article class="note">
    <p><?php echo htmlspecialchars($noteContent, ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="note-meta">
        <span class="stamp"><?php echo $noteCreatedAt !== '' ? date('d M Y H:i', strtotime($noteCreatedAt)) : '-'; ?></span>
        <form method="post" action="index.php">
            <input type="hidden" name="delete_id" value="<?php echo $noteId; ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</article>
