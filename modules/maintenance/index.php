<?php
$title = 'Maintenance Records';
require_once __DIR__ . '/../../config/security.php';
require_login();
if ($_SESSION['user']['role_id'] > ROLE_ADMIN) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
$q = $pdo->query('SELECT m.*, v.brand, v.serial_number FROM maintenance m JOIN vehicles v ON m.vehicle_id = v.id ORDER BY m.maintenance_date DESC');
$rows = $q->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<h2>All Maintenance Records</h2>
<table class="table table-bordered table-sm">
  <thead class="table-dark">
    <tr>
      <th>Date</th><th>Vehicle</th><th>Type</th><th>Description</th><th>Next Due</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= $r['maintenance_date'] ?></td>
        <td><a href="../vehicles/view.php?id=<?= $r['vehicle_id'] ?>"><?= sanitize($r['brand']) ?> (<?= sanitize($r['serial_number']) ?>)</a></td>
        <td><?= $r['type'] ?></td>
        <td><?= nl2br(sanitize($r['description'])) ?></td>
        <td><?= $r['next_date'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
      <tr><td colspan="5" class="text-center">No records.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>