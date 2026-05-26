<?php
// Display flash messages stored in session (persist across redirects)
$flashError = $_SESSION['flash_error'] ?? null;
$flashSuccess = $_SESSION['flash_success'] ?? null;

if ($flashError !== null):
    unset($_SESSION['flash_error']);
?>
    <div class="alert" role="alert">
        <?php echo htmlspecialchars((string) $flashError, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if ($flashSuccess !== null):
    unset($_SESSION['flash_success']);
?>
    <div class="alert alert-success" role="status">
        <?php echo htmlspecialchars((string) $flashSuccess, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($errorMessage)): ?>
    <div class="alert" role="alert">
        <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($successMessage)): ?>
    <div class="alert alert-success" role="status">
        <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>
