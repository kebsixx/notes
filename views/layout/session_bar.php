<?php
$currentUsername = (string) ($currentUsername ?? '');
?>
<section class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border-2 border-stone-800 bg-white/85 p-3 shadow-[0_10px_0_0_rgba(41,37,36,1)] backdrop-blur-sm">
    <div class="inline-flex items-center gap-2 rounded-full border-2 border-stone-800 chip-active px-3 py-1.5">
        <span class="font-mono text-[11px] uppercase tracking-wider text-stone-700">Aktif</span>
        <strong class="text-sm font-bold text-stone-900"><?php echo htmlspecialchars($currentUsername, ENT_QUOTES, 'UTF-8'); ?></strong>
    </div>
    <form method="post" action="index.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="logout" value="1">
        <button type="submit" class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-stone-900 px-4 py-2 text-sm font-semibold text-stone-50 shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5">Logout</button>
    </form>
</section>
