<?php
require_once __DIR__ . '/../../config/app.php';
?>
<aside class="admin-sidebar">
  <div class="sidebar-logo">
    <span style="color:var(--brand);font-weight:800">Inzira</span>
    <span style="color:#9ca3af;font-weight:300"> Admin</span>
  </div>
  <nav class="sidebar-nav">
    <a href="<?= base('admin/index.php') ?>"                class="<?= basename($_SERVER['PHP_SELF'])==='index.php'     ?'active':''?>">📊 Dashboard</a>
    <a href="<?= base('admin/pages/products.php') ?>"       class="<?= basename($_SERVER['PHP_SELF'])==='products.php'  ?'active':''?>">👕 Products</a>
    <a href="<?= base('admin/pages/orders.php') ?>"         class="<?= basename($_SERVER['PHP_SELF'])==='orders.php'    ?'active':''?>">📦 Orders</a>
    <a href="<?= base('admin/pages/users.php') ?>"          class="<?= basename($_SERVER['PHP_SELF'])==='users.php'     ?'active':''?>">👥 Users</a>
    <a href="<?= base('admin/pages/categories.php') ?>"     class="<?= basename($_SERVER['PHP_SELF'])==='categories.php'?'active':''?>">🏷️ Categories</a>
    <hr style="border-color:#374151;margin:.75rem 0">
    <a href="<?= base('index.php') ?>">🏠 View Store</a>
    <a href="<?= base('pages/logout.php') ?>" style="color:#f87171">🚪 Logout</a>
  </nav>
</aside>
