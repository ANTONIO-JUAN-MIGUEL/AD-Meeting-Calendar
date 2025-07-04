<?php
declare(strict_types=1);
include_once '../bootstrap.php';
include_once UTILS_PATH . '/signup.util.php';
include_once UTILS_PATH . '/envSetter.util.php';

$pdo = new PDO(
    "pgsql:host={$_ENV['PG_HOST']};port={$_ENV['PG_PORT']};dbname={$_ENV['PG_DB']}",
    $_ENV['PG_USER'],
    $_ENV['PG_PASS']
);

$errors = Signup::validate($_POST);
if (!empty($errors)) {
    // Redirect back with errors (can also use sessions)
    header('Location: /pages/signup/index.php?error=validation');
    exit;
}

try {
    Signup::create($pdo, $_POST);
    header('Location: /pages/login/index.php?signup=success');
    exit;
} catch (PDOException $e) {
    header('Location: /pages/signup/index.php?error=exists');
    exit;
}
