<?php
require_once 'bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Service Status</title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>

    <?php include_once BASE_PATH . '/components/partials/navbar.php'; ?>
    <div class="main-container">

        <h1>Service Status</h1>

        <h2>MongoDB:</h2>
        <?php include HANDLERS_PATH . 'mongodbChecker.handler.php'; ?>

        <h2>PostgreSQL:</h2>
        <?php include HANDLERS_PATH . 'postgreChecker.handler.php'; ?>

        <h2>Automation:</h2>
        <ul>
            <li>✅ PostgreSQL Reset script is working via <code>composer postgresql:reset</code></li>
            <li>✅ PostgreSQL Seeder script is working via <code>composer postgresql:seed</code></li>
            <li>✅ PostgreSQL Migration script is working via <code>composer postgresql:migrate</code></li>
        </ul>
    </div>
</body>

</html>