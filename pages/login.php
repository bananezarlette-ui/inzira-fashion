<?php
$pageTitle = 'Login — Inzira Fashion';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
if (isLoggedIn()) { header('Location: ' . base('index.php')); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    if ($email && $pass) {
        $db = getDB(); $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param('s', $email); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: ' . base('index.php')); exit;
        }
        $error = 'Invalid email or password.';
    } else { $error = 'Please fill in all fields.'; }
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="auth-container">
  <div class="auth-card">
    <div class="auth-logo"><h2>Inzira Fashion</h2><p style="color:var(--text-muted);font-size:.9rem;margin-top:.25rem">Welcome back!</p></div>
    <?php if ($error): ?><div style="background:#fee2e2;color:#7f1d1d;padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.9rem">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group"><label>Email Address</label><input type="email" name="email" class="form-control" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>"></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" placeholder="••••••••" required></div>
      <button type="submit" class="btn btn-primary btn-full">Login →</button>
    </form>
    <div class="auth-switch">Don't have an account? <a href="<?= base('pages/register.php') ?>">Create one</a></div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
