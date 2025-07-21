<?php
$title = 'Maintenance Records';
require_once __DIR__ . '/../../config/security.php';
require_login();
if ($_SESSION['user']['role_id'] > ROLE_ADMIN) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
$q = $pdo->query('SELECT m.*, v.brand, v.serial_number FROM maintenance m JOIN vehicles v ON m.vehicle_id = v.id ORDER BY m.maintenance_date DESC');
$rows = $q->fetchAll();
// type filter param
$typeParam = sanitize($_GET['type'] ?? '');
$typeFilterSQL = '';
if($typeParam){
  $q=$pdo->prepare('SELECT m.*, v.brand, v.serial_number FROM maintenance m JOIN vehicles v ON m.vehicle_id = v.id WHERE m.type = ? ORDER BY m.maintenance_date DESC');
  $q->execute([$typeParam]);
  $rows=$q->fetchAll();
}
$breadcrumbs=['Dashboard'=>BASE_URL,'Maintenance'=>null];
include __DIR__ . '/../../includes/header.php';
?>
<?php
$cntScheduled = $pdo->query("SELECT COUNT(*) FROM maintenance WHERE type='Scheduled'")->fetchColumn();
$cntUnscheduled = $pdo->query("SELECT COUNT(*) FROM maintenance WHERE type='Unscheduled'")->fetchColumn();
$cntOverhaul = $pdo->query("SELECT COUNT(*) FROM maintenance WHERE type='Overhaul'")->fetchColumn();
$cntOverdue = $pdo->query("SELECT COUNT(*) FROM maintenance WHERE next_date IS NOT NULL AND next_date < CURDATE()")->fetchColumn();
?>

<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="card card-stat text-center" data-color="blue"><div class="card-body"><h5 class="card-title">Scheduled</h5><h2><?= $cntScheduled ?></h2></div></div></div>
  <div class="col-md-3"><div class="card card-stat text-center" data-color="yellow"><div class="card-body"><h5 class="card-title">Unscheduled</h5><h2><?= $cntUnscheduled ?></h2></div></div></div>
  <div class="col-md-3"><div class="card card-stat text-center" data-color="cyan"><div class="card-body"><h5 class="card-title">Overhaul</h5><h2><?= $cntOverhaul ?></h2></div></div></div>
  <div class="col-md-3"><div class="card card-stat text-center" data-color="red"><div class="card-body"><h5 class="card-title">Overdue</h5><h2><?= $cntOverdue ?></h2></div></div></div>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Maintenance Records</h2>
  <a href="export_csv.php?type=<?= urlencode($_GET['type']??'') ?>" class="btn btn-outline-secondary"><i class="fa fa-download me-1"></i> CSV</a>
</div>
<div class="mb-3">
  <a href="index.php" class="btn btn-sm <?= !$typeParam?'btn-primary':'btn-outline-primary' ?> me-1">All</a>
  <?php foreach(['Scheduled','Unscheduled','Overhaul'] as $t): ?>
    <a href="index.php?type=<?= $t ?>" class="btn btn-sm <?= $typeParam==$t?'btn-primary':'btn-outline-primary' ?> me-1"><?= $t ?></a>
  <?php endforeach; ?>
</div>
<table class="table table-bordered table-sm datatable">
  <thead class="table-dark">
    <tr>
      <th>Date</th><th>Vehicle</th><th>Type</th><th>Description</th><th>Next Due</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <?php
        $rowClass = '';
        if($r['type']=='Scheduled') $rowClass='table-primary';
        elseif($r['type']=='Unscheduled') $rowClass='table-warning';
        elseif($r['type']=='Overhaul') $rowClass='table-info';
        if($r['next_date'] && $r['next_date']<date('Y-m-d')) $rowClass='table-danger';
      ?>
      <tr class="<?= $rowClass ?>">
        <td><?= $r['maintenance_date'] ?></td>
        <td><a href="../vehicles/view.php?id=<?= $r['vehicle_id'] ?>"><?= sanitize($r['brand']) ?> (<?= sanitize($r['serial_number']) ?>)</a></td>
        <td><span class="badge bg-secondary"><?= $r['type'] ?></span></td>
        <td><?= nl2br(sanitize($r['description'])) ?></td>
        <td><?= $r['next_date'] ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
      <tr><td colspan="5" class="text-center">No records.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>