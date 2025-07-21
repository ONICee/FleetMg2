<?php
$title = 'Vehicles';
require_once __DIR__ . '/../../config/security.php';
require_login();

// Only Admin, Super Admin, or Data Entry can access
if ($_SESSION['user']['role_id'] > ROLE_DATA_ENTRY) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

// Fetch vehicles
$stmt = $pdo->query('SELECT * FROM vehicles ORDER BY created_at DESC');
$vehicles = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Vehicle Registry</h2>
  <a href="create.php" class="btn btn-brand">Add Vehicle</a>
</div>
<table class="table table-bordered table-hover table-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Brand</th>
      <th>Serial #</th>
      <th>Year</th>
      <th>Agency</th>
      <th>Location</th>
      <th>Serviceability</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($vehicles as $v): ?>
    <tr>
      <td><?= $v['id'] ?></td>
      <td><?= sanitize($v['brand']) ?></td>
      <td><?= sanitize($v['serial_number']) ?></td>
      <td><?= $v['year_allocation'] ?></td>
      <td><?= sanitize($v['agency']) ?></td>
      <td><?= sanitize($v['location']) ?></td>
      <td><?= $v['serviceability'] ?></td>
      <td>
        <a href="view.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-secondary">View</a>
        <a href="edit.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-dark">Edit</a>
        <a href="delete.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this vehicle?');">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$vehicles): ?>
      <tr><td colspan="8" class="text-center">No vehicles found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>