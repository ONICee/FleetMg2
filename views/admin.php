<?php
$title = 'Admin Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_ADMIN);
include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome, Admin!</h1>
<p class="lead">Use the sidebar to manage vehicles and maintenance records.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>