<?php
$title = 'Error';
ob_start();
?>
<h1>Something went wrong</h1>
<p>Please try again later or contact support.</p>
<?php
$pageContent = ob_get_clean();
include BASE_PATH . '/layouts/main.layout.php';
