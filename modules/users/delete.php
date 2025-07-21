<?php
require_once __DIR__ . '/../../config/security.php';
require_login();
require_role(ROLE_SUPER_ADMIN);
$id=intval($_GET['id']??0);
if($id==1){exit('Cannot delete primary superadmin');}
$pdo->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
log_action($pdo,$_SESSION['user']['id'],"Deleted user #$id");
header('Location: index.php');
exit;