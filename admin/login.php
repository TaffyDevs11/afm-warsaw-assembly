<?php
/**
 * admin/login.php — Admin login form
 * -----------------------------------------------------------------------
 * Default seeded login (see sql/seed.sql):
 *   username: admin
 *   password: AfmWarsaw2026!
 * Change this password after your first login in a real deployment.
 * -----------------------------------------------------------------------
 */
require_once __DIR__ . '/includes/bootstrap.php';

// Already logged in? Skip straight to the dashboard.
if (!empty($_SESSION['admin_id'])) {
    redirect_to('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();

    $username = clean_input($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter both your username and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, password_hash, display_name FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Regenerate the session ID on login to prevent session fixation.
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['display_name'];
            redirect_to('dashboard.php');
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | <?= h(SITE_SHORT_NAME) ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="<?= h(ASSETS_URL) ?>/images/logowhite.png-171x160.png">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= h(ASSETS_URL) ?>/css/style.css">
<link rel="stylesheet" href="<?= h(ASSETS_URL) ?>/css/admin.css">
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <img src="<?= h(ASSETS_URL) ?>/images/logowhite-479x486.webp" alt="AFM Church in Poland crest">
        <h1>Admin Login</h1>
        <p class="subtitle">AFM Church in Poland — Warsaw Christian Centre</p>

        <?php if ($error): ?>
            <div class="admin-flash admin-flash--error" role="alert"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <div class="form-field" style="text-align:left;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus value="<?= h($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-field" style="text-align:left;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn--navy btn--block">Log In</button>
        </form>

        <p style="margin-top:1.5rem;"><a href="<?= h(PUBLIC_URL) ?>/index.php" style="font-size:0.85rem;color:var(--ink-faint);">&larr; Back to website</a></p>
    </div>
</div>
</body>
</html>
