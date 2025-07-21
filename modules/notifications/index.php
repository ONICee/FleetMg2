<?php
 $title = 'Notifications';
 require_once __DIR__ . '/../../config/security.php';
 require_login();
 $userId = $_SESSION['user']['id'];
 // mark as read
 $pdo->prepare('UPDATE notifications SET is_read=1 WHERE user_id = ?')->execute([$userId]);
 $all = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC');
 $all->execute([$userId]);
 $notes=$all->fetchAll();
 $byType=[];foreach($notes as $n){$byType[$n['type']][]=$n;}
 include __DIR__ . '/../../includes/header.php';
?>
<h2>Notifications</h2>
<ul class="nav nav-tabs mb-3" role="tablist">
 <?php $i=0; foreach($byType as $type=>$arr): ?>
   <li class="nav-item"><button class="nav-link <?= $i==0?'active':'' ?>" data-bs-toggle="tab" data-bs-target="#tab<?= $i ?>" type="button"><?= ucfirst($type) ?> <span class="badge bg-secondary ms-1"><?= count($arr) ?></span></button></li>
 <?php $i++; endforeach; ?>
</ul>
<div class="tab-content">
<?php $i=0; foreach($byType as $type=>$arr): ?>
  <div class="tab-pane fade <?= $i==0?'show active':'' ?>" id="tab<?= $i ?>">
    <table class="table table-bordered table-sm">
      <thead class="table-light"><tr><th>Message</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach($arr as $n): ?>
        <tr><td><?= sanitize($n['message']) ?></td><td><?= $n['created_at'] ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php $i++; endforeach; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>