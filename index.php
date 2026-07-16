<?php
$pageTitle = 'Inzira Fashion | Rwanda\'s Online Fashion Store';
require_once 'includes/header.php';
require_once 'config/database.php';

$db = getDB();
$result = $db->query("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 8");
$featured = $result->fetch_all(MYSQLI_ASSOC);

$catResult = $db->query("SELECT * FROM categories");
$categories = $catResult->fetch_all(MYSQLI_ASSOC);
?>

<section class="hero">
  <div class="container">
    <div class="hero-content">
      <div class="hero-badge">🇷🇼 Made in Rwanda</div>
      <h1>Wear Your <span>Culture</span>,<br>Own Your Style</h1>
      <p>Discover the finest African prints, contemporary fashion, and handcrafted accessories — delivered anywhere in Rwanda.</p>
      <div class="hero-actions">
        <a href="<?= base('pages/products.php') ?>" class="btn btn-accent">Shop Now →</a>
        <a href="<?= base('pages/products.php?category=women') ?>" class="btn btn-outline" style="color:white;border-color:white">Women's Collection</a>
      </div>
    </div>
  </div>
</section>

<section class="categories-strip">
  <div class="container">
    <h2 class="section-title">Shop by Category</h2>
    <div class="categories-grid">
      <?php
      $icons = ['Women'=>'👗','Men'=>'👔','Kids'=>'🧒','Accessories'=>'👜','Shoes'=>'👠'];
      foreach ($categories as $cat): ?>
        <a href="<?= base('pages/products.php?category=') . urlencode($cat['slug']) ?>" class="cat-card">
          <div class="cat-icon"><?= $icons[$cat['name']] ?? '🛍️' ?></div>
          <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2 class="section-title">New Arrivals</h2>
    <p class="section-subtitle">Fresh styles, handpicked for you</p>
    <div class="products-grid">
      <?php foreach ($featured as $product): ?>
        <div class="product-card">
          <a href="<?= base('pages/product-detail.php?id=') . $product['id'] ?>" class="product-img">
            <img src="<?= htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300x400') ?>"
                 alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
            <?php if ($product['stock'] < 5 && $product['stock'] > 0): ?>
              <span class="product-badge">Low Stock</span>
            <?php endif; ?>
          </a>
          <div class="product-info">
            <div class="product-category"><?= htmlspecialchars($product['cat_name'] ?? '') ?></div>
            <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
            <div class="product-price"><?= formatPrice($product['price']) ?></div>
            <div class="product-actions">
              <button class="btn btn-primary" style="flex:1"
                onclick="addToCart(<?= $product['id'] ?>, '<?= addslashes($product['name']) ?>', <?= $product['price'] ?>, '<?= addslashes($product['image_url'] ?? '') ?>', '<?= addslashes($product['cat_name'] ?? '') ?>')">
                Add to Cart
              </button>
              <a href="<?= base('pages/product-detail.php?id=') . $product['id'] ?>" class="btn btn-outline">View</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a href="<?= base('pages/products.php') ?>" class="btn btn-outline">View All Products →</a>
    </div>
  </div>
</section>

<section class="section" style="background:var(--white)">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:2rem;text-align:center">
      <?php foreach([['🚚','Fast Delivery','Delivered across Rwanda in 1–3 days'],['✅','Quality Guaranteed','Every item passes our quality check'],['🔄','Easy Returns','30-day hassle-free returns'],['🇷🇼','Local Brand','Proudly Rwandan, globally inspired']] as [$icon,$title,$desc]): ?>
        <div style="padding:1.5rem">
          <div style="font-size:2.5rem;margin-bottom:.75rem"><?= $icon ?></div>
          <h4 style="font-weight:700;margin-bottom:.5rem"><?= $title ?></h4>
          <p style="color:var(--text-muted);font-size:.9rem"><?= $desc ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
