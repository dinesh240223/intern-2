<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$err = '';
$success = false;
$newUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $pass     = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$username || !$fullname || !$email || !$pass) {
        $err = "All fields are required.";
    } elseif (!preg_match('/^[a-zA-Z0-9._]{3,50}$/', $username)) {
        $err = "Username must be 3–50 characters (letters, numbers, dot, underscore).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email address.";
    } elseif (strlen($pass) < 6) {
        $err = "Password must be at least 6 characters.";
    } elseif ($pass !== $confirm) {
        $err = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM admins WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if ($stmt->get_result()->num_rows) {
            $err = "Username already taken. Please choose another.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $ins->bind_param("ss", $username, $hash);

            if ($ins->execute()) {
                $conn->query("INSERT INTO admin_activity_log (action, target_username, performed_by, details)
                              VALUES ('register', '".$conn->real_escape_string($username)."', 'self', 'New admin registered')");
                $success = true;
                $newUsername = $username;
            } else {
                $err = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Registration — Craft Directory</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-page">

<div class="float-symbols" aria-hidden="true">
  <span style="--x:6%;--y:25%;--d:0s">◆</span>
  <span style="--x:18%;--y:65%;--d:2s">✦</span>
  <span style="--x:80%;--y:20%;--d:1s">❋</span>
  <span style="--x:88%;--y:70%;--d:3s">◆</span>
</div>

<div class="auth-shell">

  <aside class="auth-brand">
    <div class="brand-inner">
      <a href="../index.php" class="brand-logo">
        <span class="brand-mark">◆</span>
        <span>Craft<span class="accent">Directory</span></span>
      </a>

      <div class="brand-copy">
        <span class="brand-eyebrow">Join the Team</span>
        <h1>Become an <em>Administrator</em></h1>
        <p>Help us preserve India's traditional crafts by managing artisan profiles, verifying data, and supporting cultural heritage.</p>
      </div>

      <ul class="brand-features">
        <li><span class="tick">✓</span> Direct artisan management</li>
        <li><span class="tick">✓</span> Craft & region cataloguing</li>
        <li><span class="tick">✓</span> Real-time verification tools</li>
      </ul>

      <div class="brand-footer">
        <span>© <?= date('Y') ?> Traditional Craft Directory</span>
      </div>
    </div>
    <div class="brand-decor"></div>
  </aside>

  <section class="auth-form-wrap">
    <div class="auth-card">

      <?php if ($success): ?>

        <div class="success-state">
          <div class="success-icon">✓</div>
          <h2>Account Created!</h2>
          <p class="subtitle">Your admin account is ready to use</p>

          <div class="auth-alert auth-alert-info" style="margin-top:20px;">
            <span class="icon">✔</span>
            Username: <strong><?= htmlspecialchars($newUsername) ?></strong>
          </div>

          <a href="login.php" class="auth-btn" style="margin-top:20px;">
            <span>Go to Login</span>
            <span class="arrow">→</span>
          </a>
        </div>

      <?php else: ?>

        <header class="auth-head">
          <span class="auth-badge">New Registration</span>
          <h2>Create your account</h2>
          <p>Fill in your details to get started</p>
        </header>

        <?php if ($err): ?>
          <div class="auth-alert auth-alert-error">
            <span class="icon">⚠</span> <?= htmlspecialchars($err) ?>
          </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off" class="auth-form">
          <div class="field">
            <label for="full_name">Full Name</label>
            <div class="field-wrap">
              <span class="field-icon">👤</span>
              <input type="text" id="full_name" name="full_name" placeholder="Your full name"
                     required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
            </div>
          </div>

          <div class="field">
            <label for="username">Username</label>
            <div class="field-wrap">
              <span class="field-icon">@</span>
              <input type="text" id="username" name="username" placeholder="letters, numbers, . _"
                     required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
          </div>

          <div class="field">
            <label for="email">Email Address</label>
            <div class="field-wrap">
              <span class="field-icon">✉</span>
              <input type="email" id="email" name="email" placeholder="you@example.com"
                     required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <div class="field-wrap">
              <span class="field-icon">🔒</span>
              <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required>
              <button type="button" class="eye-toggle" onclick="togglePassword('password', this)">👁</button>
            </div>
          </div>

          <div class="field">
            <label for="confirm_password">Confirm Password</label>
            <div class="field-wrap">
              <span class="field-icon">🔒</span>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
              <button type="button" class="eye-toggle" onclick="togglePassword('confirm_password', this)">👁</button>
            </div>
          </div>

          <button type="submit" class="auth-btn">
            <span>Create Account</span>
            <span class="arrow">→</span>
          </button>
        </form>

        <div class="auth-divider"><span>or</span></div>

        <div class="auth-footer">
          <p>Already have an account?</p>
          <a href="login.php" class="btn-ghost-auth">Sign in →</a>
        </div>

        <a href="../index.php" class="back-to-site">← Back to site</a>

      <?php endif; ?>

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