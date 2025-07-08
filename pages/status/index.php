<?php
require_once '../../bootstrap.php';

ob_start();
?>

<h1>Service Status</h1>

<h2>MongoDB:</h2>
<?php include HANDLERS_PATH . 'mongodbChecker.handler.php'; ?>

<h2>PostgreSQL:</h2>
<?php include HANDLERS_PATH . 'postgreChecker.handler.php'; ?>

<h2>Automation:</h2>
<ul>
    <li>✅ PostgreSQL Reset USING DOCKER is working via:
        <code>docker exec -it web-meeting-calendar-service php utils/dbResetPostgresql.util.php</code>
    </li>
    <li>✅ PostgreSQL Seeder USING DOCKER is working via:
        <code>docker exec -it web-meeting-calendar-service php utils/dbSeederPostgresql.util.php</code>
    </li>
    <li>✅ PostgreSQL Migration USING DOCKER is working via:
        <code>docker exec -it web-meeting-calendar-service php utils/dbMigratePostgresql.util.php</code>
    </li>
</ul>

<?php
$content = ob_get_clean();
include BASE_PATH . '/layouts/main.layout.php';
