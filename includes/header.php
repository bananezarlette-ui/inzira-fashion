<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? SITE_NAME ?></title>
  <meta name="description" content="<?= $pageDesc ?? "Rwanda's premier online fashion store — Inzira Fashion" ?>">
  <link rel="stylesheet" href="<?= base('assets/css/style.css') ?>">
</head>
<body>

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Navbar -->
<nav class="navbar">
  <div class="container">
    <div class="nav-inner">
      <a href="<?= base('index.php') ?>" class="nav-logo">
        <span>Inzira</span><span> Fashion</span>
      </a>

      <div class="nav-links">
        <a href="<?= base('index.php') ?>">Home</a>
        <a href="<?= base('pages/products.php') ?>">Shop</a>
        <a href="<?= base('pages/products.php?category=women') ?>">Women</a>
        <a href="<?= base('pages/products.php?category=men') ?>">Men</a>
        <a href="<?= base('pages/products.php?category=accessories') ?>">Accessories</a>
      </div>

      <div class="nav-actions">
        <button class="nav-icon" onclick="toggleCart()" title="Cart">
          🛒
          <span class="cart-badge" id="cartBadge">0</span>
        </button>

        <?php if (isLoggedIn()): ?>
          <a href="<?= base('pages/my-orders.php') ?>" class="btn btn-outline btn-sm">My Orders</a>
          <a href="<?= base('pages/logout.php') ?>" class="btn btn-primary btn-sm">Logout</a>
        <?php else: ?>
          <a href="<?= base('pages/login.php') ?>" class="btn btn-primary btn-sm">Login</a>
        <?php endif; ?>

        <button class="hamburger" onclick="toggleMobile()" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile nav -->
  <div class="mobile-menu" id="mobileMenu">
    <a href="<?= base('index.php') ?>">Home</a>
    <a href="<?= base('pages/products.php') ?>">Shop</a>
    <a href="<?= base('pages/products.php?category=women') ?>">Women</a>
    <a href="<?= base('pages/products.php?category=men') ?>">Men</a>
    <a href="<?= base('pages/products.php?category=accessories') ?>">Accessories</a>
    <?php if (isLoggedIn()): ?>
      <a href="<?= base('pages/my-orders.php') ?>">My Orders</a>
      <a href="<?= base('pages/logout.php') ?>">Logout</a>
    <?php else: ?>
      <a href="<?= base('pages/login.php') ?>">Login / Register</a>
    <?php endif; ?>
  </div>
</nav>

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <h3>🛒 Shopping Cart</h3>
    <button class="close-btn" onclick="toggleCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItemsList"></div>
  <div class="cart-footer" id="cartFooter"></div>
</div>

<script>
// Pass base URL to JavaScript
const BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= base('assets/js/cart.js') ?>"></script>
<script src="<?= base('assets/js/app.js') ?>"></script>
