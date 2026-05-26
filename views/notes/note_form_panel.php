<?php
$editNoteId = max(0, (int) ($editNoteId ?? 0));
$editNoteContent = (string) ($editNoteContent ?? '');
$searchQuery = (string) ($searchQuery ?? '');
$currentPage = max(1, (int) ($currentPage ?? 1));
?>
<section class="panel">
    <form method="post" action="index.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($editNoteId > 0): ?>
            <input type="hidden" name="update_id" value="<?php echo (int) $editNoteId; ?>">
        <?php endif; ?>
        <label for="content">Write a note</label>
        <textarea
            id="content"
            name="content"
            placeholder="Type your note here..."
            required><?php echo htmlspecialchars($editNoteContent, ENT_QUOTES, 'UTF-8'); ?></textarea>
        <p class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo $editNoteId > 0 ? 'Update Note' : 'Save Note'; ?></button>
            <?php if ($editNoteId > 0): ?>
                <a class="btn btn-secondary" href="index.php<?php echo $searchQuery !== '' || $currentPage > 1 ? '?' . http_build_query(['q' => $searchQuery, 'page' => $currentPage]) : ''; ?>">Cancel Edit</a>
            <?php endif; ?>
        </p>
    </form>
</section>
