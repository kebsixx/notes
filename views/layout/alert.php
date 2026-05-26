<?php
$flashError = $_SESSION['flash_error'] ?? null;
$flashSuccess = $_SESSION['flash_success'] ?? null;

if ($flashError !== null):
    unset($_SESSION['flash_error']);
?>
    <div class="rounded-2xl border-2 border-stone-800 bg-rose-100 px-4 py-3 text-sm font-semibold text-stone-900 shadow-[0_8px_0_0_rgba(41,37,36,1)]" role="alert">
        <?php echo htmlspecialchars((string) $flashError, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if ($flashSuccess !== null):
    unset($_SESSION['flash_success']);
?>
    <div class="rounded-2xl border-2 border-stone-800 bg-emerald-100 px-4 py-3 text-sm font-semibold text-stone-900 shadow-[0_8px_0_0_rgba(41,37,36,1)]" role="status">
        <?php echo htmlspecialchars((string) $flashSuccess, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($errorMessage)): ?>
    <div class="rounded-2xl border-2 border-stone-800 bg-rose-100 px-4 py-3 text-sm font-semibold text-stone-900 shadow-[0_8px_0_0_rgba(41,37,36,1)]" role="alert">
        <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($successMessage)): ?>
    <div class="rounded-2xl border-2 border-stone-800 bg-emerald-100 px-4 py-3 text-sm font-semibold text-stone-900 shadow-[0_8px_0_0_rgba(41,37,36,1)]" role="status">
        <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>
