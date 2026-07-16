<?php
$pageTitle = 'Shop — Inzira Fashion';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$page     = max(1, intval($_GET['page'] ?? 1));
$limit    = 12;
$offset   = ($page - 1) * $limit;
$search   = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$sort     = sanitize($_GET['sort'] ?? 'newest');

$where = ['1=1']; $params = []; $types = '';
if ($search)   { $where[] = 'p.name LIKE ?'; $params[] = "%$search%"; $types .= 's'; }
if ($category) { $where[] = 'c.slug = ?';    $params[] = $category;  $types .= 's'; }
$whereSQL = implode(' AND ', $where);
$orderSQL = match($sort) { 'price_asc'=>'p.price ASC','price_desc'=>'p.price DESC','name'=>'p.name ASC',default=>'p.created_at DESC' };

$countStmt = $db->prepare("SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE $whereSQL");
if ($params) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = $countStmt->get_result()->fetch_row()[0];
$pages = ceil($total / $limit);

$sql = "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE $whereSQL ORDER BY $orderSQL LIMIT ? OFFSET ?";
$stmt = $db->prepare($sql);
$p2 = $params; $p2[] = $limit; $p2[] = $offset; $t2 = $types . 'ii';
$stmt->bind_param($t2, ...$p2);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$categories = $db->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
  <div class="breadcrumb">
    <a href="<?= base('index.php') ?>">Home</a><span class="sep">›</span>
    <span><?= $category ? ucfirst($category) : 'All Products' ?></span>
  </div>

  <form class="filter-bar" method="GET" action="<?= base('pages/products.php') ?>">
    <div class="search-box">
      <input type="text" name="search" class="form-control" placeholder="Search products…" value="<?= htmlspecialchars($search) ?>">
    </div>
    <select name="category" class="filter-select" onchange="this.form.submit()">
      <option value="">All Categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['slug'] ?>" <?= $category===$cat['slug']?'selected':'' ?>><?= $cat['name'] ?></option>
      <?php endforeach; ?>
    </select>
    <select name="sort" class="filter-select" onchange="this.form.submit()">
      <option value="newest"     <?= $sort==='newest'    ?'selected':''?>>Newest First</option>
      <option value="price_asc"  <?= $sort==='price_asc' ?'selected':''?>>Price: Low → High</option>
      <option value="price_desc" <?= $sort==='price_desc'?'selected':''?>>Price: High → Low</option>
      <option value="name"       <?= $sort==='name'      ?'selected':''?>>Name A–Z</option>
    </select>
    <button type="submit" class="btn btn-primary">Search</button>
    <?php if ($search || $category): ?>
      <a href="<?= base('pages/products.php') ?>" class="btn btn-outline btn-sm">Clear</a>
    <?php endif; ?>
  </form>

  <p style="margin-bottom:1.5rem;color:var(--text-muted);font-size:.9rem"><?= $total ?> product<?= $total!=1?'s':'' ?> found</p>

  <?php if (empty($products)): ?>
    <div style="text-align:center;padding:4rem;color:var(--text-muted)">
      <div style="font-size:3rem;margin-bottom:1rem">😔</div>
      <p>No products found.</p>
      <a href="<?= base('pages/products.php') ?>" class="btn btn-primary" style="margin-top:1rem">View All</a>
    </div>
  <?php else: ?>
    <div class="products-grid">
      <?php foreach ($products as $p): ?>
        <div class="product-card">
          <a href="<?= base('pages/product-detail.php?id=') . $p['id'] ?>" class="product-img">
            <img src="<?= htmlspecialchars($p['image_url'] ?? 'https://via.placeholder.com/300x400') ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            <?php if ($p['stock']===0): ?>
              <span class="product-badge" style="background:#6b7280">Out of Stock</span>
            <?php elseif ($p['stock']<5): ?>
              <span class="product-badge">Low Stock</span>
            <?php endif; ?>
          </a>
          <div class="product-info">
            <div class="product-category"><?= htmlspecialchars($p['cat_name']??'') ?></div>
            <h3 class="product-name"><?= htmlspecialchars($p['name']) ?></h3>
            <div class="product-price"><?= formatPrice($p['price']) ?></div>
            <div class="product-actions">
              <?php if ($p['stock']>0): ?>
                <button class="btn btn-primary" style="flex:1"
                  onclick="addToCart(<?= $p['id'] ?>, '<?= addslashes($p['name']) ?>', <?= $p['price'] ?>, '<?= addslashes($p['image_url']??'') ?>', '<?= addslashes($p['cat_name']??'') ?>')">
                  Add to Cart
                </button>
              <?php else: ?>
                <button class="btn btn-primary" style="flex:1" disabled>Out of Stock</button>
              <?php endif; ?>
              <a href="<?= base('pages/product-detail.php?id=') . $p['id'] ?>" class="btn btn-outline">View</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if ($pages>1): ?>
      <div class="pagination">
        <?php for ($i=1;$i<=$pages;$i++): ?>
          <a href="<?= base('pages/products.php') ?>?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&sort=<?= $sort ?>"
             class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div><br>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
