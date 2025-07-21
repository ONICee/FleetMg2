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
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.png">
</head>
<body class="login-page">
  <div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="card shadow" style="max-width:420px;border-top:5px solid var(--brand-yellow);">
      <div class="card-body p-4">
        <div class="text-center mb-3">
          <img src="<?= BASE_URL ?>assets/images/anambra_logo.png" alt="Anambra State" style="height:60px;">
          <h4 class="fw-bold mt-3 mb-1">ANSG Security Fleet</h4>
          <span class="text-muted small">Operational Readiness Portal</span>
        </div>
        <p class="small text-muted mb-4 text-center">Login to manage vehicle records, schedule maintenance and monitor fleet performance.</p>
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
</body>
</html>