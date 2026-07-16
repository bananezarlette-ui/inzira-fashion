<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/app.php';
requireAdmin(); $db = getDB();
$orders = $db->query("SELECT o.*, u.name AS uname FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Orders — Admin</title>
<link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base('admin/admin.css') ?>">
</head><body class="admin-body"><div class="admin-layout">
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
  <div class="admin-topbar"><h1>Orders (<?= count($orders) ?>)</h1></div>
  <div class="admin-card"><div style="overflow-x:auto">
    <table class="orders-table">
      <thead><tr><th>#</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['customer_name']) ?></td>
            <td><?= htmlspecialchars($o['customer_phone']) ?></td>
            <td><?= formatPrice($o['total']) ?></td>
            <td>
              <form method="POST" action="<?= base('admin/pages/update-order.php') ?>" style="display:inline">
                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                <input type="hidden" name="redirect" value="<?= base('admin/pages/orders.php') ?>">
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
  </div></div>
</main></div></body></html>
