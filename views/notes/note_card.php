<article class="note">
    <p><?php echo htmlspecialchars($note['content'], ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="note-meta">
        <span class="stamp"><?php echo date('d M Y H:i', strtotime($note['created_at'])); ?></span>
        <form method="post" action="index.php">
            <input type="hidden" name="delete_id" value="<?php echo (int) $note['id']; ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</article>
