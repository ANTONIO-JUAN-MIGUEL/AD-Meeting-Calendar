<?php
declare(strict_types=1);
define('UTILS_PATH', __DIR__);

require 'vendor/autoload.php';
require 'bootstrap.php';
require_once UTILS_PATH . '/envSetter.util.php';

$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];

$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// 1. CREATE all tables
$modelFiles = [
    'users.model.sql',
    'meetings.model.sql',
    'tasks.model.sql',
    'meeting_users.model.sql'
];

foreach ($modelFiles as $modelFile) {
    echo "Applying schema from database/{$modelFile}…\n";
    $sql = file_get_contents("database/{$modelFile}");

    if ($sql === false) {
        throw new RuntimeException("❌ Could not read database/{$modelFile}");
    }

    $pdo->exec($sql);
    echo "✅ Created from {$modelFile}\n";
}

// 2. THEN TRUNCATE (now tables exist)
echo "Truncating tables…\n";
foreach (['meeting_users', 'tasks', 'meetings', 'users'] as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

echo "✅ Tables truncated.\n";

echo "✅ Database reset completed!\n";
