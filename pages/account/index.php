<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

Auth::init();
$user = Auth::user();

if (!$user) {
    header('Location: /pages/login/index.php');
    exit;
}

ob_start();
?>

<!-- your HTML inside this block -->
<h1>Account Page</h1>
<p>Welcome, <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>!</p>
<p>Your role is <strong><?= htmlspecialchars($user['role']) ?></strong>.</p>

<?php
$content = ob_get_clean();
include BASE_PATH . '/layouts/main.layout.php';
