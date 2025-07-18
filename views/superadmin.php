<?php
$title = 'Super Admin Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);
include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome, Super Admin!</h1>
<p class="lead">Quick stats and shortcuts will appear here.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>