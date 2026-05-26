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
<article class="rounded-2xl border-2 border-stone-800 bg-white/90 p-4 shadow-[0_10px_0_0_rgba(41,37,36,1)] backdrop-blur-sm transition hover:-translate-y-0.5">
    <p class="mb-3 whitespace-pre-wrap break-words text-[15px] leading-relaxed text-stone-800"><?php echo htmlspecialchars($noteContent, ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="flex flex-wrap items-center justify-between gap-2">
        <span class="rounded-full border-2 border-stone-800 bg-stone-100 px-3 py-1 font-mono text-[11px] uppercase tracking-wide text-stone-700"><?php echo $noteCreatedAt !== '' ? date('d M Y H:i', strtotime($noteCreatedAt)) : '-'; ?></span>
        <div class="flex flex-wrap gap-2">
            <a class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-white px-3 py-1.5 text-xs font-semibold text-stone-900 shadow-[0_4px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5" href="index.php?<?php echo htmlspecialchars($editQuery, ENT_QUOTES, 'UTF-8'); ?>">Edit</a>
            <form method="post" action="index.php" class="m-0">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars((string) ($csrfToken ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="delete_id" value="<?php echo $noteId; ?>">
                <input type="hidden" name="redirect_q" value="<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="redirect_page" value="<?php echo (int) $currentPage; ?>">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl border-2 border-stone-800 bg-red-200 px-3 py-1.5 text-xs font-semibold text-stone-900 shadow-[0_4px_0_0_rgba(41,37,36,1)] transition hover:-translate-y-0.5">Delete</button>
            </form>
        </div>
    </div>
</article>
