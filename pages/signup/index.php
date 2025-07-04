<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'signup.util.php';
require_once UTILS_PATH . 'envSetter.util.php';

$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];

$pdo = new PDO("pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}", $pgConfig['user'], $pgConfig['pass']);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = Signup::validate($_POST);
    if (empty($errors)) {
        try {
            Signup::create($pdo, $_POST);
            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Signup failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>
    <?php include_once BASE_PATH . '/components/partials/navbar.php'; ?>
    <div class="main-container">

        <h1>Signup</h1>

        <?php if ($success): ?>
            <p style="color: green;">Account created successfully. <a href="/pages/login/index.php">Login here</a>.</p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul style="color: red;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST">
            <label>First Name:
                <input type="text" name="first_name" required>
            </label><br><br>

            <label>Middle Name:
                <input type="text" name="middle_name">
            </label><br><br>

            <label>Last Name:
                <input type="text" name="last_name" required>
            </label><br><br>

            <label>Username:
                <input type="text" name="username" required>
            </label><br><br>

            <label>Password:
                <input type="password" name="password" required>
            </label><br><br>

            <label>Role:
                <select name="role" required>
                    <option value="team lead">Team Lead</option>
                    <option value="member">Member</option>
                </select>
            </label><br><br>

            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>

</html>