<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/app.php';
requireAdmin(); $db = getDB(); $msg = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=sanitize($_POST['name']??''); $slug=strtolower(preg_replace('/\s+/','-',$name));
    if ($name) { $s=$db->prepare("INSERT IGNORE INTO categories (name,slug) VALUES (?,?)"); $s->bind_param('ss',$name,$slug); $s->execute(); $msg='Category added!'; }
}
$cats = $db->query("SELECT c.*, COUNT(p.id) AS product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Categories — Admin</title>
<link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base('admin/admin.css') ?>">
</head><body class="admin-body"><div class="admin-layout">
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
  <div class="admin-topbar"><h1>Categories</h1></div>
  <?php if ($msg): ?><div style="margin:1rem 2rem;background:#dcfce7;color:#14532d;padding:.75rem 1rem;border-radius:8px">✅ <?= $msg ?></div><?php endif; ?>
  <div class="admin-card admin-form">
    <h2 class="admin-card-title">Add Category</h2>
    <form method="POST" action="<?= base('admin/pages/categories.php') ?>" style="display:flex;gap:1rem;align-items:flex-end">
      <div class="form-group" style="flex:1;margin:0"><input type="text" name="name" class="form-control" placeholder="Category name" required></div>
      <button type="submit" class="btn btn-primary">Add</button>
    </form>
  </div>
  <div class="admin-card">
    <table class="orders-table">
      <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Products</th></tr></thead>
      <tbody>
        <?php foreach ($cats as $c): ?>
          <tr><td>#<?= $c['id'] ?></td><td><?= htmlspecialchars($c['name']) ?></td><td><?= $c['slug'] ?></td><td><?= $c['product_count'] ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main></div></body></html>
