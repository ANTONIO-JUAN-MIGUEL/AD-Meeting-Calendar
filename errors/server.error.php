<?php
require_once '../bootstrap.php';

$title = 'Server Error';
ob_start();
?>

<h1>500 - Server Error</h1>
<p class="error">Something went wrong on our end. Please try again later.</p>
<p><a href="/">Return to home</a></p>

<?php
$content = ob_get_clean();
include LAYOUTS_PATH . 'main.layout.php';
?>