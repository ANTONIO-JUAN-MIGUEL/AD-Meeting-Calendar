<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'userView.util.php';
require_once UTILS_PATH . 'auth.util.php';

Auth::init();
$user = Auth::user();

$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];
$pdo = new PDO("pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}", $pgConfig['user'], $pgConfig['pass']);

$users = UsersDatabase::viewAll($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>
    <?php include_once BASE_PATH . '/components/partials/navbar.php'; ?>
    <div class="main-container">

        <h1>User List</h1>

        <?php if (!$users): ?>
            <p style="color: red;">Please login first.</p>
        <?php else: ?>
            <table border="1" cellpadding="6">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                            <td><?= htmlspecialchars($u['role']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

</body>
</div>

</html>