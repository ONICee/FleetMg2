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
$serial = sanitize($_GET['serial'] ?? '');
$assignedParam = isset($_GET['assigned']);
$gps = sanitize($_GET['gps'] ?? '');

$sql = 'SELECT * FROM vehicles WHERE 1';
$params = [];
if ($brand)   { $sql .= ' AND brand LIKE ?';       $params[] = "%$brand%"; }
if ($agency)  { $sql .= ' AND agency LIKE ?';      $params[] = "%$agency%"; }
if ($location){ $sql .= ' AND location LIKE ?';    $params[] = "%$location%"; }
if ($serial){ $sql .= ' AND serial_number LIKE ?'; $params[] = "%$serial%"; }
if ($assignedParam){ $sql .= " AND agency IS NOT NULL AND agency <> ''"; }
$sql .= ' ORDER BY created_at DESC';
if($gps=='yes'){$sql.=' AND last_lat IS NOT NULL';}
if($gps=='no'){$sql.=' AND last_lat IS NULL';}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicles = $stmt->fetchAll();

$breadcrumbs=['Dashboard'=>BASE_URL,'Vehicles'=>null];

include __DIR__ . '/../../includes/header.php';
?>
<?php
// KPI counts
$countInUse    = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE serviceability='In Use'")->fetchColumn();
$countOffRoad  = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE serviceability='Off-Road'")->fetchColumn();
$countWithGPS  = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE last_lat IS NOT NULL")->fetchColumn();
$countNoGPS    = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE last_lat IS NULL")->fetchColumn();
?>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <a href="index.php?serviceability=In+Use" class="text-decoration-none text-dark">
      <div class="card card-stat text-center" data-color="green">
        <div class="card-body">
          <h5 class="card-title">Serviceable (In-Use)</h5>
          <h2><?= $countInUse ?></h2>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="index.php?serviceability=Off-Road" class="text-decoration-none text-dark">
      <div class="card card-stat text-center" data-color="red">
        <div class="card-body">
          <h5 class="card-title">Un-serviceable</h5>
          <h2><?= $countOffRoad ?></h2>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="index.php?gps=yes" class="text-decoration-none text-dark">
      <div class="card card-stat text-center" data-color="cyan">
        <div class="card-body">
          <h5 class="card-title">With GPS Fix</h5>
          <h2><?= $countWithGPS ?></h2>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="index.php?gps=no" class="text-decoration-none text-dark">
      <div class="card card-stat text-center" data-color="blue">
        <div class="card-body">
          <h5 class="card-title">No GPS Fix</h5>
          <h2><?= $countNoGPS ?></h2>
        </div>
      </div>
    </a>
  </div>
</div>

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
  <div class="col-md-3"><input type="text" name="serial" value="<?= $serial ?>" placeholder="Serial #" class="form-control"></div>
  <div class="col-md-2 d-grid"><button class="btn btn-dark">Filter</button></div>
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