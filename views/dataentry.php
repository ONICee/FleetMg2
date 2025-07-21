<?php
$title = 'Data Entry Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_DATA_ENTRY);
include __DIR__ . '/../includes/header.php';
?>
<?php
$vehTotal = $pdo->query('SELECT COUNT(*) FROM vehicles')->fetchColumn();
?>

<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Total Vehicles</h5>
        <h2><?= $vehTotal ?></h2>
      </div>
    </div>
  </div>
</div>

<p class="lead">Use the sidebar to add vehicles and maintenance data.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>