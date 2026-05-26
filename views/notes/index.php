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
            <?php include __DIR__ . '/note_form_panel.php'; ?>

            <?php if (count($notes) === 0): ?>
                <div class="empty" role="alert">Belum ada notes untuk user ini.</div>
            <?php else: ?>
                <section class="list">
                    <?php foreach ($notes as $note): ?>
                        <?php include __DIR__ . '/note_card.php'; ?>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <script src="assets/js/auth-ui.js"></script>
</body>

</html>
