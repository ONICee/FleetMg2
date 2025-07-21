<?php
require_once __DIR__ . '/../config/security.php';

if (is_logged_in()) {
    header('Location: ' . BASE_URL);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare('SELECT u.id, u.username, u.password_hash, u.role_id, r.name AS role_name
                               FROM users u JOIN roles r ON u.role_id = r.id
                               WHERE u.username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'role_id'   => $user['role_id'],
                'role_name' => $user['role_name'],
            ];
            log_action($pdo, $user['id'], 'Logged in');
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $error = 'Invalid credentials.';
        }
    } else {
        $error = 'Please fill in both fields.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login – ANSG Security Fleet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="login-page">
  <div class="container-fluid h-100">
    <div class="row h-100">
      <!-- Intro / branding side -->
      <div class="col-lg-7 d-none d-lg-flex flex-column justify-content-center text-white p-5" style="background:linear-gradient(135deg,#ffda52 0%, #ffcd29 100%);">
        <div class="mb-4">
          <img src="<?= BASE_URL ?>assets/images/anambra_logo.png" alt="Anambra State" style="height:80px;">
          <h1 class="mt-3 fw-bold">ANSG Security Fleet Management</h1>
          <p class="lead">Safeguarding the operational readiness of our security vehicles through smart maintenance, real-time tracking and data-driven insights.</p>
        </div>
        <ul class="list-unstyled fs-5 lh-lg">
          <li><i class="fa fa-check-circle me-2"></i>Track serviceability & maintenance history</li>
          <li><i class="fa fa-check-circle me-2"></i>Receive automated reminders before due dates</li>
          <li><i class="fa fa-check-circle me-2"></i>Monitor vehicle location (GPS-enabled)</li>
          <li><i class="fa fa-check-circle me-2"></i>Role-based dashboards & in-app notifications</li>
        </ul>
      </div>

      <!-- Login form side -->
      <div class="col-lg-5 d-flex align-items-center justify-content-center p-4">
        <div class="card shadow w-100" style="max-width:420px;">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center">Sign in</h3>
            <?php if ($error): ?>
              <div class="alert alert-danger small"><i class="fa fa-triangle-exclamation me-2"></i><?= $error ?></div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
              </div>
              <button class="btn btn-brand w-100">Login</button>
            </form>
          </div>
          <div class="card-footer text-center small text-muted">© <?= date('Y') ?> Anambra State Government</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>