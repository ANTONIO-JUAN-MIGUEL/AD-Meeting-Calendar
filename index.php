<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Service Status</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
    <h1>Service Status</h1>

    <h2>MongoDB:</h2>
    <ul>
        <li><?php include HANDLERS_PATH . 'mongodbChecker.handler.php'; ?></li>
    </ul>

    <h2>PostgreSQL:</h2>
    <ul>
        <li><?php include HANDLERS_PATH . 'postgreChecker.handler.php'; ?></li>
    </ul>

    <h2>Automation:</h2>
    <ul>
        <li>✅ PostgreSQL Reset script is working via: <code>composer postgresql:reset</code></li>
        <li>✅ PostgreSQL Seeder script is working via: <code>composer postgresql:seed</code></li>
        <li>✅ PostgreSQL Migration script is working via: <code>composer postgresql:migrate</code></li>
    </ul>
</body>

</html>