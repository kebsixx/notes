<?php
$currentUsername = (string) ($currentUsername ?? '');
?>
<section class="session-bar">
    <div class="session-user">
        <span class="session-label">Aktif</span>
        <strong><?php echo htmlspecialchars($currentUsername, ENT_QUOTES, 'UTF-8'); ?></strong>
    </div>
    <form method="post" action="index.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="logout" value="1">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</section>
