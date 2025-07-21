<?php
$role = $_SESSION['user']['role_id'];
?>
<aside class="sidebar bg-black text-white position-fixed">
  <ul class="nav nav-pills flex-column p-3 pt-4">
    <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>"><i class="fa fa-home me-1"></i> Dashboard</a></li>
    <?php if ($role <= ROLE_ADMIN): ?>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/vehicles/index.php"><i class="fa fa-car me-1"></i> Vehicles</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/maintenance/index.php"><i class="fa fa-wrench me-1"></i> Maintenance</a></li>
    <?php endif; ?>
    <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/notifications/index.php"><i class="fa fa-bell me-1"></i> Notifications</a></li>
    <?php if ($role == ROLE_SUPER_ADMIN): ?>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/users/index.php"><i class="fa fa-users me-1"></i> Users</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>settings/general.php"><i class="fa fa-gear me-1"></i> Settings</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/logs/index.php"><i class="fa fa-clipboard me-1"></i> Audit Logs</a></li>
    <?php endif; ?>
  </ul>
</aside>