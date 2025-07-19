<?php
require_once __DIR__ . '/../config/security.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= isset($title) ? $title . ' – ' : '' ?>Fleet Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= BASE_URL ?>">Fleet</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <!-- Notifications -->
          <li class="nav-item dropdown me-3">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-bell"></i>
              <?php
              $stmt = $pdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0');
              $stmt->execute([$_SESSION['user']['id']]);
              $unread = $stmt->fetchColumn();
              if ($unread): ?>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"><?= $unread ?></span>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" style="width:300px;">
              <?php
              $stmt = $pdo->prepare('SELECT id, message, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10');
              $stmt->execute([$_SESSION['user']['id']]);
              foreach ($stmt->fetchAll() as $note): ?>
                <li><span class="dropdown-item small"><?= sanitize($note['message']) ?><br><small class="text-muted"><?= $note['created_at'] ?></small></span></li>
              <?php endforeach; ?>
              <?php if (!$stmt->rowCount()): ?>
                <li><span class="dropdown-item text-muted">No notifications</span></li>
              <?php endif; ?>
            </ul>
          </li>
          <!-- User -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <?= $_SESSION['user']['username'] ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><span class="dropdown-item-text small text-muted">Role: <?= $_SESSION['user']['role_name'] ?></span></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="d-flex">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="flex-grow-1 p-4" style="margin-top:56px;">