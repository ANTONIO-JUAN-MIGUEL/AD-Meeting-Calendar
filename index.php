<!DOCTYPE html>
<html>

<head>
    <title>Checker</title>
</head>

<body>
    <h1>Service Status (after doing step 5 to 6)</h1>

    <h2>MongoDB:</h2>
    <?php include 'handlers/mongodbChecker.handler.php'; ?>

    <h2>PostgreSQL:</h2>
    <?php include 'handlers/postgreChecker.handler.php'; ?>
</body>

</html>