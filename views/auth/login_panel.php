<section class="panel auth-panel">
    <h2 class="section-title">Login</h2>
    <form method="post" action="index.php" class="auth-form">
        <div class="auth-field">
            <label class="auth-label" for="login_username"><span>Username</span></label>
            <div class="auth-input-wrap">
                <input class="auth-input" id="login_username" name="login_username" type="text" autocomplete="username" value="<?php echo htmlspecialchars($loginUsernameValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                <span class="auth-icon" aria-hidden="true">
                    <svg class="auth-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.89 4.111a5.5 5.5 0 1 0 0 7.778.75.75 0 1 1 1.06 1.061A7 7 0 1 1 15 8a2.5 2.5 0 0 1-4.083 1.935A3.5 3.5 0 1 1 11.5 8a1 1 0 0 0 2 0 5.48 5.48 0 0 0-1.61-3.889ZM10 8a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            </div>
        </div>

        <div class="auth-field">
            <label class="auth-label" for="login_password"><span>Password</span></label>
            <div class="auth-input-wrap">
                <input class="auth-input" id="login_password" name="login_password" type="password" autocomplete="current-password" required>
                <button class="auth-icon auth-icon-button" type="button" data-toggle-password data-target="login_password" aria-label="Show password" aria-pressed="false">
                    <svg class="auth-icon-svg auth-icon-svg-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M12 5c5.52 0 9.8 4.05 11 7-1.2 2.95-5.48 7-11 7S2.2 14.95 1 12c1.2-2.95 5.48-7 11-7Zm0 2C7.79 7 4.35 9.74 3.17 12 4.35 14.26 7.79 17 12 17s7.65-2.74 8.83-5C19.65 9.74 16.21 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/>
                    </svg>
                    <svg class="auth-icon-svg auth-icon-svg-eye-off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" hidden>
                        <path d="M2.29 3.7 1 5l4.1 4.1A13.92 13.92 0 0 0 1 12c1.2 2.95 5.48 7 11 7a11.8 11.8 0 0 0 4.86-1L20.3 21.3 21.6 20 2.29 3.7ZM12 17c-4.21 0-7.65-2.74-8.83-5a11.5 11.5 0 0 1 3.36-3.7l1.53 1.53A3.96 3.96 0 0 0 8 12a4 4 0 0 0 5.17 3.82l2.2 2.2A9.3 9.3 0 0 1 12 17Zm0-10a9.85 9.85 0 0 1 8.83 5 11.06 11.06 0 0 1-2.92 3.47l-1.44-1.44A3.97 3.97 0 0 0 16 12a4 4 0 0 0-5.62-3.66l-1.6-1.6A9.42 9.42 0 0 1 12 7Z"/>
                    </svg>
                </button>
            </div>
        </div>

        <p class="form-actions auth-actions">
            <button type="submit" class="btn btn-primary">Login</button>
            <a class="btn btn-secondary" href="signup.php">Signup</a>
        </p>
    </form>
</section>
