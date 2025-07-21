<?php
$title = 'General Settings';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);

// helper to get setting
function get_setting(PDO $pdo, $key) {
  $s = $pdo->prepare('SELECT value FROM settings WHERE `key` = ?');
  $s->execute([$key]);
  return $s->fetchColumn();
}

$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scheduled = intval($_POST['scheduled_interval_months'] ?? 3);
    $overhaul  = intval($_POST['overhaul_interval_months'] ?? 12);
    $update = $pdo->prepare('UPDATE settings SET value = ? WHERE `key` = ?');
    $update->execute([$scheduled, 'scheduled_interval_months']);
    $update->execute([$overhaul,  'overhaul_interval_months']);
    log_action($pdo, $_SESSION['user']['id'], 'Updated general settings');
    $messages[] = 'Settings updated.';
}
$scheduled = get_setting($pdo, 'scheduled_interval_months');
$overhaul  = get_setting($pdo, 'overhaul_interval_months');
include __DIR__ . '/../includes/header.php';
?>
<h2>General Settings</h2>
<?php foreach ($messages as $msg): ?>
  <div class="alert alert-success"><?= $msg ?></div>
<?php endforeach; ?>
<form method="post" class="row g-3 w-50">
  <div class="col-md-6">
    <label class="form-label">Scheduled Maintenance Interval (months)</label>
    <input type="number" name="scheduled_interval_months" value="<?= $scheduled ?>" class="form-control" min="1" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Overhaul Interval (months)</label>
    <input type="number" name="overhaul_interval_months" value="<?= $overhaul ?>" class="form-control" min="1" required>
  </div>
  <div class="col-12">
    <button class="btn btn-brand">Save</button>
  </div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>