<?php
declare(strict_types=1);
define('UTILS_PATH', __DIR__);

require 'vendor/autoload.php';
require 'bootstrap.php';
require_once UTILS_PATH . '/envSetter.util.php';

// PostgreSQL config
$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db' => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];

// Connect to PostgreSQL
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

//
// ===== USERS =====
echo "🌱 Seeding users…\n";
$users = require_once DUMMIES_PATH . '/users.staticData.php';

$stmt = $pdo->prepare("
    INSERT INTO users (username, role, full_name, password)
    VALUES (:username, :role, :full_name, :password)
");

foreach ($users as $u) {
    $stmt->execute([
        ':username' => $u['username'],
        ':role' => $u['role'],
        ':full_name' => $u['first_name'] . ' ' . $u['last_name'],
        ':password' => password_hash($u['password'], PASSWORD_DEFAULT),
    ]);
}
echo "✅ Seeded users.\n";

//
// ===== MEETINGS =====
echo "🌱 Seeding meetings…\n";
$meetings = require_once DUMMIES_PATH . '/meetings.staticData.php';

$stmt = $pdo->prepare("
    INSERT INTO meetings (title, agenda, scheduled_at)
    VALUES (:title, :agenda, :scheduled_at)
");

foreach ($meetings as $m) {
    $stmt->execute([
        ':title' => $m['title'],
        ':agenda' => $m['agenda'],
        ':scheduled_at' => $m['scheduled_at'],
    ]);
}
echo "✅ Seeded meetings.\n";

//
// ===== TASKS =====
echo "🌱 Seeding tasks…\n";
$tasks = require_once DUMMIES_PATH . '/tasks.staticData.php';

$stmt = $pdo->prepare("
    INSERT INTO tasks (assigned_to, title, description, status, due_date)
    VALUES (:assigned_to, :title, :description, :status, :due_date)
");

foreach ($tasks as $t) {
    $stmt->execute([
        ':assigned_to' => $t['assigned_to'],
        ':title' => $t['title'],
        ':description' => $t['description'],
        ':status' => $t['status'],
        ':due_date' => $t['due_date'],
    ]);
}
echo "✅ Seeded tasks.\n";

//
// ===== MEETING_USERS =====
echo "🌱 Seeding meeting_users…\n";
$meetingUsers = require_once DUMMIES_PATH . '/meeting_users.staticData.php';

$stmt = $pdo->prepare("
    INSERT INTO meeting_users (meeting_id, user_id, role)
    VALUES (:meeting_id, :user_id, :role)
");

foreach ($meetingUsers as $mu) {
    $stmt->execute([
        ':meeting_id' => $mu['meeting_id'],
        ':user_id' => $mu['user_id'],
        ':role' => $mu['role'],
    ]);
}
echo "✅ Seeded meeting_users.\n";

//
// ✅ Done
echo "✅ PostgreSQL seeding complete!\n";
