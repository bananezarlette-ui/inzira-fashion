<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/app.php';
requireAdmin(); $db = getDB(); $msg = '';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action = $_POST['action']??'';
    if ($action==='delete') {
        $id=intval($_POST['id']); $stmt=$db->prepare("DELETE FROM products WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute(); $msg='Product deleted.';
    } elseif ($action==='add'||$action==='edit') {
        $name=sanitize($_POST['name']); $desc=sanitize($_POST['description']); $price=floatval($_POST['price']); $stock=intval($_POST['stock']); $img=sanitize($_POST['image_url']); $catId=intval($_POST['category_id']);
        if ($action==='add') { $s=$db->prepare("INSERT INTO products (name,description,price,stock,image_url,category_id) VALUES (?,?,?,?,?,?)"); $s->bind_param('ssdiis',$name,$desc,$price,$stock,$img,$catId); $s->execute(); $msg='Product added!'; }
        else { $pid=intval($_POST['product_id']); $s=$db->prepare("UPDATE products SET name=?,description=?,price=?,stock=?,image_url=?,category_id=? WHERE id=?"); $s->bind_param('ssdiisi',$name,$desc,$price,$stock,$img,$catId,$pid); $s->execute(); $msg='Product updated!'; }
    }
}
$products   = $db->query("SELECT p.*,c.name AS cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC")->fetch_all(MYSQLI_ASSOC);
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$editProduct = null;
if (isset($_GET['edit'])) { foreach ($products as $pp) { if ($pp['id']==intval($_GET['edit'])) { $editProduct=$pp; break; } } }
$pageTitle = 'Products — Admin';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base('admin/admin.css') ?>">
</head><body class="admin-body"><div class="admin-layout">
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
  <div class="admin-topbar"><h1><?= $editProduct?'Edit Product':'Products' ?></h1><a href="<?= base('admin/pages/products.php?add=1') ?>" class="btn btn-primary btn-sm">+ Add Product</a></div>
  <?php if ($msg): ?><div style="margin:1rem 2rem;background:#dcfce7;color:#14532d;padding:.75rem 1rem;border-radius:8px">✅ <?= $msg ?></div><?php endif; ?>
  <?php if ($editProduct||isset($_GET['add'])): ?>
    <div class="admin-card admin-form">
      <h2 class="admin-card-title"><?= $editProduct?'Edit: '.htmlspecialchars($editProduct['name']):'Add New Product' ?></h2>
      <form method="POST" action="<?= base('admin/pages/products.php') ?>">
        <input type="hidden" name="action" value="<?= $editProduct?'edit':'add' ?>">
        <?php if ($editProduct): ?><input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>"><?php endif; ?>
        <div class="form-row">
          <div class="form-group"><label>Product Name *</label><input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($editProduct['name']??'') ?>"></div>
          <div class="form-group"><label>Category *</label><select name="category_id" class="form-control" required><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= ($editProduct['category_id']??'')==$c['id']?'selected':''?>><?= $c['name'] ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Price (RWF) *</label><input type="number" name="price" class="form-control" required min="0" value="<?= $editProduct['price']??'' ?>"></div>
          <div class="form-group"><label>Stock *</label><input type="number" name="stock" class="form-control" required min="0" value="<?= $editProduct['stock']??'' ?>"></div>
        </div>
        <div class="form-group"><label>Image URL</label><input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($editProduct['image_url']??'') ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($editProduct['description']??'') ?></textarea></div>
        <div style="display:flex;gap:1rem">
          <button type="submit" class="btn btn-primary"><?= $editProduct?'Update':'Add Product' ?></button>
          <a href="<?= base('admin/pages/products.php') ?>" class="btn btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  <?php endif; ?>
  <div class="admin-card"><div style="overflow-x:auto">
    <table class="orders-table">
      <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td>#<?= $p['id'] ?></td>
            <td><img src="<?= htmlspecialchars($p['image_url']??'') ?>" alt="" style="width:48px;height:56px;object-fit:cover;border-radius:6px"></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['cat_name']??'-') ?></td>
            <td><?= formatPrice($p['price']) ?></td>
            <td><?= $p['stock'] ?></td>
            <td>
              <a href="<?= base('admin/pages/products.php?edit=') . $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
              <form method="POST" action="<?= base('admin/pages/products.php') ?>" style="display:inline" onsubmit="return confirm('Delete this product?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-sm" style="background:#ef4444;color:#fff;border:none">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div></div>
</main></div></body></html>
