<?php
define('BASE_PATH', realpath(__DIR__));
define('UTILS_PATH', BASE_PATH . '/utils/');
define('VENDOR_PATH', BASE_PATH . '/vendor/');
define('HANDLERS_PATH', BASE_PATH . '/handlers/');
define('DUMMIES_PATH', BASE_PATH . '/staticDatas/dummies/');
define('COMPONENTS_PATH', BASE_PATH . '/components/');
define('LAYOUTS_PATH', BASE_PATH . '/layouts/');
define('TEMPLATES_PATH', COMPONENTS_PATH . 'templates/');
define('SHARED_PATH', COMPONENTS_PATH . 'shared/');
define('ERRORS_PATH', BASE_PATH . '/errors/');

chdir(BASE_PATH);

// ✅ Load .env
require_once VENDOR_PATH . '/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
