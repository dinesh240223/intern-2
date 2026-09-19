<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

$res = $conn->query("SELECT a.*, c.craft_name, r.state_name FROM artisans a
                     LEFT JOIN crafts c ON a.craft_id=c.id
                     LEFT JOIN regions r ON a.region_id=r.id
                     ORDER BY a.id DESC");
$flash = getFlash();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Artisans</title>
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
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
    <h1 style="margin:0;">Manage Artisans</h1>
    <a href="add-artisan.php" class="btn btn-primary">+ Add New Artisan</a>
  </div>

  <?php if($flash): ?>
    <div class="alert-<?= $flash['type']==='success'?'success':'error' ?>"><?= e($flash['msg']) ?></div>
  <?php endif; ?>

  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Craft</th><th>State</th>
        <th>Experience</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if($res->num_rows === 0): ?>
        <tr><td colspan="7" style="text-align:center;color:var(--muted);font-style:italic;padding:40px;">No artisans yet. Click "Add New Artisan" to begin.</td></tr>
      <?php else: while($r = $res->fetch_assoc()): ?>
      <tr>
        <td>#<?= $r['id'] ?></td>
        <td><strong style="color:var(--indigo);"><?= e($r['name']) ?></strong></td>
        <td><?= e($r['craft_name']) ?></td>
        <td><?= e($r['state_name']) ?></td>
        <td><?= $r['experience_years'] ?> yrs</td>
        <td>
          <?php if($r['is_verified']): ?>
            <span class="badge badge-success">Verified</span>
          <?php else: ?>
            <span class="badge badge-warn">Pending</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="edit-artisan.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-ghost">Edit</a>
          <a href="delete-artisan.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-primary"
             onclick="return confirm('Delete <?= e($r['name']) ?>?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; endif; ?>
    </tbody>
  </table>
</main>
</body>
</html>