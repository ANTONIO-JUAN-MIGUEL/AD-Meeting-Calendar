<?php
require_once __DIR__ . '/../utils/envSetter.util.php';

try {
    $mongo = new MongoDB\Driver\Manager($typeConfig['mongo_uri']);

    $command = new MongoDB\Driver\Command(["ping" => 1]);
    $mongo->executeCommand($typeConfig['mongo_db'], $command);

    echo "✅ Connected to MongoDB successfully.<br>";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "❌ MongoDB connection failed: " . $e->getMessage() . "<br>";
}
