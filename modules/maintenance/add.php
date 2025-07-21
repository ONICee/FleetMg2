<?php
$title = 'Add Maintenance';
require_once __DIR__ . '/../../config/security.php';
require_login();
if ($_SESSION['user']['role_id'] > ROLE_DATA_ENTRY) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
$vehicle_id = intval($_GET['vehicle_id'] ?? 0);
// get vehicle exists
$vehStmt = $pdo->prepare('SELECT brand, serial_number FROM vehicles WHERE id = ?');
$vehStmt->execute([$vehicle_id]);
$vehicle = $vehStmt->fetch();
if (!$vehicle) {
    exit('Vehicle not found');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type        = in_array($_POST['type'] ?? '', ['Scheduled','Unscheduled','Overhaul']) ? $_POST['type'] : '';
    $desc        = sanitize($_POST['description'] ?? '');
    $date        = $_POST['maintenance_date'] ?? '';
    if (!$type || !$date) {
        $errors[] = 'Type and Date are required.';
    }
    if (!$errors) {
        // determine next_date based on settings
        $next_date = null;
        if ($type === 'Scheduled' || $type === 'Overhaul') {
            $intervalKey = $type === 'Scheduled' ? 'scheduled_interval_months' : 'overhaul_interval_months';
            $sStmt = $pdo->prepare('SELECT value FROM settings WHERE `key` = ?');
            $sStmt->execute([$intervalKey]);
            $months = intval($sStmt->fetchColumn());
            if ($months) {
                $next_date = date('Y-m-d', strtotime("$date +$months months"));
            }
        }
        $stmt = $pdo->prepare('INSERT INTO maintenance (vehicle_id,type,description,maintenance_date,next_date,created_by) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$vehicle_id, $type, $desc, $date, $next_date, $_SESSION['user']['id']]);
        log_action($pdo, $_SESSION['user']['id'], "Added $type maintenance to vehicle #$vehicle_id");
        add_notification($pdo, null, "$type maintenance recorded for {$vehicle['brand']} ({$vehicle['serial_number']})", 'Maintenance');
        header('Location: ../vehicles/view.php?id=' . $vehicle_id);
        exit;
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<a href="../vehicles/view.php?id=<?= $vehicle_id ?>" class="btn btn-secondary mb-3">&larr; Back</a>
<h2>Add Maintenance – <?= sanitize($vehicle['brand']) ?> (<?= sanitize($vehicle['serial_number']) ?>)</h2>
<?php foreach ($errors as $e): ?>
  <div class="alert alert-danger"><?= $e ?></div>
<?php endforeach; ?>
<form method="post" class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Type *</label>
    <select name="type" class="form-select" required>
      <option value="Scheduled">Scheduled (every 3 months)</option>
      <option value="Unscheduled">Unscheduled</option>
      <option value="Overhaul">Overhaul (annual)</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Maintenance Date *</label>
    <input type="date" name="maintenance_date" class="form-control" required value="<?= date('Y-m-d') ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Description / Notes</label>
    <textarea name="description" class="form-control" rows="4"></textarea>
  </div>
  <div class="col-12">
    <button class="btn btn-brand">Save</button>
  </div>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>