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

    $trackBase   = sanitize($_POST['track_api_base'] ?? '');
    $trackAuth   = sanitize($_POST['track_auth_type'] ?? 'none');
    $trackKey    = sanitize($_POST['track_api_key'] ?? '');
    $trackSecret = sanitize($_POST['track_api_secret'] ?? '');

    $update = $pdo->prepare('UPDATE settings SET value = ? WHERE `key` = ?');
    $update->execute([$scheduled, 'scheduled_interval_months']);
    $update->execute([$overhaul,  'overhaul_interval_months']);

    $update->execute([$trackBase,   'track_api_base']);
    $update->execute([$trackAuth,   'track_auth_type']);
    $update->execute([$trackKey,    'track_api_key']);
    $update->execute([$trackSecret, 'track_api_secret']);

    log_action($pdo, $_SESSION['user']['id'], 'Updated general & tracking settings');
    $messages[] = 'Settings updated.';
}

$scheduled = get_setting($pdo, 'scheduled_interval_months');
$overhaul  = get_setting($pdo, 'overhaul_interval_months');

$trackBase   = get_setting($pdo, 'track_api_base');
$trackAuth   = get_setting($pdo, 'track_auth_type') ?: 'none';
$trackKey    = get_setting($pdo, 'track_api_key');
$trackSecret = get_setting($pdo, 'track_api_secret');
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
  <hr class="my-4">
  <h4>Vehicle Tracking (3rd-party API)</h4>
  <div class="col-md-6">
    <label class="form-label">API Base URL</label>
    <input type="text" name="track_api_base" value="<?= $trackBase ?>" class="form-control" placeholder="https://api.tracker.com">
  </div>
  <div class="col-md-3">
    <label class="form-label">Auth Type</label>
    <select name="track_auth_type" class="form-select">
      <?php foreach(['none','api_key','bearer','basic'] as $t): ?>
        <option value="<?= $t ?>" <?= $trackAuth==$t?'selected':'' ?>><?= ucfirst($t) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">API Key / Token</label>
    <input type="text" name="track_api_key" value="<?= $trackKey ?>" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">API Secret (if Basic Auth)</label>
    <input type="password" name="track_api_secret" value="<?= $trackSecret ?>" class="form-control">
  </div>
  <div class="col-12">
    <button class="btn btn-brand">Save</button>
  </div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>