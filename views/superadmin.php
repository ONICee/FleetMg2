<?php
$title = 'Super Admin Dashboard';
require_once __DIR__ . '/../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);
include __DIR__ . '/../includes/header.php';
?>
<?php
$vehTotal   = $pdo->query('SELECT COUNT(*) FROM vehicles')->fetchColumn();
$vehActive  = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE serviceability='In Use'")->fetchColumn();
$vehOffRoad = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE serviceability='Off-Road'")->fetchColumn();
$maintDue   = $pdo->query("SELECT COUNT(*) FROM maintenance WHERE next_date IS NOT NULL AND next_date <= CURDATE() + INTERVAL 30 DAY")->fetchColumn();
?>

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <a href="<?= BASE_URL ?>modules/vehicles/index.php" class="text-decoration-none text-dark">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Total Vehicles</h5>
        <h2><?= $vehTotal ?></h2>
      </div>
    </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= BASE_URL ?>modules/vehicles/index.php?serviceability=In+Use" class="text-decoration-none text-dark">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Active (In Use)</h5>
        <h2><?= $vehActive ?></h2>
      </div>
    </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= BASE_URL ?>modules/vehicles/index.php?serviceability=Off-Road" class="text-decoration-none text-dark">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Off-Road</h5>
        <h2><?= $vehOffRoad ?></h2>
      </div>
    </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= BASE_URL ?>modules/maintenance/index.php" class="text-decoration-none text-dark">
    <div class="card card-stat text-center">
      <div class="card-body">
        <h5 class="card-title">Maintenance Due &lt;30d</h5>
        <h2><?= $maintDue ?></h2>
      </div>
    </div>
    </a>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header bg-light">Fleet Serviceability Chart</div>
  <div class="card-body d-flex justify-content-center">
    <canvas id="statusChart" width="220" height="220" style="max-width:220px;"></canvas>
  </div>
</div>

<script defer>
document.addEventListener('DOMContentLoaded',()=>{
  const ctx=document.getElementById('statusChart');
  if(ctx && window.Chart){
    new Chart(ctx,{type:'doughnut',data:{labels:['Serviceable (In Use)','Unserviceable (Off-Road)'],datasets:[{data:[<?= $vehActive ?>,<?= $vehOffRoad ?>],backgroundColor:['#28a745','#dc3545']}]} ,options:{plugins:{legend:{position:'bottom'}}}});
  }
});
</script>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-light">Recent Vehicles Added</div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
        <?php
          $recentV = $pdo->query('SELECT id, brand, serial_number, created_at FROM vehicles ORDER BY created_at DESC LIMIT 5')->fetchAll();
          foreach ($recentV as $v): ?>
            <tr>
              <td><?= sanitize($v['brand']) ?> (<?= sanitize($v['serial_number']) ?>)</td>
              <td class="text-end"><small class="text-muted"><?= date('d M', strtotime($v['created_at'])) ?></small></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$recentV): ?><tr><td class="text-center py-3">No vehicles yet.</td></tr><?php endif; ?>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-light">Upcoming Maintenance (&lt;30d)</div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
        <?php
          $upcoming = $pdo->query("SELECT v.brand, v.serial_number, m.next_date FROM maintenance m JOIN vehicles v ON m.vehicle_id=v.id WHERE m.next_date IS NOT NULL AND m.next_date <= CURDATE() + INTERVAL 30 DAY ORDER BY m.next_date LIMIT 5")->fetchAll();
          foreach ($upcoming as $u): ?>
            <tr>
              <td><?= sanitize($u['brand']) ?> (<?= sanitize($u['serial_number']) ?>)</td>
              <td class="text-end"><span class="badge bg-warning text-dark"><?= $u['next_date'] ?></span></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$upcoming): ?><tr><td class="text-center py-3">No upcoming maintenance.</td></tr><?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>