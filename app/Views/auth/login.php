<?php /** @var string|null $error */ ?>
<div class="card shadow" style="min-width:380px;">
  <div class="card-body p-4">
    <h3 class="text-center mb-4">Login</h3>
    <?php if(isset(
        $error) && $error): ?>
      <div class="alert alert-danger small"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" action="/login">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>