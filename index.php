<?php
require_once __DIR__ . '/config/security.php';
require_login();

switch ($_SESSION['user']['role_id']) {
    case ROLE_SUPER_ADMIN:
        header('Location: ' . BASE_URL . 'views/superadmin.php');
        break;
    case ROLE_ADMIN:
        header('Location: ' . BASE_URL . 'views/admin.php');
        break;
    case ROLE_DATA_ENTRY:
        header('Location: ' . BASE_URL . 'views/dataentry.php');
        break;
    default:
        header('Location: ' . BASE_URL . 'views/guest.php');
        break;
}
exit;