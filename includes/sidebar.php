<?php
$role = $_SESSION['user']['role_id'];
?>
<aside class="sidebar bg-black text-white position-fixed">
  <ul class="nav nav-pills flex-column p-3 pt-4">
    <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>">Dashboard</a></li>
    <?php if ($role <= ROLE_ADMIN): ?>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/vehicles/index.php">Vehicles</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/maintenance/index.php">Maintenance</a></li>
    <?php endif; ?>
    <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/notifications/index.php">Notifications</a></li>
    <?php if ($role == ROLE_SUPER_ADMIN): ?>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/users/index.php">Users</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>settings/general.php">Settings</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="<?= BASE_URL ?>modules/logs/index.php">Audit Logs</a></li>
    <?php endif; ?>
  </ul>
</aside>