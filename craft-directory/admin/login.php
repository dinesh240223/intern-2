<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$info  = '';

if (isset($_GET['loggedout'])) $info = "You have been logged out successfully.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    if (!$u || !$p) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");
        $stmt->bind_param("s", $u);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows) {
            $admin = $res->fetch_assoc();
            if (password_verify($p, $admin['password'])) {
                $_SESSION['admin']    = $admin['username'];
                $_SESSION['admin_id'] = $admin['id'];
                session_regenerate_id(true);
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No admin account found with that username.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Craft Directory</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-page">

<!-- Floating decorative symbols -->
<div class="float-symbols" aria-hidden="true">
  <span style="--x:8%;--y:20%;--d:0s">◆</span>
  <span style="--x:15%;--y:70%;--d:2s">✦</span>
  <span style="--x:25%;--y:40%;--d:1s">❋</span>
  <span style="--x:75%;--y:15%;--d:3s">◆</span>
  <span style="--x:85%;--y:65%;--d:1.5s">✦</span>
  <span style="--x:92%;--y:35%;--d:2.5s">❋</span>
</div>

<div class="auth-shell">

  <!-- LEFT BRAND PANEL -->
  <aside class="auth-brand">
    <div class="brand-inner">
      <a href="../index.php" class="brand-logo">
        <span class="brand-mark">◆</span>
        <span>Craft<span class="accent">Directory</span></span>
      </a>

      <div class="brand-copy">
        <span class="brand-eyebrow">Administrator Access</span>
        <h1>Preserving India's <em>Living Craft Heritage</em></h1>
        <p>Manage artisans, crafts, and regions from a single verified dashboard. Built in collaboration with the Ministry of Textiles.</p>
      </div>

      <ul class="brand-stats">
        <li><strong>10+</strong><span>Artisans Verified</span></li>
        <li><strong>10+</strong><span>Traditional Crafts</span></li>
        <li><strong>12</strong><span>States Covered</span></li>
      </ul>

      <div class="brand-footer">
        <span>© <?= date('Y') ?> Traditional Craft Directory</span>
      </div>
    </div>
    <div class="brand-decor"></div>
  </aside>

  <!-- RIGHT FORM PANEL -->
  <section class="auth-form-wrap">
    <div class="auth-card">

      <header class="auth-head">
        <span class="auth-badge">Secure Login</span>
        <h2>Welcome back</h2>
        <p>Sign in to access your admin dashboard</p>
      </header>

      <?php if($info): ?>
        <div class="auth-alert auth-alert-info">
          <span class="icon">ℹ</span> <?= htmlspecialchars($info) ?>
        </div>
      <?php endif; ?>

      <?php if($error): ?>
        <div class="auth-alert auth-alert-error">
          <span class="icon">⚠</span> <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" autocomplete="off" class="auth-form">
        <div class="field">
          <label for="username">Username</label>
          <div class="field-wrap">
            <span class="field-icon">👤</span>
            <input type="text" id="username" name="username" placeholder="Enter your username"
                   required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="field-wrap">
            <span class="field-icon">🔒</span>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="button" class="eye-toggle" onclick="togglePassword('password', this)" aria-label="Show password">👁</button>
          </div>
        </div>

        <button type="submit" class="auth-btn">
          <span>Sign In</span>
          <span class="arrow">→</span>
        </button>
      </form>

      <div class="auth-divider"><span>or</span></div>

      <div class="auth-footer">
        <p>New administrator?</p>
        <a href="register.php" class="btn-ghost-auth">Create an account →</a>
      </div>

      <div class="auth-hint">
        <span>Default credentials:</span>
        <code>admin / admin123</code>
      </div>

      <a href="../index.php" class="back-to-site">← Back to site</a>
    </div>
  </section>

</div>

<script>
function togglePassword(id, btn){
  const el = document.getElementById(id);
  if (el.type === 'password'){ el.type = 'text'; btn.textContent = '🙈'; }
  else { el.type = 'password'; btn.textContent = '👁'; }
}
</script>
</body>
</html>