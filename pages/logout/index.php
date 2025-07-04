<?php
require_once '../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
Auth::logout();
header('Location: /pages/login/index.php');
exit;
