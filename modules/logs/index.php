<?php
$title = 'Audit Logs';
require_once __DIR__ . '/../../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);
$q = $pdo->query('SELECT l.*, u.username FROM audit_logs l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 500');
$logs = $q->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<h2>Audit Logs</h2>
<table class="table table-bordered table-sm datatable">
  <thead class="table-dark"><tr><th>Date</th><th>User</th><th>Action</th><th>IP</th><th>URL</th><th>User Agent</th></tr></thead>
  <tbody>
  <?php foreach ($logs as $log): ?>
    <tr>
      <td><?= $log['created_at'] ?></td>
      <td><?= sanitize($log['username'] ?? 'System') ?></td>
      <td><?= sanitize($log['action']) ?></td>
      <td><?= $log['ip_address'] ?></td>
      <td><small><?= sanitize($log['request_url']) ?></small></td>
      <td><small><?= sanitize($log['user_agent']) ?></small></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$logs): ?>
    <tr><td colspan="6" class="text-center">No logs.</td></tr>
  <?php endif; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>