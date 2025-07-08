<?php
declare(strict_types=1);

require_once '../bootstrap.php';
require_once UTILS_PATH . '/envSetter.util.php';
require_once UTILS_PATH . '/auth.util.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sanitize input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if ($username === '' || $password === '') {
    $_SESSION['error'] = 'Username and password are required.';
    header('Location: /pages/login/index.php');
    exit;
}

try {
    $pdo = new PDO(
        "pgsql:host={$_ENV['PG_HOST']};port={$_ENV['PG_PORT']};dbname={$_ENV['PG_DB']}",
        $_ENV['PG_USER'],
        $_ENV['PG_PASS'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    Auth::login($pdo, $username, $password);

    $_SESSION['success'] = 'Login successful.';
    header('Location: /pages/account/index.php');
    exit;


} catch (Exception $e) {
    // Catch both PDO and login errors
    $_SESSION['error'] = $e->getMessage();
    header('Location: /pages/login/index.php');
    exit;
}
