<!DOCTYPE html>
<html>

<head>
    <title>Checker</title>
</head>

<body>
    <h1>Service Status (After Doing Step 9)</h1>

    <h2>MongoDB:</h2>
    <?php include 'handlers/mongodbChecker.handler.php'; ?>

    <h2>PostgreSQL:</h2>
    <?php include 'handlers/postgreChecker.handler.php'; ?>
</body>

</html>