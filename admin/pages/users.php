<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/app.php';
requireAdmin(); $db = getDB();
$users = $db->query("SELECT u.*, COUNT(o.id) AS order_count FROM users u LEFT JOIN orders o ON u.id=o.user_id GROUP BY u.id ORDER BY u.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Users — Admin</title>
<link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base('admin/admin.css') ?>">
</head><body class="admin-body"><div class="admin-layout">
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
  <div class="admin-topbar"><h1>Users (<?= count($users) ?>)</h1></div>
  <div class="admin-card"><div style="overflow-x:auto">
    <table class="orders-table">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Orders</th><th>Joined</th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td>#<?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="status-badge <?= $u['role']==='admin'?'status-confirmed':'status-pending'?>"><?= ucfirst($u['role']) ?></span></td>
            <td><?= $u['order_count'] ?></td>
            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div></div>
</main></div></body></html>
