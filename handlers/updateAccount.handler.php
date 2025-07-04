<?php
declare(strict_types=1);
include_once '../bootstrap.php';
include_once UTILS_PATH . '/updateAccount.util.php';
include_once UTILS_PATH . '/envSetter.util.php';

$pdo = new PDO(
    "pgsql:host={$_ENV['PG_HOST']};port={$_ENV['PG_PORT']};dbname={$_ENV['PG_DB']}",
    $_ENV['PG_USER'],
    $_ENV['PG_PASS']
);

$errors = UpdateAccount::validate($pdo, $_POST, $_FILES['profile_image'] ?? null);

if (!empty($errors)) {
    // redirect with error message
    header('Location: /pages/account/index.php?error=update');
    exit;
}

UpdateAccount::apply($pdo, $_POST, $_FILES['profile_image'] ?? null);
header('Location: /pages/account/index.php?success=1');
exit;
