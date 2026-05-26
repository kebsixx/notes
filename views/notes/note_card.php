<?php
$note = isset($note) && is_array($note) ? $note : [];
$noteContent = (string) ($note['content'] ?? '');
$noteCreatedAt = (string) ($note['created_at'] ?? '');
$noteId = (int) ($note['id'] ?? 0);
$searchQuery = (string) ($searchQuery ?? '');
$currentPage = max(1, (int) ($currentPage ?? 1));
$editQuery = http_build_query([
    'edit_id' => $noteId,
    'q' => $searchQuery,
    'page' => $currentPage,
]);
?>
<article class="note">
    <p><?php echo htmlspecialchars($noteContent, ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="note-meta">
        <span class="stamp"><?php echo $noteCreatedAt !== '' ? date('d M Y H:i', strtotime($noteCreatedAt)) : '-'; ?></span>
        <div class="note-actions">
            <a class="btn btn-secondary" href="index.php?<?php echo htmlspecialchars($editQuery, ENT_QUOTES, 'UTF-8'); ?>">Edit</a>
            <form method="post" action="index.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="delete_id" value="<?php echo $noteId; ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</article>
