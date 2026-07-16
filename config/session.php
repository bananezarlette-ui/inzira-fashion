<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() { return isset($_SESSION['user_id']); }
function isAdmin()    { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }

function requireLogin() {
    if (!isLoggedIn()) { header('Location: ' . base('pages/login.php')); exit; }
}
function requireAdmin() {
    if (!isAdmin()) { header('Location: ' . base('index.php')); exit; }
}
