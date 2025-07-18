<?php
require_once __DIR__ . '/../config/security.php';

if (is_logged_in()) {
    log_action($pdo, $_SESSION['user']['id'], 'Logged out');
}

session_destroy();
header('Location: ' . BASE_URL . 'auth/login.php');
exit;