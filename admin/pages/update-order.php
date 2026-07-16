<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/app.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=intval($_POST['order_id']); $status=$_POST['status'];
    if (in_array($status,['pending','confirmed','shipped','delivered','cancelled'])) {
        $db=getDB(); $stmt=$db->prepare("UPDATE orders SET status=? WHERE id=?"); $stmt->bind_param('si',$status,$id); $stmt->execute();
    }
}
header('Location: ' . ($_POST['redirect'] ?? base('admin/index.php'))); exit;
