<?php
$title='Edit User';
require_once __DIR__.'/../../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);
$id=intval($_GET['id']??0);
$userStmt=$pdo->prepare('SELECT * FROM users WHERE id=?');
$userStmt->execute([$id]);
$user=$userStmt->fetch();
if(!$user){exit('User not found');}
$roles=$pdo->query('SELECT id,name FROM roles')->fetchAll();
$errors=[];$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $role=intval($_POST['role_id']??0);
  $pwd=$_POST['password']??'';
  if(!$role){$errors[]='Role required';}
  if(!$errors){
    $pdo->prepare('UPDATE users SET role_id=? WHERE id=?')->execute([$role,$id]);
    if($pwd){$hash=password_hash($pwd,PASSWORD_BCRYPT);$pdo->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([$hash,$id]);}
    log_action($pdo,$_SESSION['user']['id'],"Updated user #$id");
    $success='User updated.';
    $userStmt->execute([$id]);$user=$userStmt->fetch();
  }
}
include __DIR__.'/../../includes/header.php';?>
<h2>Edit User – <?= sanitize($user['username']) ?></h2>
<?php foreach($errors as $e):?><div class="alert alert-danger"><?= $e ?></div><?php endforeach; ?>
<?php if($success):?><div class="alert alert-success"><?= $success ?></div><?php endif;?>
<form method="post" class="row g-3 w-50">
  <div class="col-12"><label class="form-label">Username</label><input type="text" class="form-control" value="<?= sanitize($user['username']) ?>" disabled></div>
  <div class="col-12"><label class="form-label">Role</label><select name="role_id" class="form-select"><?php foreach($roles as $r):?><option value="<?= $r['id'] ?>" <?= $user['role_id']==$r['id']?'selected':'' ?>><?= sanitize($r['name']) ?></option><?php endforeach;?></select></div>
  <div class="col-12"><label class="form-label">New Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
  <div class="col-12"><button class="btn btn-brand">Save</button><a href="index.php" class="btn btn-secondary ms-2">Back</a></div>
</form>
<?php include __DIR__.'/../../includes/footer.php'; ?>