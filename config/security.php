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
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'CLI');
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $url = $_SERVER['REQUEST_URI'] ?? '';
    $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, url, user_agent) VALUES (?,?,?,?,?)');
    $stmt->execute([$userId, $action, $ip, $url, $ua]);
}

function add_notification(PDO $pdo, $userId = null, $message, $type='system')
{
    $stmt = $pdo->prepare('INSERT INTO notifications (user_id, message, notif_type) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $message, strtolower($type)]);
}

// Generate maintenance due/overdue notifications (runs once per request)
function generateMaintenanceNotifications(PDO $pdo)
{
    // Find maintenance records whose next_date is overdue or today
    $stmt = $pdo->query("SELECT m.id, m.type, m.next_date, v.brand, v.serial_number FROM maintenance m JOIN vehicles v ON m.vehicle_id = v.id WHERE m.next_date IS NOT NULL AND m.next_date <= CURDATE()");
    $check = $pdo->prepare('SELECT id FROM notifications WHERE message = ? LIMIT 1');
    foreach ($stmt->fetchAll() as $row) {
        $msg = sprintf('%s maintenance due for %s (%s) on %s', $row['type'], $row['brand'], $row['serial_number'], $row['next_date']);
        $check->execute([$msg]);
        if (!$check->fetch()) {
            add_notification($pdo, null, $msg, 'Maintenance');
        }
    }
}

// Run generator once per request (after DB connection ready)
generateMaintenanceNotifications($pdo);

if(!function_exists('get_setting')){
function get_setting(PDO $pdo,$key){
  static $stm=null;
  if(!$stm){$stm=$pdo->prepare('SELECT value FROM settings WHERE `key`=? LIMIT 1');}
  $stm->execute([$key]);
  return $stm->fetchColumn();
}}
?>