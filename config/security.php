<?php
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/../db/connection.php';

// -------------------------
// Secure session settings
// -------------------------
if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// -------------------------
// Utility functions
// -------------------------
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function is_logged_in()
{
    return isset($_SESSION['user']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit;
    }
}

function has_role($roleId)
{
    return is_logged_in() && $_SESSION['user']['role_id'] == $roleId;
}

function require_role($roleId)
{
    if (!has_role($roleId)) {
        header('HTTP/1.1 403 Forbidden');
        exit('Access denied');
    }
}

function log_action(PDO $pdo, $userId, $action)
{
    $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address) VALUES (?,?,?)');
    $stmt->execute([$userId, $action, $_SERVER['REMOTE_ADDR'] ?? 'CLI']);
}

function add_notification(PDO $pdo, $userId = null, $message)
{
    $stmt = $pdo->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)');
    $stmt->execute([$userId, $message]);
}
?>