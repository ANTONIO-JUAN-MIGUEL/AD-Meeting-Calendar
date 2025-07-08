<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
require_once UTILS_PATH . 'userView.util.php';

Auth::init();
$user = Auth::user();

if (!$user) {
    header('Location: /pages/login/index.php');
    exit;
}

$pdo = new PDO(
    "pgsql:host={$_ENV['PG_HOST']};port={$_ENV['PG_PORT']};dbname={$_ENV['PG_DB']}",
    $_ENV['PG_USER'],
    $_ENV['PG_PASS']
);

$users = UsersDatabase::viewAll($pdo);

ob_start();
?>

<h1>User List</h1>

<?php if (empty($users)): ?>
    <p style="color: red;">No users found.</p>
<?php else: ?>
    <table>
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

<?php
$content = ob_get_clean();
include BASE_PATH . '/layouts/main.layout.php';
