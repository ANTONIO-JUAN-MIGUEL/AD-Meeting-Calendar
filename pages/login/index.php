<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
Auth::init();
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>
    <?php include_once BASE_PATH . '/components/partials/navbar.php'; ?>
    <div class="main-container">

        <h1>Login</h1>

        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="/handlers/auth.handler.php" method="POST">
            <label>Username:
                <input type="text" name="username" required>
            </label><br><br>
            <label>Password:
                <input type="password" name="password" required>
            </label><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>