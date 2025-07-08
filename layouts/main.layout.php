<?php
require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
Auth::init(); // ✅ REQUIRED so session starts on all pages
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Meeting Calendar</title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>
    <?php include COMPONENTS_PATH . 'shared/navbar.component.php'; ?>

    <div class="main-container">
        <?= $content ?? '' ?>
    </div>
</body>

</html>