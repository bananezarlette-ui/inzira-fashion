<?php
$pageTitle = 'Order Confirmed — Inzira Fashion';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
$db = getDB();
$id = intval($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT o.*, GROUP_CONCAT(p.name SEPARATOR ', ') AS product_names FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id LEFT JOIN products p ON oi.product_id=p.id WHERE o.id=? GROUP BY o.id");
$stmt->bind_param('i', $id); $stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) { header('Location: ' . base('index.php')); exit; }
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
  <div class="confirm-box">
    <div class="confirm-icon">🎉</div>
    <h2>Order Confirmed!</h2>
    <p style="color:var(--text-muted);margin-bottom:2rem">Thank you, <strong><?= htmlspecialchars($order['customer_name']) ?></strong>! Your order has been received.</p>
    <div class="checkout-card" style="max-width:500px;margin:0 auto 2rem;text-align:left">
      <h3 class="checkout-title">Order #<?= $order['id'] ?> Details</h3>
      <div class="order-summary-item"><span>Status</span><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></div>
      <div class="order-summary-item"><span>Items</span><span><?= htmlspecialchars($order['product_names']) ?></span></div>
      <div class="order-summary-item"><span>Delivery to</span><span><?= htmlspecialchars($order['customer_address']) ?></span></div>
      <div class="order-summary-item"><span>Contact</span><span><?= htmlspecialchars($order['customer_phone']) ?></span></div>
      <div class="order-total-row" style="padding-top:.5rem"><span>Total</span><span style="color:var(--brand)"><?= formatPrice($order['total']) ?></span></div>
    </div>
    <p style="color:var(--text-muted);font-size:.9rem;margin-bottom:2rem">Confirmation sent to <strong><?= htmlspecialchars($order['customer_email']) ?></strong></p>
    <a href="<?= base('pages/products.php') ?>" class="btn btn-primary">Continue Shopping</a>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
