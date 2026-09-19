<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO regions (state_name, zone) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['state_name'], $_POST['zone']);
    $stmt->execute();
    setFlash('success', 'Region added.');
    redirect('manage-regions.php');
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM regions WHERE id=$id");
    setFlash('success', 'Region deleted.');
    redirect('manage-regions.php');
}

$flash = getFlash();
$regions = $conn->query("SELECT r.*, (SELECT COUNT(*) FROM artisans WHERE region_id=r.id) AS artisans
                         FROM regions r ORDER BY r.state_name");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Regions</title>
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
  <h1>Manage Regions</h1>

  <?php if($flash): ?>
    <div class="alert-success">✔ <?= e($flash['msg']) ?></div>
  <?php endif; ?>

  <div style="background:#fff;padding:32px;border-radius:14px;border:1px solid var(--border);margin-bottom:36px;box-shadow:var(--shadow-sm);">
    <h2 style="font-family:var(--font-display);font-size:22px;color:var(--indigo);margin-bottom:20px;">Add New Region</h2>
    <form method="POST" style="display:grid;grid-template-columns:2fr 1fr auto;gap:16px;align-items:start;">
      <input type="text" name="state_name" placeholder="State Name" required
             style="padding:14px 18px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);font-family:inherit;">
      <select name="zone" required
              style="padding:14px 18px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);font-family:inherit;">
        <option value="">Zone</option>
        <option>North</option><option>South</option><option>East</option>
        <option>West</option><option>Central</option><option>North-East</option>
      </select>
      <button class="btn btn-primary" name="add">+ Add</button>
    </form>
  </div>

  <table class="admin-table">
    <thead><tr><th>ID</th><th>State</th><th>Zone</th><th>Artisans</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($r = $regions->fetch_assoc()): ?>
      <tr>
        <td>#<?= $r['id'] ?></td>
        <td><strong style="color:var(--indigo);"><?= e($r['state_name']) ?></strong></td>
        <td><span class="badge badge-warn"><?= e($r['zone']) ?></span></td>
        <td><?= $r['artisans'] ?></td>
        <td>
          <a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-primary"
             onclick="return confirm('Delete this region?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>
</body>
</html>