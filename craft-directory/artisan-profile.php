<?php
include 'includes/db.php';
include 'includes/header.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { echo "<div class='container section'><h2>Invalid Artisan</h2></div>"; include 'includes/footer.php'; exit; }

$conn->query("UPDATE artisans SET profile_views = profile_views + 1 WHERE id=$id");

$sql = "SELECT a.*, c.craft_name, c.description AS craft_desc, cat.category_name, r.state_name, r.zone
        FROM artisans a
        LEFT JOIN crafts c ON a.craft_id=c.id
        LEFT JOIN categories cat ON c.category_id=cat.id
        LEFT JOIN regions r ON a.region_id=r.id
        WHERE a.id=$id";
$res = $conn->query($sql);
if ($res->num_rows === 0) { echo "<div class='container section'><h2>Artisan not found</h2></div>"; include 'includes/footer.php'; exit; }
$a = $res->fetch_assoc();
?>

<section class="profile-hero">
  <div class="container profile-header">
    <div class="profile-avatar"><?= strtoupper(substr($a['name'],0,1)) ?></div>
    <div class="profile-info">
      <span class="eyebrow"><?= htmlspecialchars($a['category_name']) ?></span>
      <h1><?= htmlspecialchars($a['name']) ?>
        <?php if($a['is_verified']): ?><span class="verified-badge">✔ Verified Artisan</span><?php endif; ?>
      </h1>
      <p class="profile-meta">
        🎨 <strong><?= htmlspecialchars($a['craft_name']) ?></strong> &nbsp;·&nbsp;
        📍 <strong><?= htmlspecialchars($a['state_name']) ?></strong> (<?= htmlspecialchars($a['zone']) ?> India) &nbsp;·&nbsp;
        ⏳ <strong><?= $a['experience_years'] ?> years</strong> of mastery
      </p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container profile-grid">
    <div class="profile-main">
      <div class="info-block">
        <h2>About the Artisan</h2>
        <p><?= nl2br(htmlspecialchars($a['description'])) ?></p>
      </div>

      <div class="info-block">
        <h2>Technique & Materials</h2>
        <p><?= nl2br(htmlspecialchars($a['technique'])) ?></p>
      </div>

      <div class="info-block">
        <h2>The Craft — <?= htmlspecialchars($a['craft_name']) ?></h2>
        <p><?= nl2br(htmlspecialchars($a['craft_desc'])) ?></p>
      </div>
    </div>

    <aside class="profile-side">
      <div class="contact-card">
        <h3>Contact the Artisan</h3>
        <p><span>📞</span> <?= htmlspecialchars($a['contact_number']) ?></p>
        <p><span>✉</span> <?= htmlspecialchars($a['email']) ?></p>
        <p><span>🏠</span> <?= htmlspecialchars($a['workshop_address']) ?></p>
        <a href="tel:<?= htmlspecialchars($a['contact_number']) ?>" class="btn btn-primary btn-block">📞 Call Now</a>
        <a href="mailto:<?= htmlspecialchars($a['email']) ?>" class="btn btn-outline btn-block">✉ Send Email</a>
      </div>
      <div class="stats-card">
        <div><strong><?= $a['profile_views'] ?></strong><span>Profile Views</span></div>
        <div><strong><?= $a['experience_years'] ?></strong><span>Years Experience</span></div>
      </div>
    </aside>
  </div>
</section>

<?php include 'includes/footer.php'; ?>