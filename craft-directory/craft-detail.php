<?php
include 'includes/db.php';
include 'includes/functions.php';
include 'includes/header.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { redirect('crafts.php'); }

$craft = $conn->query("SELECT c.*, cat.category_name, r.state_name, r.zone
                       FROM crafts c
                       LEFT JOIN categories cat ON c.category_id=cat.id
                       LEFT JOIN regions r ON c.region_id=r.id
                       WHERE c.id=$id")->fetch_assoc();

if (!$craft) { redirect('crafts.php'); }

$artisans = $conn->query("SELECT * FROM artisans WHERE craft_id=$id ORDER BY is_verified DESC, experience_years DESC");
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow"><?= e($craft['category_name']) ?></span>
    <h1><?= e($craft['craft_name']) ?></h1>
    <p>📍 Origin: <?= e($craft['state_name']) ?> (<?= e($craft['zone']) ?> India) · <?= $artisans->num_rows ?> artisan(s) listed</p>
  </div>
</section>

<section class="section">
  <div class="container narrow">
    <div class="info-block">
      <h2>About the Craft</h2>
      <p><?= nl2br(e($craft['description'])) ?></p>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Craftspeople</span>
      <h2>Artisans Practicing <?= e($craft['craft_name']) ?></h2>
      <p>Connect directly with verified master craftspeople from this tradition.</p>
    </div>
    <div class="grid grid-4">
      <?php if ($artisans->num_rows === 0): ?>
        <div style="grid-column:1/-1;text-align:center;padding:60px 20px;">
          <h3 style="font-family:var(--font-display);font-size:26px;color:var(--indigo);">No artisans listed yet</h3>
          <p style="color:var(--muted);">We're continuously onboarding verified craftspeople.</p>
        </div>
      <?php else: while($a = $artisans->fetch_assoc()): ?>
        <div class="card artisan-card">
          <div class="artisan-avatar">
            <?= strtoupper(substr($a['name'],0,1)) ?>
            <?php if($a['is_verified']): ?><span class="verified-dot">✔</span><?php endif; ?>
          </div>
          <h4><?= e($a['name']) ?></h4>
          <span class="craft-tag"><?= experienceBadge($a['experience_years']) ?></span>
          <p class="region">📍 <?= e($craft['state_name']) ?> · <?= $a['experience_years'] ?> yrs</p>
          <a href="artisan-profile.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-primary">View Profile</a>
        </div>
      <?php endwhile; endif; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>