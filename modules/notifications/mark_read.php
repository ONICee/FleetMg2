<?php
require_once __DIR__ . '/../../config/security.php';
if(!is_logged_in()) exit;
$pdo->prepare('UPDATE notifications SET is_read=1 WHERE user_id = ?')->execute([$_SESSION['user']['id']]);
log_action($pdo,$_SESSION['user']['id'],'Viewed notifications');
exit;