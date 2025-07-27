<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="text-center">
      <h1 class="display-5 fw-bold mb-3" style="color: <?= htmlspecialchars($_ENV['BRAND_COLOR'] ?? '#ffcd29') ?>;">Fleet Management</h1>
      <p class="lead">Micro-MVC refactor is up and running! 🎉</p>
      <a href="/" class="btn btn-dark">Back to Home</a>
    </div>
  </div>
</body>
</html>