<?php
$title = 'Vehicle Details';
require_once __DIR__ . '/../../config/security.php';
require_login();
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = ?');
$stmt->execute([$id]);
$vehicle = $stmt->fetch();
if (!$vehicle) {
    exit('Vehicle not found');
}
// Maintenance records
$mStmt = $pdo->prepare('SELECT * FROM maintenance WHERE vehicle_id = ? ORDER BY maintenance_date DESC');
$mStmt->execute([$id]);
$maintenance = $mStmt->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<a href="index.php" class="btn btn-secondary mb-3">&larr; Back</a>
<h2><?= sanitize($vehicle['brand']) ?> – <?= sanitize($vehicle['serial_number']) ?></h2>
<table class="table table-bordered w-75">
  <tr><th>Year Allocated</th><td><?= $vehicle['year_allocation'] ?></td></tr>
  <tr><th>Engine #</th><td><?= sanitize($vehicle['engine_number']) ?></td></tr>
  <tr><th>Chassis #</th><td><?= sanitize($vehicle['chassis_number']) ?></td></tr>
  <tr><th>Agency</th><td><?= sanitize($vehicle['agency']) ?></td></tr>
  <tr><th>Location</th><td><?= sanitize($vehicle['location']) ?></td></tr>
  <tr><th>Tracker #</th><td><?= sanitize($vehicle['tracker_number']) ?></td></tr>
  <tr><th>Tracker IMEI</th><td><?= sanitize($vehicle['tracker_imei']) ?></td></tr>
  <tr><th>Tracker Status</th><td><?= $vehicle['tracker_status'] ?></td></tr>
  <tr><th>Serviceability</th><td><?= $vehicle['serviceability'] ?></td></tr>
</table>

<?php
$lastSched = $pdo->prepare("SELECT maintenance_date,next_date FROM maintenance WHERE vehicle_id=? AND type='Scheduled' ORDER BY maintenance_date DESC LIMIT 1");
$lastSched->execute([$id]);
$ls = $lastSched->fetch();

$lastOver = $pdo->prepare("SELECT maintenance_date,next_date FROM maintenance WHERE vehicle_id=? AND type='Overhaul' ORDER BY maintenance_date DESC LIMIT 1");
$lastOver->execute([$id]);
$lo = $lastOver->fetch();
?>

<h4 class="mt-4">Maintenance Schedule</h4>
<table class="table table-bordered w-50">
  <tr><th>Last Scheduled Maintenance</th><td><?= $ls['maintenance_date'] ?? '-' ?></td></tr>
  <tr><th>Next Scheduled Maintenance</th><td><?= $ls['next_date'] ?? '-' ?></td></tr>
  <tr><th>Last Overhaul</th><td><?= $lo['maintenance_date'] ?? '-' ?></td></tr>
  <tr><th>Next Overhaul</th><td><?= $lo['next_date'] ?? '-' ?></td></tr>
</table>
<div class="d-flex justify-content-between align-items-center mb-2">
  <h3>Maintenance History</h3>
  <?php if ($_SESSION['user']['role_id'] <= ROLE_DATA_ENTRY): ?>
    <a href="../maintenance/add.php?vehicle_id=<?= $id ?>" class="btn btn-brand btn-sm">Add Maintenance</a>
  <?php endif; ?>
</div>
<table class="table table-bordered table-sm">
  <thead class="table-light">
    <tr>
      <th>Date</th><th>Type</th><th>Description</th><th>Next Due</th><th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($maintenance as $m): ?>
      <?php
        $overdue = ($m['next_date'] && $m['next_date'] < date('Y-m-d'));
        $rowClass = $overdue ? 'table-danger' : '';
      ?>
      <tr class="<?= $rowClass ?>">
        <td><?= $m['maintenance_date'] ?></td>
        <td><?= $m['type'] ?></td>
        <td><?= nl2br(sanitize($m['description'])) ?></td>
        <td><?= $m['next_date'] ?></td>
        <td class="text-center">
          <?php if($overdue): ?>
            <span class="text-danger"><i class="fa fa-exclamation-circle"></i> Overdue</span>
          <?php else: ?>
            <span class="text-success"><i class="fa fa-check-circle"></i></span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$maintenance): ?>
      <tr><td colspan="5" class="text-center">No maintenance records.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>