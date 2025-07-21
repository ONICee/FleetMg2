<?php
$title = 'Admin Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_ADMIN);
include __DIR__ . '/../includes/header.php';
?>
<?php
$vehTotal   = $pdo->query('SELECT COUNT(*) FROM vehicles')->fetchColumn();
$maintDue   = $pdo->query("SELECT COUNT(*) FROM maintenance WHERE next_date IS NOT NULL AND next_date <= CURDATE() + INTERVAL 30 DAY")->fetchColumn();
?>

<div class="row g-4 mb-4">
  <div class="col-md-6">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Total Vehicles</h5>
        <h2><?= $vehTotal ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Maintenance Due &lt;30d</h5>
        <h2><?= $maintDue ?></h2>
      </div>
    </div>
  </div>
</div>

<p class="lead">Use the sidebar to manage vehicles and maintenance records.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>