<?php
$pageTitle = 'Checkout — Inzira Fashion';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
  <div class="breadcrumb">
    <a href="<?= base('index.php') ?>">Home</a><span class="sep">›</span>
    <a href="<?= base('pages/products.php') ?>">Shop</a><span class="sep">›</span><span>Checkout</span>
  </div>
  <div class="checkout-layout" id="checkoutLayout">
    <div class="checkout-card">
      <h2 class="checkout-title">📋 Customer Information</h2>
      <form id="checkoutForm" novalidate>
        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="customer_name" class="form-control" placeholder="Jean Paul Habimana">
            <span class="error-msg" data-err="customer_name"></span>
          </div>
          <div class="form-group">
            <label>Phone Number *</label>
            <input type="tel" name="customer_phone" class="form-control" placeholder="+250 788 000 000">
            <span class="error-msg" data-err="customer_phone"></span>
          </div>
        </div>
        <div class="form-group">
          <label>Email Address *</label>
          <input type="email" name="customer_email" class="form-control" placeholder="jean@example.com">
          <span class="error-msg" data-err="customer_email"></span>
        </div>
        <div class="form-group">
          <label>Delivery Address *</label>
          <textarea name="customer_address" class="form-control" rows="3" placeholder="Sector, District, Province"></textarea>
          <span class="error-msg" data-err="customer_address"></span>
        </div>
        <div class="form-group">
          <label>Payment Method</label>
          <select name="payment_method" class="form-control">
            <option value="momo">MTN Mobile Money</option>
            <option value="airtel">Airtel Money</option>
            <option value="cash">Cash on Delivery</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-full" id="placeOrderBtn">Place Order 🎉</button>
      </form>
    </div>
    <div>
      <div class="checkout-card">
        <h2 class="checkout-title">🛍️ Order Summary</h2>
        <div id="summaryItems"></div>
        <div style="border-top:2px solid var(--border);margin-top:.5rem;padding-top:.75rem">
          <div class="order-total-row"><span>Subtotal</span><span id="summarySubtotal">RWF 0</span></div>
          <div class="order-total-row" style="color:var(--text-muted);font-size:.9rem;font-weight:400;margin:.25rem 0"><span>Delivery</span><span>Free</span></div>
          <div class="order-total-row" style="font-size:1.2rem;color:var(--brand);margin-top:.5rem"><span>Total</span><span id="summaryTotal">RWF 0</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const CHECKOUT_URL = <?= json_encode(base('pages/order-confirmation.php')) ?>;
const ORDERS_API   = <?= json_encode(base('api/orders.php')) ?>;
document.addEventListener('DOMContentLoaded', () => {
  const cart = getCart();
  if (cart.length === 0) { window.location = <?= json_encode(base('pages/products.php')) ?>; return; }
  document.getElementById('summaryItems').innerHTML = cart.map(i =>
    `<div class="order-summary-item"><span>${i.name} × ${i.qty}</span><span>${formatPrice(i.price * i.qty)}</span></div>`).join('');
  const total = cartTotal();
  document.getElementById('summarySubtotal').textContent = formatPrice(total);
  document.getElementById('summaryTotal').textContent    = formatPrice(total);

  document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const valid = validateForm('checkoutForm', {
      customer_name:    { required: true, message: 'Full name is required' },
      customer_phone:   { required: true, phone: true },
      customer_email:   { required: true, email: true },
      customer_address: { required: true, message: 'Delivery address is required' },
    });
    if (!valid) return;
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true; btn.textContent = 'Placing order…';
    const form = new FormData(e.target);
    const body = {
      customer_name: form.get('customer_name'), customer_email: form.get('customer_email'),
      customer_phone: form.get('customer_phone'), customer_address: form.get('customer_address'),
      items: cart.map(i => ({ product_id: i.id, quantity: i.qty }))
    };
    try {
      const res  = await fetch(ORDERS_API, { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(body) });
      const data = await res.json();
      if (res.ok && data.order_id) { clearCart(); window.location = CHECKOUT_URL + '?id=' + data.order_id; }
      else { showToast(data.error || 'Order failed. Please try again.', 'error'); btn.disabled=false; btn.textContent='Place Order 🎉'; }
    } catch { showToast('Network error. Please try again.', 'error'); btn.disabled=false; btn.textContent='Place Order 🎉'; }
  });
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
