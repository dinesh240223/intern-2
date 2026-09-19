<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

$totalArtisans  = $conn->query("SELECT COUNT(*) c FROM artisans")->fetch_assoc()['c'];
$pendingArts    = $conn->query("SELECT COUNT(*) c FROM artisans WHERE is_verified=0")->fetch_assoc()['c'];
$totalCrafts    = $conn->query("SELECT COUNT(*) c FROM crafts")->fetch_assoc()['c'];
$totalViews     = $conn->query("SELECT SUM(profile_views) c FROM artisans")->fetch_assoc()['c'] ?? 0;
$totalAdmins    = $conn->query("SELECT COUNT(*) c FROM admins")->fetch_assoc()['c'];
$totalContacts  = $conn->query("SELECT COUNT(*) c FROM contacts")->fetch_assoc()['c'];

$recent = $conn->query("SELECT a.*, c.craft_name FROM artisans a
                        LEFT JOIN crafts c ON a.craft_id=c.id
                        ORDER BY a.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — Craft Directory</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<style>
  @media(max-width:1024px){ .dash-grid{grid-template-columns:repeat(2,1fr)!important} }
  @media(max-width:768px){ .dash-grid{grid-template-columns:1fr!important} }
</style>
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
  <h1>Welcome back, <?= e($_SESSION['admin']) ?> 👋</h1>

  <div class="dash-grid" style="grid-template-columns:repeat(6,1fr);">
    <div class="dash-card"><h4><?= $totalArtisans ?></h4><p>Total Artisans</p></div>
    <div class="dash-card"><h4><?= $totalCrafts ?></h4><p>Total Crafts</p></div>
    <div class="dash-card"><h4><?= $pendingArts ?></h4><p>Pending Verifications</p></div>
    <div class="dash-card"><h4><?= $totalViews ?></h4><p>Profile Views</p></div>
    <div class="dash-card" style="cursor:pointer;" onclick="location.href='manage-admins.php'">
      <h4><?= $totalAdmins ?></h4><p>Active Admins</p>
    </div>
    <div class="dash-card" style="cursor:pointer;" onclick="location.href='manage-contacts.php'">
      <h4><?= $totalContacts ?></h4><p>Messages</p>
    </div>
  </div>

  <h2 style="font-family:var(--font-display);font-size:24px;color:var(--indigo);margin:36px 0 20px;">
    Recently Added Artisans
  </h2>
  <table class="admin-table">
    <thead>
      <tr><th>Name</th><th>Craft</th><th>Experience</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php if($recent->num_rows === 0): ?>
        <tr><td colspan="4" style="text-align:center;color:var(--muted);font-style:italic;padding:40px;">No artisans yet.</td></tr>
      <?php else: while($r = $recent->fetch_assoc()): ?>
      <tr>
        <td><strong style="color:var(--indigo);"><?= e($r['name']) ?></strong></td>
        <td><?= e($r['craft_name']) ?></td>
        <td><?= $r['experience_years'] ?> yrs</td>
        <td>
          <?php if($r['is_verified']): ?>
            <span class="badge badge-success">Verified</span>
          <?php else: ?>
            <span class="badge badge-warn">Pending</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; endif; ?>
    </tbody>
  </table>
</main>
</body>
</html>