<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/app.php';
requireAdmin(); $db = getDB();
$id=intval($_GET['id']??0);
$stmt=$db->prepare("SELECT * FROM orders WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute();
$order=$stmt->get_result()->fetch_assoc();
if (!$order) { header('Location: ' . base('admin/pages/orders.php')); exit; }
$items=$db->prepare("SELECT oi.*, p.name AS pname, p.image_url FROM order_items oi LEFT JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
$items->bind_param('i',$id); $items->execute();
$orderItems=$items->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Order #<?= $id ?> — Admin</title>
<link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base('admin/admin.css') ?>">
</head><body class="admin-body"><div class="admin-layout">
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
  <div class="admin-topbar"><h1>Order #<?= $id ?></h1><a href="<?= base('admin/pages/orders.php') ?>" class="btn btn-outline btn-sm">← Back</a></div>
  <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;padding:1.5rem 2rem;align-items:start">
    <div class="admin-card">
      <h2 class="admin-card-title">Items</h2>
      <?php foreach ($orderItems as $item): ?>
        <div class="cart-item" style="padding:.75rem 0">
          <img src="<?= htmlspecialchars($item['image_url']??'') ?>" style="width:56px;height:64px;object-fit:cover;border-radius:6px">
          <div class="cart-item-info">
            <div class="cart-item-name"><?= htmlspecialchars($item['pname']) ?></div>
            <div style="font-size:.85rem;color:var(--text-muted)">Qty: <?= $item['quantity'] ?> × <?= formatPrice($item['price']) ?></div>
            <div class="cart-item-price"><?= formatPrice($item['price']*$item['quantity']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
      <div style="border-top:2px solid var(--border);padding-top:.75rem;display:flex;justify-content:space-between;font-weight:700;font-size:1.1rem">
        <span>Total</span><span style="color:var(--brand)"><?= formatPrice($order['total']) ?></span>
      </div>
    </div>
    <div class="admin-card">
      <h2 class="admin-card-title">Customer</h2>
      <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
      <p style="margin:.5rem 0"><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
      <p style="margin:.5rem 0"><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
      <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
      <p style="margin-top:.75rem"><strong>Status:</strong> <span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></p>
    </div>
  </div>
</main></div></body></html>
