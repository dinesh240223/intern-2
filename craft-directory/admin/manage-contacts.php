<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contacts WHERE id=$id");
    setFlash('success', 'Message deleted.');
    redirect('manage-contacts.php');
}

$flash = getFlash();
$contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Messages</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">

<aside class="admin-side">
  <h3>◆ Craft Admin</h3>
  <a href="dashboard.php"       class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">📊 Dashboard</a>
  <a href="manage-artisans.php" class="<?= basename($_SERVER['PHP_SELF'])=='manage-artisans.php'?'active':'' ?>">👨‍🎨 Manage Artisans</a>
  <a href="manage-crafts.php"   class="<?= basename($_SERVER['PHP_SELF'])=='manage-crafts.php'?'active':'' ?>">🎭 Manage Crafts</a>
  <a href="manage-regions.php"  class="<?= basename($_SERVER['PHP_SELF'])=='manage-regions.php'?'active':'' ?>">🌍 Manage Regions</a>
  <a href="manage-contacts.php" class="<?= basename($_SERVER['PHP_SELF'])=='manage-contacts.php'?'active':'' ?>">📬 Contact Messages</a>
  <a href="pending-admins.php"  class="<?= basename($_SERVER['PHP_SELF'])=='pending-admins.php'?'active':'' ?>">🔔 Pending Admins</a>
  <a href="manage-admins.php"   class="<?= basename($_SERVER['PHP_SELF'])=='manage-admins.php'?'active':'' ?>">👥 All Admins</a>
  <a href="add-artisan.php"     class="<?= basename($_SERVER['PHP_SELF'])=='add-artisan.php'?'active':'' ?>">➕ Add Artisan</a>
  <a href="logout.php">🚪 Logout</a>
</aside>

<main class="admin-main">
  <h1>Contact Messages</h1>

  <?php if($flash): ?>
    <div class="alert-success">✔ <?= e($flash['msg']) ?></div>
  <?php endif; ?>

  <?php if ($contacts->num_rows === 0): ?>
    <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:16px;border:1px dashed var(--border);">
      <div style="font-size:44px;color:var(--saffron);margin-bottom:12px;">📭</div>
      <h3 style="font-family:var(--font-display);font-size:24px;color:var(--indigo);margin-bottom:8px;">No messages yet</h3>
      <p style="color:var(--muted);">Contact form submissions will appear here.</p>
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Date</th><th>Name</th><th>Email</th>
          <th>Subject</th><th>Message</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($c = $contacts->fetch_assoc()): ?>
        <tr>
          <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
          <td><strong style="color:var(--indigo);"><?= e($c['name']) ?></strong></td>
          <td><a href="mailto:<?= e($c['email']) ?>" style="color:var(--terracotta);"><?= e($c['email']) ?></a></td>
          <td><?= e($c['subject']) ?></td>
          <td style="max-width:320px;"><?= e(excerpt($c['message'], 100)) ?></td>
          <td>
            <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-primary"
               onclick="return confirm('Delete this message?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>
</body>
</html>