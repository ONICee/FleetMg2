<?php
/** @var string $content */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'Fleet Management' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">Fleet</a>
    <div class="d-flex">
      <?php if(isset($_SESSION['user'])): ?>
        <span class="navbar-text text-white me-3">Hello, <?= $_SESSION['user']['username'] ?></span>
        <a href="/logout" class="btn btn-sm btn-outline-light">Logout</a>
      <?php else: ?>
        <a href="/login" class="btn btn-sm btn-outline-light">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container">
    <?= $content ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>