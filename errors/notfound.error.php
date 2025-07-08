<?php
require_once '../bootstrap.php';

$title = 'Page Not Found';
ob_start();
?>

<h1>404 - Page Not Found</h1>
<p class="error">Sorry, the page you are looking for does not exist.</p>
<p><a href="/">Return to home</a></p>

<?php
$content = ob_get_clean();
include LAYOUTS_PATH . 'main.layout.php';
?>