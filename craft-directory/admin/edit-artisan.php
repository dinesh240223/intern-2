<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { redirect('manage-artisans.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE artisans SET name=?,craft_id=?,region_id=?,experience_years=?,workshop_address=?,contact_number=?,email=?,description=?,technique=?,is_verified=? WHERE id=?");
    $v = isset($_POST['is_verified']) ? 1 : 0;
    $stmt->bind_param("siiisssssii",
        $_POST['name'], $_POST['craft_id'], $_POST['region_id'], $_POST['experience_years'],
        $_POST['workshop_address'], $_POST['contact_number'], $_POST['email'],
        $_POST['description'], $_POST['technique'], $v, $id);
    $stmt->execute();
    setFlash('success', 'Artisan updated successfully.');
    redirect('manage-artisans.php');
}

$artisan = $conn->query("SELECT * FROM artisans WHERE id=$id")->fetch_assoc();
if (!$artisan) { redirect('manage-artisans.php'); }
$crafts  = $conn->query("SELECT * FROM crafts ORDER BY craft_name");
$regions = $conn->query("SELECT * FROM regions ORDER BY state_name");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Artisan</title>
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
  <h1>Edit Artisan — <?= e($artisan['name']) ?></h1>

  <form method="POST" class="contact-form" style="max-width:780px;">
    <input type="text" name="name" value="<?= e($artisan['name']) ?>" required>

    <select name="craft_id" required
            style="width:100%;padding:16px 20px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14.5px;margin-bottom:18px;background:var(--cream);">
      <?php while($c=$crafts->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" <?= $artisan['craft_id']==$c['id']?'selected':'' ?>><?= e($c['craft_name']) ?></option>
      <?php endwhile; ?>
    </select>

    <select name="region_id" required
            style="width:100%;padding:16px 20px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14.5px;margin-bottom:18px;background:var(--cream);">
      <?php while($r=$regions->fetch_assoc()): ?>
        <option value="<?= $r['id'] ?>" <?= $artisan['region_id']==$r['id']?'selected':'' ?>><?= e($r['state_name']) ?></option>
      <?php endwhile; ?>
    </select>

    <input type="number" name="experience_years" value="<?= $artisan['experience_years'] ?>" required>
    <input type="text" name="contact_number" value="<?= e($artisan['contact_number']) ?>" required>
    <input type="email" name="email" value="<?= e($artisan['email']) ?>" required>
    <input type="text" name="workshop_address" value="<?= e($artisan['workshop_address']) ?>" required>
    <textarea name="description" rows="4" required><?= e($artisan['description']) ?></textarea>
    <textarea name="technique" rows="3" required><?= e($artisan['technique']) ?></textarea>

    <label style="display:flex;align-items:center;gap:10px;margin-bottom:20px;font-size:14.5px;color:var(--indigo);">
      <input type="checkbox" name="is_verified" value="1" <?= $artisan['is_verified']?'checked':'' ?> style="width:auto;margin:0;">
      Verified
    </label>

    <button class="btn btn-primary">Update Artisan</button>
    <a href="manage-artisans.php" class="btn btn-ghost">Cancel</a>
  </form>
</main>
</body>
</html>