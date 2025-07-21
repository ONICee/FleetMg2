<?php
$title = 'Edit Vehicle';
require_once __DIR__ . '/../../config/security.php';
require_login();
if ($_SESSION['user']['role_id'] > ROLE_DATA_ENTRY) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = ?');
$stmt->execute([$id]);
$vehicle = $stmt->fetch();
if (!$vehicle) {
    exit('Vehicle not found');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand           = sanitize($_POST['brand'] ?? '');
    $serial          = sanitize($_POST['serial_number'] ?? '');
    $year            = intval($_POST['year_allocation'] ?? 0);
    $engine          = sanitize($_POST['engine_number'] ?? '');
    $chassis         = sanitize($_POST['chassis_number'] ?? '');
    $tracker_num     = sanitize($_POST['tracker_number'] ?? '');
    $tracker_imei    = sanitize($_POST['tracker_imei'] ?? '');
    $agency          = sanitize($_POST['agency'] ?? '');
    $location        = sanitize($_POST['location'] ?? '');
    $tracker_status  = ($_POST['tracker_status'] ?? 'Active') === 'Inactive' ? 'Inactive' : 'Active';
    $serviceability  = ($_POST['serviceability'] ?? 'In Use') === 'Off-Road' ? 'Off-Road' : 'In Use';

    if (!$brand || !$serial) {
        $errors[] = 'Brand and Serial Number are required.';
    }
    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE vehicles SET brand=?, serial_number=?, year_allocation=?, engine_number=?, chassis_number=?, tracker_number=?, tracker_imei=?, agency=?, location=?, tracker_status=?, serviceability=? WHERE id=?');
        $stmt->execute([$brand, $serial, $year ?: NULL, $engine, $chassis, $tracker_num, $tracker_imei, $agency, $location, $tracker_status, $serviceability, $id]);
        log_action($pdo, $_SESSION['user']['id'], "Edited vehicle #$id");
        header('Location: view.php?id=' . $id);
        exit;
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<h2>Edit Vehicle</h2>
<?php foreach ($errors as $e): ?>
  <div class="alert alert-danger"><?= $e ?></div>
<?php endforeach; ?>
<form method="post" class="row g-3">
  <?php function val($key,$default='') { global $vehicle; return sanitize($_POST[$key] ?? $vehicle[$key] ?? $default); } ?>
  <div class="col-md-4">
    <label class="form-label">Brand *</label>
    <input type="text" name="brand" value="<?= val('brand') ?>" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Serial Number *</label>
    <input type="text" name="serial_number" value="<?= val('serial_number') ?>" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Year of Allocation</label>
    <input type="number" name="year_allocation" value="<?= val('year_allocation') ?>" min="1900" max="<?= date('Y') ?>" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Engine Number</label>
    <input type="text" name="engine_number" value="<?= val('engine_number') ?>" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Chassis Number</label>
    <input type="text" name="chassis_number" value="<?= val('chassis_number') ?>" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Tracker Number</label>
    <input type="text" name="tracker_number" value="<?= val('tracker_number') ?>" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Tracker IMEI</label>
    <input type="text" name="tracker_imei" value="<?= val('tracker_imei') ?>" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Assigned Agency</label>
    <input type="text" name="agency" value="<?= val('agency') ?>" class="form-control">
  </div>
  <div class="col-md-4">
    <label class="form-label">Deployment Location</label>
    <input type="text" name="location" value="<?= val('location') ?>" class="form-control">
  </div>
  <div class="col-md-3">
    <label class="form-label">Tracker Status</label>
    <select name="tracker_status" class="form-select">
      <option value="Active" <?= val('tracker_status')=='Active'?'selected':'' ?>>Active</option>
      <option value="Inactive" <?= val('tracker_status')=='Inactive'?'selected':'' ?>>Inactive</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Serviceability</label>
    <select name="serviceability" class="form-select">
      <option value="In Use" <?= val('serviceability')=='In Use'?'selected':'' ?>>In Use</option>
      <option value="Off-Road" <?= val('serviceability')=='Off-Road'?'selected':'' ?>>Off-Road</option>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-brand">Update Vehicle</button>
    <a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
  </div>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>