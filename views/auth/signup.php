<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup - QuickNotes</title>
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
    <main class="mx-auto grid w-full max-w-6xl gap-4">
        <header class="relative overflow-hidden rounded-3xl border-2 border-stone-800 bg-white/85 p-6 shadow-[0_12px_0_0_rgba(41,37,36,1)] backdrop-blur-sm sm:p-8">
            <div class="pointer-events-none absolute -right-8 -top-10 h-32 w-32 rounded-full border-2 border-stone-800 bg-gradient-to-br from-[var(--primary)] to-stone-500 opacity-70"></div>
            <h1 class="max-w-3xl font-display text-4xl leading-tight tracking-wide text-stone-900 sm:text-6xl">Signup QuickNotes</h1>
            <p class="mt-2 max-w-2xl text-sm font-semibold text-stone-600 sm:text-base">Buat akun baru untuk menyimpan notes pribadi Anda.</p>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-mono uppercase tracking-wider text-stone-600">Theme</span>
                <button type="button" data-theme-btn="slate" aria-pressed="false" class="rounded-lg border-2 border-stone-800 bg-white px-2.5 py-1 text-xs font-semibold text-stone-800">Slate</button>
                <button type="button" data-theme-btn="forest" aria-pressed="false" class="rounded-lg border-2 border-stone-800 bg-white px-2.5 py-1 text-xs font-semibold text-stone-800">Forest</button>
                <button type="button" data-theme-btn="ocean" aria-pressed="false" class="rounded-lg border-2 border-stone-800 bg-white px-2.5 py-1 text-xs font-semibold text-stone-800">Ocean</button>
            </div>
        </header>

        <div class="grid gap-3"><?php include __DIR__ . '/../layout/alert.php'; ?></div>
        <?php include __DIR__ . '/signup_panel.php'; ?>
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
