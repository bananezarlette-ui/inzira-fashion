<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
$db = getDB();
$id = intval($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT p.*, c.name AS cat_name, c.slug AS cat_slug FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.id=?");
$stmt->bind_param('i', $id); $stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { header('Location: ' . base('pages/products.php')); exit; }

$pageTitle = htmlspecialchars($p['name']) . ' — Inzira Fashion';
require_once __DIR__ . '/../includes/header.php';

$rel = $db->prepare("SELECT * FROM products WHERE category_id=? AND id!=? LIMIT 4");
$rel->bind_param('ii', $p['category_id'], $p['id']); $rel->execute();
$related = $rel->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="container">
  <div class="breadcrumb">
    <a href="<?= base('index.php') ?>">Home</a><span class="sep">›</span>
    <a href="<?= base('pages/products.php?category=') . $p['cat_slug'] ?>"><?= htmlspecialchars($p['cat_name'] ?? 'Products') ?></a>
    <span class="sep">›</span><span><?= htmlspecialchars($p['name']) ?></span>
  </div>
  <div class="product-detail">
    <div class="detail-img">
      <img src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/400x530') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
    </div>
    <div class="detail-info">
      <div class="detail-category"><?= htmlspecialchars($p['cat_name'] ?? '') ?></div>
      <h1 class="detail-name"><?= htmlspecialchars($p['name']) ?></h1>
      <div class="detail-price"><?= formatPrice($p['price']) ?></div>
      <div class="detail-stock <?= $p['stock']>0?'in':'out' ?>">
        <?= $p['stock']>0 ? '✅ In Stock ('.$p['stock'].' available)' : '❌ Out of Stock' ?>
      </div>
      <p class="detail-desc"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
      <?php if ($p['stock']>0): ?>
        <div class="qty-row">
          <label style="font-weight:600">Quantity:</label>
          <div class="qty-large">
            <button class="qty-btn" onclick="changeQty(-1)">−</button>
            <span class="qty-num" id="detailQty">1</span>
            <button class="qty-btn" onclick="changeQty(1)">+</button>
          </div>
        </div>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
          <button class="btn btn-primary" style="flex:1;min-width:180px"
            onclick="addToCartQty(<?= $p['id'] ?>, '<?= addslashes($p['name']) ?>', <?= $p['price'] ?>, '<?= addslashes($p['image_url']??'') ?>', '<?= addslashes($p['cat_name']??'') ?>')">
            🛒 Add to Cart
          </button>
          <a href="<?= base('pages/checkout.php') ?>" class="btn btn-outline" style="flex:1;min-width:180px;text-align:center">Buy Now</a>
        </div>
      <?php else: ?>
        <button class="btn btn-primary btn-full" disabled>Out of Stock</button>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($related)): ?>
    <div class="section" style="padding-top:2rem">
      <h2 class="section-title" style="text-align:left;font-size:1.4rem;margin-bottom:1.5rem">You May Also Like</h2>
      <div class="products-grid">
        <?php foreach ($related as $r): ?>
          <div class="product-card">
            <a href="<?= base('pages/product-detail.php?id=') . $r['id'] ?>" class="product-img">
              <img src="<?= htmlspecialchars($r['image_url']??'') ?>" alt="<?= htmlspecialchars($r['name']) ?>" loading="lazy">
            </a>
            <div class="product-info">
              <h3 class="product-name"><?= htmlspecialchars($r['name']) ?></h3>
              <div class="product-price"><?= formatPrice($r['price']) ?></div>
              <button class="btn btn-primary btn-full btn-sm"
                onclick="addToCart(<?= $r['id'] ?>, '<?= addslashes($r['name']) ?>', <?= $r['price'] ?>, '<?= addslashes($r['image_url']??'') ?>', '')">
                Add to Cart
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
<script>
let qty = 1; const max = <?= $p['stock'] ?>;
function changeQty(d) { qty = Math.min(max, Math.max(1, qty+d)); document.getElementById('detailQty').textContent = qty; }
function addToCartQty(id, name, price, image, cat) {
  const cart = getCart(); const idx = cart.findIndex(i => i.id===id);
  if (idx>-1) cart[idx].qty += qty; else cart.push({id,name,price,image,category:cat,qty});
  saveCart(cart); showToast(`${qty} item(s) added to cart! 🛒`,'success'); openCart();
}
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
