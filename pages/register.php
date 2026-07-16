<?php
$pageTitle = 'Register — Inzira Fashion';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
if (isLoggedIn()) { header('Location: ' . base('index.php')); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password']   ?? '';
    $conf  = $_POST['confirm']    ?? '';
    if (!$name||!$email||!$pass) { $error='All fields are required.'; }
    elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) { $error='Invalid email.'; }
    elseif (strlen($pass)<6) { $error='Password must be at least 6 characters.'; }
    elseif ($pass!==$conf) { $error='Passwords do not match.'; }
    else {
        $db=$db=getDB(); $check=$db->prepare("SELECT id FROM users WHERE email=?"); $check->bind_param('s',$email); $check->execute();
        if ($check->get_result()->fetch_assoc()) { $error='Email already registered.'; }
        else {
            $hash=password_hash($pass,PASSWORD_DEFAULT);
            $stmt=$db->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)"); $stmt->bind_param('sss',$name,$email,$hash); $stmt->execute();
            $_SESSION['user_id']=$stmt->insert_id; $_SESSION['user_name']=$name; $_SESSION['role']='customer';
            header('Location: ' . base('index.php')); exit;
        }
    }
}
require_once __DIR__ . '/../includes/header.php';
?>
<div class="auth-container">
  <div class="auth-card">
    <div class="auth-logo"><h2>Create Account</h2><p style="color:var(--text-muted);font-size:.9rem;margin-top:.25rem">Join Inzira Fashion today</p></div>
    <?php if ($error): ?><div style="background:#fee2e2;color:#7f1d1d;padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.9rem">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group"><label>Full Name</label><input type="text" name="name" class="form-control" placeholder="Jean Paul Habimana" required value="<?= htmlspecialchars($_POST['name']??'') ?>"></div>
      <div class="form-group"><label>Email Address</label><input type="email" name="email" class="form-control" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>"></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" placeholder="At least 6 characters" required></div>
      <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm" class="form-control" placeholder="Repeat password" required></div>
      <button type="submit" class="btn btn-primary btn-full">Create Account →</button>
    </form>
    <div class="auth-switch">Already have an account? <a href="<?= base('pages/login.php') ?>">Login</a></div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
