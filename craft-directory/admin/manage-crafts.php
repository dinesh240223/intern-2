<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO crafts (craft_name,category_id,region_id,description) VALUES (?,?,?,?)");
    $stmt->bind_param("siis", $_POST['craft_name'], $_POST['category_id'], $_POST['region_id'], $_POST['description']);
    $stmt->execute();
    setFlash('success', 'Craft added successfully.');
    redirect('manage-crafts.php');
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM crafts WHERE id=$id");
    setFlash('success', 'Craft deleted.');
    redirect('manage-crafts.php');
}

$flash = getFlash();
$crafts = $conn->query("SELECT c.*, cat.category_name, r.state_name
                        FROM crafts c
                        LEFT JOIN categories cat ON c.category_id=cat.id
                        LEFT JOIN regions r ON c.region_id=r.id
                        ORDER BY c.craft_name");
$cats = $conn->query("SELECT * FROM categories ORDER BY category_name");
$regions = $conn->query("SELECT * FROM regions ORDER BY state_name");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Crafts</title>
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
  <h1>Manage Crafts</h1>

  <?php if($flash): ?>
    <div class="alert-<?= $flash['type']==='success'?'success':'error' ?>"><?= e($flash['msg']) ?></div>
  <?php endif; ?>

  <div style="background:#fff;padding:32px;border-radius:14px;border:1px solid var(--border);margin-bottom:36px;box-shadow:var(--shadow-sm);">
    <h2 style="font-family:var(--font-display);font-size:22px;color:var(--indigo);margin-bottom:20px;">Add New Craft</h2>
    <form method="POST" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
      <input type="text" name="craft_name" placeholder="Craft Name" required
             style="padding:14px 18px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);font-family:inherit;">
      <select name="category_id" required
              style="padding:14px 18px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);font-family:inherit;">
        <option value="">Select Category</option>
        <?php while($c=$cats->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>"><?= e($c['category_name']) ?></option>
        <?php endwhile; ?>
      </select>
      <select name="region_id" required
              style="padding:14px 18px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);font-family:inherit;">
        <option value="">Select Region</option>
        <?php while($r=$regions->fetch_assoc()): ?>
          <option value="<?= $r['id'] ?>"><?= e($r['state_name']) ?></option>
        <?php endwhile; ?>
      </select>
      <input type="text" name="description" placeholder="Short Description" required
             style="padding:14px 18px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);font-family:inherit;">
      <button class="btn btn-primary" name="add" style="grid-column:1/-1;justify-self:start;">+ Add Craft</button>
    </form>
  </div>

  <table class="admin-table">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Region</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($c = $crafts->fetch_assoc()): ?>
      <tr>
        <td>#<?= $c['id'] ?></td>
        <td><strong style="color:var(--indigo);"><?= e($c['craft_name']) ?></strong></td>
        <td><?= e($c['category_name']) ?></td>
        <td><?= e($c['state_name']) ?></td>
        <td>
          <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-primary"
             onclick="return confirm('Delete this craft?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>
</body>
</html>