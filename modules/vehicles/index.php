<?php
$title = 'Vehicles';
require_once __DIR__ . '/../../config/security.php';
require_login();

// Only Admin, Super Admin, or Data Entry can access
if ($_SESSION['user']['role_id'] > ROLE_DATA_ENTRY) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

// Filters
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
$vehicles = $stmt->fetchAll();

include __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Vehicle Registry</h2>
  <div>
    <a href="export_csv.php?brand=<?= urlencode($brand) ?>&agency=<?= urlencode($agency) ?>&location=<?= urlencode($location) ?>" class="btn btn-outline-secondary me-2"><i class="bi bi-download"></i> CSV</a>
    <a href="create.php" class="btn btn-brand">Add Vehicle</a>
  </div>
</div>

<form class="row g-2 mb-4" method="get">
  <div class="col-md-3"><input type="text" name="brand" value="<?= $brand ?>" placeholder="Brand" class="form-control"></div>
  <div class="col-md-3"><input type="text" name="agency" value="<?= $agency ?>" placeholder="Agency" class="form-control"></div>
  <div class="col-md-3"><input type="text" name="location" value="<?= $location ?>" placeholder="Location" class="form-control"></div>
  <div class="col-md-3 d-grid"><button class="btn btn-dark">Filter</button></div>
</form>

<table class="table table-bordered table-hover table-sm datatable">
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