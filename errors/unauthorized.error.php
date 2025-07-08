<?php
require_once '../bootstrap.php';

$title = 'Unauthorized';
ob_start();
?>

<h1>Unauthorized</h1>
<p class="error">You must be logged in to view this page.</p>
<p><a href="/pages/login/index.php">Go to login</a></p>

<?php
$content = ob_get_clean();
include LAYOUTS_PATH . 'main.layout.php';
?>