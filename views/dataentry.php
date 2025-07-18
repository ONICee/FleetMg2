<?php
$title = 'Data Entry Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_DATA_ENTRY);
include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome, Data Entry Officer!</h1>
<p class="lead">You can add vehicles and maintenance data from the sidebar.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>