<?php
$title = 'Users';
require_once __DIR__ . '/../../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);

$breadcrumbs=['Dashboard'=>BASE_URL,'Users'=>null];

$users = $pdo->query('SELECT u.id, u.username, r.name AS role_name, u.created_at FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC')->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>User Management</h2>
  <a href="create.php" class="btn btn-brand">Add User</a>
</div>
<table class="table table-bordered table-sm datatable">
  <thead class="table-dark"><tr><th>ID</th><th>Username</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= sanitize($u['username']) ?></td>
      <td><?= sanitize($u['role_name']) ?></td>
      <td><?= $u['created_at'] ?></td>
      <td>
        <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-dark me-1"><i class="fa fa-edit"></i></a>
        <?php if($u['id']!=1): ?>
        <a href="delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');"><i class="fa fa-trash"></i></a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>