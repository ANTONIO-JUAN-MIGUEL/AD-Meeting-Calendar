<?php
require_once __DIR__ . '/../utils/envSetter.util.php';

$connStr = sprintf(
    "host=%s port=%s dbname=%s user=%s password=%s",
    $typeConfig['pg_host'],
    $typeConfig['pg_port'],
    $typeConfig['pg_db'],
    $typeConfig['pg_user'],
    $typeConfig['pg_pass']
);

$conn = pg_connect($connStr);

if ($conn) {
    echo "✅ PostgreSQL Connection<br>";
} else {
    echo "❌ Connection Failed: " . pg_last_error() . "<br>";
}
