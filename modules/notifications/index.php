<?php
$title = 'Notifications';
require_once __DIR__ . '/../../config/security.php';
require_login();
$userId = $_SESSION['user']['id'];
// mark all as read
$pdo->prepare('UPDATE notifications SET is_read=1 WHERE user_id = ?')->execute([$userId]);
$stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC');
$stmt->execute([$userId]);
$notes = $stmt->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<h2>Notifications</h2>
<table class="table table-bordered table-sm">
  <thead class="table-light"><tr><th>Message</th><th>Date</th></tr></thead>
  <tbody>
  <?php foreach ($notes as $n): ?>
    <tr>
      <td><?= sanitize($n['message']) ?></td>
      <td><?= $n['created_at'] ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$notes): ?>
    <tr><td colspan="2" class="text-center">No notifications.</td></tr>
  <?php endif; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../includes/footer.php'; ?>