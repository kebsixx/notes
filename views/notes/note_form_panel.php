<?php
$editNoteId = max(0, (int) ($editNoteId ?? 0));
$editNoteContent = (string) ($editNoteContent ?? '');
$searchQuery = (string) ($searchQuery ?? '');
$currentPage = max(1, (int) ($currentPage ?? 1));
?>
<section class="rounded-2xl border-2 border-stone-800 bg-white/85 p-5 shadow-[0_10px_0_0_rgba(41,37,36,1)] backdrop-blur-sm">
    <form method="post" action="index.php" class="grid gap-3">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($editNoteId > 0): ?>
            <input type="hidden" name="update_id" value="<?php echo (int) $editNoteId; ?>">
        <?php endif; ?>
        <input type="hidden" name="redirect_q" value="<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="redirect_page" value="<?php echo (int) $currentPage; ?>">
        <label for="content" class="text-sm font-bold uppercase tracking-wide text-stone-700">Write a note</label>
        <textarea
            id="content"
            name="content"
            placeholder="Type your note here..."
            class="min-h-36 w-full rounded-xl border-2 border-stone-800 bg-white px-4 py-3 text-[15px] text-stone-900 outline-none transition focus:border-[var(--primary)] focus:ring-4 focus:ring-[color:color-mix(in_srgb,var(--primary)_35%,white)]"
            required><?php echo htmlspecialchars($editNoteContent, ENT_QUOTES, 'UTF-8'); ?></textarea>
        <p class="mt-1 flex flex-wrap gap-2">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 btn-primary-tone px-4 py-2 text-sm font-semibold text-stone-900 shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5"><?php echo $editNoteId > 0 ? 'Update Note' : 'Save Note'; ?></button>
            <?php if ($editNoteId > 0): ?>
                <a class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-white px-4 py-2 text-sm font-semibold text-stone-900 shadow-[0_5px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5" href="index.php<?php echo $searchQuery !== '' || $currentPage > 1 ? '?' . http_build_query(['q' => $searchQuery, 'page' => $currentPage]) : ''; ?>">Cancel Edit</a>
            <?php endif; ?>
        </p>
    </form>
</section>
