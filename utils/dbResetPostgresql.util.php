<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
require_once UTILS_PATH . '/envSetter.util.php';

$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

echo "Truncating tables…\n";
foreach (['project_users', 'tasks', 'meetings', 'projects', 'users'] as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}
echo "✅ Tables truncated.\n";

// List of model files
$modelFiles = [
    'users.model.sql',
    'projects.model.sql',
    'meetings.model.sql',
    'tasks.model.sql',
    'project_users.model.sql'
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

echo "✅ Database reset completed!\n";
