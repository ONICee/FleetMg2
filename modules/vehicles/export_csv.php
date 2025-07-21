<?php
require_once __DIR__ . '/../../config/security.php';
require_login();
if ($_SESSION['user']['role_id'] > ROLE_ADMIN) {
  header('HTTP/1.1 403 Forbidden');
  exit('Access denied');
}
$brand = sanitize($_GET['brand'] ?? '');
$agency = sanitize($_GET['agency'] ?? '');
$location = sanitize($_GET['location'] ?? '');
$sql = 'SELECT * FROM vehicles WHERE 1';
$params = [];
if ($brand)   { $sql .= ' AND brand LIKE ?';       $params[] = "%$brand%"; }
if ($agency)  { $sql .= ' AND agency LIKE ?';      $params[] = "%$agency%"; }
if ($location){ $sql .= ' AND location LIKE ?';    $params[] = "%$location%"; }
$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=vehicles_' . date('Ymd_His') . '.csv');
$out = fopen('php://output', 'w');
if ($rows) {
  fputcsv($out, array_keys($rows[0]));
  foreach ($rows as $r) {
    fputcsv($out, $r);
  }
}
fclose($out);
exit;