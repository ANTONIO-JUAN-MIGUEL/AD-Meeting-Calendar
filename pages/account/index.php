<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
Auth::init();
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>

    <?php include_once BASE_PATH . '/components/partials/navbar.php'; ?>
    <div class="main-container">

        <h1>Account Page</h1>

        <?php if ($user): ?>
            <p>Welcome,
                <strong><?= htmlspecialchars($user['full_name'] ?? (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) ?></strong>!
            </p>
            <p>You are logged in as <strong><?= htmlspecialchars($user['role']) ?></strong>.</p>
        <?php else: ?>
            <p>You are not logged in. Please <a href="/pages/login/index.php">login</a>.</p>
        <?php endif; ?>

</body>
</div>

</html>