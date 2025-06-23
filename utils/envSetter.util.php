<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

require_once VENDOR_PATH . 'autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
try {
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    // Try alternative path if first attempt fails
    $dotenv = Dotenv\Dotenv::createImmutable('/var/www/html');
    $dotenv->load();
}

$typeConfig = [
    'pg_host' => $_ENV['PG_HOST'],
    'pg_port' => $_ENV['PG_PORT'],
    'pg_db' => $_ENV['PG_DB'],
    'pg_user' => $_ENV['PG_USER'],
    'pg_pass' => $_ENV['PG_PASS'],
    'mongo_uri' => $_ENV['MONGO_URI'],
    'mongo_db' => $_ENV['MONGO_DB'],
];