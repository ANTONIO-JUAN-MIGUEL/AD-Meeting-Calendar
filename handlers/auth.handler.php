<?php
require_once '../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];

$pdo = new PDO("pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}", $pgConfig['user'], $pgConfig['pass']);

try {
    Auth::login($pdo, $_POST['username'], $_POST['password']);
    header('Location: /pages/account/index.php');
} catch (Exception $e) {
    header('Location: /pages/login/index.php?error=' . urlencode($e->getMessage()));
}
exit;
