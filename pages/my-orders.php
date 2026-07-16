<?php
$pageTitle = 'My Orders — Inzira Fashion';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
requireLogin();
$db = getDB(); $uid = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT o.*, COUNT(oi.id) AS item_count FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id WHERE o.user_id=? GROUP BY o.id ORDER BY o.created_at DESC");
$stmt->bind_param('i', $uid); $stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container" style="padding:2rem 1.5rem">
  <h1 style="font-size:1.75rem;font-weight:800;margin-bottom:1.5rem">📦 My Orders</h1>
  <?php if (empty($orders)): ?>
    <div style="text-align:center;padding:4rem;color:var(--text-muted)">
      <div style="font-size:3rem;margin-bottom:1rem">📭</div>
      <p>No orders yet.</p>
      <a href="<?= base('pages/products.php') ?>" class="btn btn-primary" style="margin-top:1rem">Start Shopping</a>
    </div>
  <?php else: ?>
    <div style="overflow-x:auto">
      <table class="orders-table">
        <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td>#<?= $o['id'] ?></td>
              <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
              <td><?= $o['item_count'] ?> item(s)</td>
              <td><?= formatPrice($o['total']) ?></td>
              <td><span class="status-badge status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
