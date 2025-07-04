<?php
require_once UTILS_PATH . 'envSetter.util.php';

// Detect Docker
$isDocker = file_exists('/.dockerenv');

// Inside Docker, override host and port
$host = $isDocker && $_ENV['PG_HOST'] === 'localhost' ? 'postgresql' : $_ENV['PG_HOST'];
$port = $isDocker && $_ENV['PG_PORT'] === '5556' ? '5432' : $_ENV['PG_PORT'];

$connStr = sprintf(
    "host=%s port=%s dbname=%s user=%s password=%s",
    $host,
    $port,
    $_ENV['PG_DB'],
    $_ENV['PG_USER'],
    $_ENV['PG_PASS']
);

$conn = pg_connect($connStr);

if ($conn) {
    echo "✅ Connected to PostgreSQL successfully.<br>";
} else {
    $error = error_get_last();
    echo "❌ Connection Failed: " . ($error ? $error['message'] : 'Unknown error') . "<br>";
}
