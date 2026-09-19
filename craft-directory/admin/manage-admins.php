<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

if (isset($_GET['delete'])) {
    $u = $conn->real_escape_string($_GET['delete']);
    if ($u === $_SESSION['admin']) {
        setFlash('error', "You cannot remove your own account.");
    } else {
        $conn->query("DELETE FROM admins WHERE username='$u'");
        $conn->query("INSERT INTO admin_activity_log (action, target_username, performed_by, details)
                      VALUES ('remove', '$u', '".$conn->real_escape_string($_SESSION['admin'])."', 'Admin removed')");
        setFlash('success', "Admin '$u' removed.");
    }
    redirect('manage-admins.php');
}

$flash = getFlash();
$allAdmins = $conn->query("SELECT * FROM admins ORDER BY username");
$log       = $conn->query("SELECT * FROM admin_activity_log ORDER BY performed_at DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Admins</title>
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
  <a href="manage-admins.php"   class="<?= basename($_SERVER['PHP_SELF'])=='manage-admins.php'?'active':'' ?>">👥 All Admins</a>
  <a href="add-artisan.php"     class="<?= basename($_SERVER['PHP_SELF'])=='add-artisan.php'?'active':'' ?>">➕ Add Artisan</a>
  <a href="logout.php">🚪 Logout</a>
</aside>

<main class="admin-main">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
    <h1 style="margin:0;">Admin Users</h1>
    <a href="register.php" class="btn btn-primary">+ Add New Admin</a>
  </div>

  <?php if($flash): ?>
    <div class="alert-<?= $flash['type']==='success'?'success':'error' ?>">
      <?= $flash['type']==='success'?'✔':'⚠' ?> <?= e($flash['msg']) ?>
    </div>
  <?php endif; ?>

  <h2 style="font-family:var(--font-display);font-size:22px;color:var(--indigo);margin:24px 0 16px;">
    Active Administrators (<?= $allAdmins->num_rows ?>)
  </h2>

  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Created</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($a = $allAdmins->fetch_assoc()): ?>
      <tr>
        <td>#<?= $a['id'] ?></td>
        <td>
          <strong style="color:var(--indigo);"><?= e($a['username']) ?></strong>
          <?php if($a['username'] === $_SESSION['admin']): ?>
            <span class="badge badge-success" style="margin-left:8px;">You</span>
          <?php endif; ?>
        </td>
        <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
        <td><span class="badge badge-success">Active</span></td>
        <td>
          <?php if($a['username'] !== $_SESSION['admin']): ?>
            <a href="?delete=<?= urlencode($a['username']) ?>"
               class="btn btn-sm btn-primary"
               onclick="return confirm('Remove <?= e($a['username']) ?> permanently?')">Remove</a>
          <?php else: ?>
            <span style="color:var(--muted);font-size:13px;">—</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h2 style="font-family:var(--font-display);font-size:22px;color:var(--indigo);margin:44px 0 16px;">
    Recent Admin Activity
  </h2>

  <?php if($log->num_rows === 0): ?>
    <div style="padding:40px;background:#fff;border-radius:14px;border:1px dashed var(--border);text-align:center;color:var(--muted);">
      No activity logged yet.
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>When</th>
          <th>Action</th>
          <th>Target</th>
          <th>Performed By</th>
        </tr>
      </thead>
      <tbody>
        <?php while($l = $log->fetch_assoc()): ?>
        <tr>
          <td><?= date('d M Y, H:i', strtotime($l['performed_at'])) ?></td>
          <td>
            <?php
            $badge = 'badge-warn';
            if ($l['action'] === 'register' || $l['action'] === 'approve') $badge = 'badge-success';
            ?>
            <span class="badge <?= $badge ?>"><?= e(ucfirst($l['action'])) ?></span>
          </td>
          <td><strong><?= e($l['target_username']) ?></strong></td>
          <td><?= e($l['performed_by']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>
</body>
</html>