// ── Inzira Fashion Cart (localStorage) ──
const CART_KEY = 'inzira_cart';

function getCart() {
  try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch { return []; }
}
function saveCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
  renderCart(); updateBadge();
}
function addToCart(id, name, price, image, category) {
  const cart = getCart();
  const idx  = cart.findIndex(i => i.id === id);
  if (idx > -1) cart[idx].qty += 1;
  else cart.push({ id, name, price, image, category, qty: 1 });
  saveCart(cart);
  showToast('Added to cart! 🛒', 'success');
  openCart();
}
function removeFromCart(id) { saveCart(getCart().filter(i => i.id !== id)); }
function updateQty(id, qty) {
  qty = parseInt(qty);
  if (qty < 1) return removeFromCart(id);
  saveCart(getCart().map(i => i.id === id ? { ...i, qty } : i));
}
function clearCart() { localStorage.removeItem(CART_KEY); renderCart(); updateBadge(); }
function cartTotal() { return getCart().reduce((s, i) => s + i.price * i.qty, 0); }
function cartCount() { return getCart().reduce((s, i) => s + i.qty, 0); }
function updateBadge() { const b = document.getElementById('cartBadge'); if (b) b.textContent = cartCount(); }
function formatPrice(p) { return 'RWF ' + Number(p).toLocaleString(); }

function renderCart() {
  const list   = document.getElementById('cartItemsList');
  const footer = document.getElementById('cartFooter');
  if (!list) return;
  const cart = getCart();
  const checkoutUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/pages/checkout.php';

  if (cart.length === 0) {
    const shopUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/pages/products.php';
    list.innerHTML = `<div class="cart-empty"><div class="icon">🛍️</div><p>Your cart is empty</p><a href="${shopUrl}" class="btn btn-primary btn-sm" style="margin-top:1rem" onclick="toggleCart()">Shop Now</a></div>`;
    footer.innerHTML = ''; return;
  }
  list.innerHTML = cart.map(item => `
    <div class="cart-item">
      <img src="${item.image || 'https://via.placeholder.com/70x80'}" alt="${item.name}" class="cart-item-img">
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">${formatPrice(item.price)}</div>
        <div class="qty-control">
          <button class="qty-btn" onclick="updateQty(${item.id}, ${item.qty - 1})">−</button>
          <span class="qty-num">${item.qty}</span>
          <button class="qty-btn" onclick="updateQty(${item.id}, ${item.qty + 1})">+</button>
        </div>
      </div>
      <button class="remove-btn" onclick="removeFromCart(${item.id})" title="Remove">🗑️</button>
    </div>`).join('');

  footer.innerHTML = `
    <div class="cart-total"><span>Total</span><span>${formatPrice(cartTotal())}</span></div>
    <a href="${checkoutUrl}" class="btn btn-primary btn-full" onclick="toggleCart()">Proceed to Checkout</a>`;
}

function openCart()   { document.getElementById('cartSidebar')?.classList.add('open'); document.getElementById('cartOverlay')?.classList.add('open'); }
function closeCart()  { document.getElementById('cartSidebar')?.classList.remove('open'); document.getElementById('cartOverlay')?.classList.remove('open'); }
function toggleCart() { document.getElementById('cartSidebar')?.classList.toggle('open'); document.getElementById('cartOverlay')?.classList.toggle('open'); }

document.addEventListener('DOMContentLoaded', () => { updateBadge(); renderCart(); });
