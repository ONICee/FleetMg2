<?php
require_once __DIR__ . '/../../config/security.php';
require_login();
if($_SESSION['user']['role_id']>ROLE_ADMIN){header('HTTP/1.1 403');exit;}
$type=sanitize($_GET['type']??'');
$sql='SELECT m.maintenance_date as Date, v.brand as VehicleBrand, v.serial_number as SerialNumber, m.type as Type, m.description as Description, m.next_date as NextDue FROM maintenance m JOIN vehicles v ON m.vehicle_id=v.id';
$params=[];
if($type){$sql.=' WHERE m.type=?';$params[]=$type;}
$sql.=' ORDER BY m.maintenance_date DESC';
$stmt=$pdo->prepare($sql);$stmt->execute($params);
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=maintenance_'.date('Ymd_His').'.csv');
$out=fopen('php://output','w');
if($rows){fputcsv($out,array_keys($rows[0]));foreach($rows as $r){fputcsv($out,$r);} }
fclose($out);exit;