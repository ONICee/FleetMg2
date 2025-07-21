<?php
$title = 'Add User';
require_once __DIR__ . '/../../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);

$roles = $pdo->query('SELECT id, name FROM roles')->fetchAll();
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username=sanitize($_POST['username']??'');
  $password=$_POST['password']??'';
  $role=intval($_POST['role_id']??0);
  if(!$username||!$password||!$role){$errors[]='All fields are required.';}
  else{
    $exists=$pdo->prepare('SELECT id FROM users WHERE username=?');
    $exists->execute([$username]);
    if($exists->fetch()){$errors[]='Username already exists.';}
  }
  if(!$errors){
    $hash=password_hash($password,PASSWORD_BCRYPT);
    $stmt=$pdo->prepare('INSERT INTO users(username,password_hash,role_id) VALUES (?,?,?)');
    $stmt->execute([$username,$hash,$role]);
    log_action($pdo,$_SESSION['user']['id'],"Created user $username");
    header('Location: index.php');exit;
  }
}
include __DIR__ . '/../../includes/header.php';
?>
<h2>Add User</h2>
<?php foreach($errors as $e):?><div class="alert alert-danger"><?= $e ?></div><?php endforeach;?>
<form method="post" class="row g-3 w-50">
  <div class="col-12"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
  <div class="col-12"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
  <div class="col-12"><label class="form-label">Role</label><select name="role_id" class="form-select" required><?php foreach($roles as $r):?><option value="<?= $r['id']?>"><?= sanitize($r['name'])?></option><?php endforeach;?></select></div>
  <div class="col-12"><button class="btn btn-brand">Create</button></div>
</form>
<?php include __DIR__ . '/../../includes/footer.php'; ?>