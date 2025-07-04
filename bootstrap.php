<?php
define('BASE_PATH', realpath(__DIR__));

if (!defined('UTILS_PATH')) {
    define('UTILS_PATH', BASE_PATH . '/utils/');
}

define('VENDOR_PATH', BASE_PATH . '/vendor/');
define('HANDLERS_PATH', BASE_PATH . '/handlers/');
define('DUMMIES_PATH', BASE_PATH . '/staticDatas/dummies/');

chdir(BASE_PATH);

