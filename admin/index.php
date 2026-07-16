<?php
$pageTitle = 'Admin Dashboard — Inzira Fashion';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
requireAdmin();
$db = getDB();
$stats = [
  'products' => $db->query("SELECT COUNT(*) FROM products")->fetch_row()[0],
  'orders'   => $db->query("SELECT COUNT(*) FROM orders")->fetch_row()[0],
  'revenue'  => $db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='cancelled'")->fetch_row()[0],
  'users'    => $db->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetch_row()[0],
];
$recentOrders = $db->query("SELECT o.*, u.name AS uname FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 8")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base('admin/admin.css') ?>">
</head>
<body class="admin-body">
<div class="admin-layout">
  <?php include __DIR__ . '/includes/sidebar.php'; ?>
  <main class="admin-main">
    <div class="admin-topbar"><h1>Dashboard</h1><span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span></div>
    <div class="stats-grid">
      <?php foreach([['📦','Products',$stats['products']],['🛒','Orders',$stats['orders']],['💰','Revenue',formatPrice($stats['revenue'])],['👥','Customers',$stats['users']]] as [$icon,$label,$val]): ?>
        <div class="stat-card"><div class="stat-icon"><?= $icon ?></div><div><div class="stat-value"><?= $val ?></div><div class="stat-label"><?= $label ?></div></div></div>
      <?php endforeach; ?>
    </div>
    <div class="admin-card">
      <h2 class="admin-card-title">Recent Orders</h2>
      <div style="overflow-x:auto">
        <table class="orders-table">
          <thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($recentOrders as $o): ?>
              <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['customer_name']) ?></td>
                <td><?= formatPrice($o['total']) ?></td>
                <td>
                  <form method="POST" action="<?= base('admin/pages/update-order.php') ?>" style="display:inline">
                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                    <input type="hidden" name="redirect" value="<?= base('admin/index.php') ?>">
                    <select name="status" class="filter-select" style="padding:.3rem .5rem;font-size:.8rem" onchange="this.form.submit()">
                      <?php foreach(['pending','confirmed','shipped','delivered','cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= $o['status']===$s?'selected':''?>><?= ucfirst($s) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </form>
                </td>
                <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                <td><a href="<?= base('admin/pages/order-detail.php?id=') . $o['id'] ?>" class="btn btn-outline btn-sm">View</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
</body></html>
