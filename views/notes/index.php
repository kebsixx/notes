<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuickNotes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Manrope:wght@500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Alfa Slab One', 'serif'],
                        body: ['Manrope', 'sans-serif'],
                        mono: ['IBM Plex Mono', 'monospace']
                    }
                }
            }
        };
    </script>
    <style>
        :root {
            --bg-a: #f5f5f4;
            --bg-b: #e7e5e4;
            --orb-a: #f59e0b33;
            --orb-b: #0f172a22;
            --primary: #f59e0b;
            --primary-text: #1c1917;
        }
        body[data-theme="forest"] {
            --bg-a: #f0fdf4;
            --bg-b: #dcfce7;
            --orb-a: #22c55e33;
            --orb-b: #14532d22;
            --primary: #22c55e;
            --primary-text: #052e16;
        }
        body[data-theme="ocean"] {
            --bg-a: #f0f9ff;
            --bg-b: #e0f2fe;
            --orb-a: #0ea5e933;
            --orb-b: #0c4a6e22;
            --primary: #0ea5e9;
            --primary-text: #082f49;
        }
        .btn-primary-tone {
            background: var(--primary);
            color: var(--primary-text);
        }
        .chip-active {
            background: var(--primary) !important;
            color: var(--primary-text) !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 0 0 rgba(41, 37, 36, 1);
        }
        [data-toggle-password][aria-pressed="false"] .auth-icon-svg-eye-off { display: none; }
        [data-toggle-password][aria-pressed="true"] .auth-icon-svg-eye { display: none; }
        [data-toggle-password][aria-pressed="true"] .auth-icon-svg-eye-off { display: block; }
    </style>
</head>

<body class="min-h-screen bg-[radial-gradient(circle_at_15%_10%,var(--orb-a),transparent_35%),radial-gradient(circle_at_90%_5%,var(--orb-b),transparent_30%),linear-gradient(160deg,var(--bg-a),var(--bg-b))] p-3 font-body text-stone-900 sm:p-6" data-theme="slate">
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
    <main class="mx-auto grid w-full max-w-6xl gap-4">
        <header class="relative overflow-hidden rounded-3xl border-2 border-stone-800 bg-white/85 p-6 shadow-[0_12px_0_0_rgba(41,37,36,1)] backdrop-blur-sm sm:p-8">
            <div class="pointer-events-none absolute -right-8 -top-10 h-32 w-32 rounded-full border-2 border-stone-800 bg-gradient-to-br from-[var(--primary)] to-stone-500 opacity-70"></div>
            <h1 class="max-w-3xl font-display text-4xl leading-tight tracking-wide text-stone-900 sm:text-6xl">QuickNotes</h1>
            <p class="mt-2 max-w-2xl text-sm font-semibold text-stone-600 sm:text-base">Catatan cepat dengan tampilan baru yang lebih clean, tegas, dan fokus pada kecepatan, pencarian, dan pengelolaan catatan.</p>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-mono uppercase tracking-wider text-stone-600">Theme</span>
                <button type="button" data-theme-btn="slate" aria-pressed="false" class="rounded-lg border-2 border-stone-800 bg-white px-2.5 py-1 text-xs font-semibold text-stone-800">Slate</button>
                <button type="button" data-theme-btn="forest" aria-pressed="false" class="rounded-lg border-2 border-stone-800 bg-white px-2.5 py-1 text-xs font-semibold text-stone-800">Forest</button>
                <button type="button" data-theme-btn="ocean" aria-pressed="false" class="rounded-lg border-2 border-stone-800 bg-white px-2.5 py-1 text-xs font-semibold text-stone-800">Ocean</button>
            </div>
        </header>

        <div class="grid gap-3"><?php include __DIR__ . '/../layout/alert.php'; ?></div>

        <?php if ((int) $currentUserId === 0): ?>
            <?php include __DIR__ . '/../auth/login_panel.php'; ?>
        <?php else: ?>
            <?php include __DIR__ . '/../layout/session_bar.php'; ?>
            <section class="rounded-2xl border-2 border-stone-800 bg-white/85 p-5 shadow-[0_10px_0_0_rgba(41,37,36,1)] backdrop-blur-sm">
                <form method="get" action="index.php" class="grid gap-2">
                    <label for="q" class="text-xs font-bold uppercase tracking-wider text-stone-700">Search notes</label>
                    <div class="grid gap-2 sm:grid-cols-[minmax(0,1fr)_auto_auto]">
                        <input id="q" type="text" name="q" value="<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ketik kata kunci..." class="w-full rounded-xl border-2 border-stone-800 bg-white px-3 py-2 text-sm outline-none transition focus:border-[var(--primary)] focus:ring-4 focus:ring-[color:color-mix(in_srgb,var(--primary)_35%,white)]">
                        <button type="submit" class="btn-primary-tone inline-flex items-center justify-center rounded-xl border-2 border-stone-800 px-4 py-2 text-sm font-semibold shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5">Search</button>
                        <a class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-white px-4 py-2 text-sm font-semibold text-stone-900 shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5" href="index.php">Reset</a>
                    </div>
                </form>
                <p class="mt-2 font-mono text-xs uppercase tracking-wider text-stone-600">Total: <?php echo $totalNotes; ?> note<?php echo $totalNotes === 1 ? '' : 's'; ?>.</p>
            </section>
            <?php include __DIR__ . '/note_form_panel.php'; ?>

            <?php if (count($notes) === 0): ?>
                <div class="rounded-2xl border-2 border-stone-800 bg-white/80 px-4 py-5 text-center text-sm font-semibold text-stone-700 shadow-[0_8px_0_0_rgba(41,37,36,1)]" role="alert"><?php echo $searchQuery !== '' ? 'Tidak ada note yang cocok dengan pencarian.' : 'Belum ada notes untuk user ini.'; ?></div>
            <?php else: ?>
                <section class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    <?php foreach ($notes as $note): ?>
                        <?php include __DIR__ . '/note_card.php'; ?>
                    <?php endforeach; ?>
                </section>
                <?php if ($totalPages > 1): ?>
                    <nav class="flex flex-col items-center justify-between gap-2 rounded-2xl border-2 border-stone-800 bg-white/85 p-3 shadow-[0_8px_0_0_rgba(41,37,36,1)] sm:flex-row" aria-label="Pagination notes">
                        <a class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-white px-4 py-2 text-sm font-semibold text-stone-900 shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5<?php echo $currentPage <= 1 ? ' pointer-events-none opacity-45' : ''; ?>" href="<?php echo htmlspecialchars($queryBuilder(['q' => $searchQuery, 'page' => max(1, $currentPage - 1)]), ENT_QUOTES, 'UTF-8'); ?>"<?php echo $currentPage <= 1 ? ' aria-disabled="true"' : ''; ?>>Prev</a>
                        <span class="font-mono text-xs uppercase tracking-wider text-stone-600">Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span>
                        <a class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-white px-4 py-2 text-sm font-semibold text-stone-900 shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5<?php echo $currentPage >= $totalPages ? ' pointer-events-none opacity-45' : ''; ?>" href="<?php echo htmlspecialchars($queryBuilder(['q' => $searchQuery, 'page' => min($totalPages, $currentPage + 1)]), ENT_QUOTES, 'UTF-8'); ?>"<?php echo $currentPage >= $totalPages ? ' aria-disabled="true"' : ''; ?>>Next</a>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <script src="assets/js/auth-ui.js"></script>
    <script>
        (function () {
            var key = 'quicknotes_theme';
            var body = document.body;
            var buttons = document.querySelectorAll('[data-theme-btn]');
            function applyActive(theme) {
                buttons.forEach(function (b) {
                    var isActive = (b.getAttribute('data-theme-btn') || 'slate') === theme;
                    b.classList.toggle('chip-active', isActive);
                    b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    b.classList.toggle('text-stone-900', isActive);
                    b.classList.toggle('text-stone-800', !isActive);
                });
            }
            var saved = localStorage.getItem(key);
            if (saved) body.setAttribute('data-theme', saved);
            applyActive(body.getAttribute('data-theme') || 'slate');
            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var theme = btn.getAttribute('data-theme-btn') || 'slate';
                    body.setAttribute('data-theme', theme);
                    localStorage.setItem(key, theme);
                    applyActive(theme);
                });
            });
        })();
    </script>
</body>

</html>
