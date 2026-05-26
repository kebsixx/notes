<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuickNotes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php
    $currentUserId = isset($currentUserId) ? (int) $currentUserId : 0;
    $notes = isset($notes) && is_array($notes) ? $notes : [];
    $searchQuery = trim((string) ($searchQuery ?? ''));
    $currentPage = max(1, (int) ($currentPage ?? 1));
    $totalPages = max(1, (int) ($totalPages ?? 1));
    $totalNotes = max(0, (int) ($totalNotes ?? 0));

    $queryBuilder = function (array $params): string {
        $clean = [];
        foreach ($params as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            $clean[$key] = $value;
        }
        $query = http_build_query($clean);
        return $query === '' ? 'index.php' : 'index.php?' . $query;
    };
    ?>
    <main class="wrap">
        <header class="hero">
            <h1>QuickNotes &#x1F4DD;</h1>
            <p>Tulis dan Simpan catatan Anda di sini.</p>
        </header>

        <?php include __DIR__ . '/../layout/alert.php'; ?>

        <?php if ((int) $currentUserId === 0): ?>
            <?php include __DIR__ . '/../auth/login_panel.php'; ?>
        <?php else: ?>
            <?php include __DIR__ . '/../layout/session_bar.php'; ?>
            <section class="panel panel-search">
                <form method="get" action="index.php" class="search-form">
                    <label for="q">Search notes</label>
                    <div class="search-controls">
                        <input id="q" type="text" name="q" value="<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ketik kata kunci...">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a class="btn btn-secondary" href="index.php">Reset</a>
                    </div>
                </form>
                <p class="hint">Total: <?php echo $totalNotes; ?> note<?php echo $totalNotes === 1 ? '' : 's'; ?>.</p>
            </section>
            <?php include __DIR__ . '/note_form_panel.php'; ?>

            <?php if (count($notes) === 0): ?>
                <div class="empty" role="alert"><?php echo $searchQuery !== '' ? 'Tidak ada note yang cocok dengan pencarian.' : 'Belum ada notes untuk user ini.'; ?></div>
            <?php else: ?>
                <section class="list">
                    <?php foreach ($notes as $note): ?>
                        <?php include __DIR__ . '/note_card.php'; ?>
                    <?php endforeach; ?>
                </section>
                <?php if ($totalPages > 1): ?>
                    <nav class="panel pagination" aria-label="Pagination notes">
                        <a class="btn btn-secondary<?php echo $currentPage <= 1 ? ' is-disabled' : ''; ?>" href="<?php echo htmlspecialchars($queryBuilder(['q' => $searchQuery, 'page' => max(1, $currentPage - 1)]), ENT_QUOTES, 'UTF-8'); ?>"<?php echo $currentPage <= 1 ? ' aria-disabled="true"' : ''; ?>>Prev</a>
                        <span class="pagination-status">Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span>
                        <a class="btn btn-secondary<?php echo $currentPage >= $totalPages ? ' is-disabled' : ''; ?>" href="<?php echo htmlspecialchars($queryBuilder(['q' => $searchQuery, 'page' => min($totalPages, $currentPage + 1)]), ENT_QUOTES, 'UTF-8'); ?>"<?php echo $currentPage >= $totalPages ? ' aria-disabled="true"' : ''; ?>>Next</a>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <script src="assets/js/auth-ui.js"></script>
</body>

</html>
