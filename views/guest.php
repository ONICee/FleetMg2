<?php
$title = 'Guest Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_GUEST);
include __DIR__ . '/../includes/header.php';
?>
<h1>Welcome, Guest!</h1>
<p class="lead">Your access is read-only. Contact an administrator for additional permissions.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>