<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'signup.util.php';
require_once UTILS_PATH . 'envSetter.util.php';

$pdo = new PDO(
    "pgsql:host={$_ENV['PG_HOST']};port={$_ENV['PG_PORT']};dbname={$_ENV['PG_DB']}",
    $_ENV['PG_USER'],
    $_ENV['PG_PASS']
);

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

ob_start();
?>

<h1>Signup</h1>

<?php if ($success): ?>
    <p class="success">Account created successfully. <a href="/pages/login/index.php">Login here</a>.</p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul class="error">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label>First Name:
        <input type="text" name="first_name" required>
    </label>

    <label>Middle Name:
        <input type="text" name="middle_name">
    </label>

    <label>Last Name:
        <input type="text" name="last_name" required>
    </label>

    <label>Username:
        <input type="text" name="username" required>
    </label>

    <label>Password:
        <input type="password" name="password" required>
    </label>

    <label>Role:
        <select name="role" required>
            <option value="team lead">Team Lead</option>
            <option value="member">Member</option>
        </select>
    </label>

    <button type="submit">Sign Up</button>
</form>

<?php
$content = ob_get_clean();
include BASE_PATH . '/layouts/main.layout.php';
