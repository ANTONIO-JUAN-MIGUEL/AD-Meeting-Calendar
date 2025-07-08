<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
Auth::init();

$error = $_GET['error'] ?? null;

ob_start();
?>

<h1>Login</h1>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="/handlers/auth.handler.php" method="POST">
    <label>Username:
        <input type="text" name="username" required>
    </label>

    <label>Password:
        <input type="password" name="password" required>
    </label>

    <button type="submit">Login</button>
</form>

<?php
$content = ob_get_clean();
include BASE_PATH . '/layouts/main.layout.php';
