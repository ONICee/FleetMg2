<?php
require_once __DIR__ . '/../../config/security.php';
require_login();
if ($_SESSION['user']['role_id'] > ROLE_ADMIN) { // Only admins and super admins can delete
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT id, brand, serial_number FROM vehicles WHERE id = ?');
$stmt->execute([$id]);
$v = $stmt->fetch();
if (!$v) {
    exit('Vehicle not found');
}
// Delete vehicle (maintenance rows will cascade)
$stmt = $pdo->prepare('DELETE FROM vehicles WHERE id = ?');
$stmt->execute([$id]);
log_action($pdo, $_SESSION['user']['id'], "Deleted vehicle #$id");
add_notification($pdo, null, "Vehicle removed: {$v['brand']} ({$v['serial_number']})");
header('Location: index.php');
exit;