<?php
include 'includes/db.php';
include 'includes/header.php';

$totalArtisans = $conn->query("SELECT COUNT(*) c FROM artisans")->fetch_assoc()['c'];
$totalCrafts   = $conn->query("SELECT COUNT(*) c FROM crafts")->fetch_assoc()['c'];
$totalRegions  = $conn->query("SELECT COUNT(*) c FROM regions")->fetch_assoc()['c'];
$totalVerified = $conn->query("SELECT COUNT(*) c FROM artisans WHERE is_verified=1")->fetch_assoc()['c'];

$crafts  = $conn->query("SELECT c.*, cat.category_name, r.state_name FROM crafts c
                         LEFT JOIN categories cat ON c.category_id=cat.id
                         LEFT JOIN regions r ON c.region_id=r.id LIMIT 6");
$artisans = $conn->query("SELECT a.*, cr.craft_name, r.state_name FROM artisans a
                          LEFT JOIN crafts cr ON a.craft_id=cr.id
                          LEFT JOIN regions r ON a.region_id=r.id
                          WHERE a.is_verified=1 ORDER BY a.experience_years DESC LIMIT 4");
?>

<!-- HERO -->
<section class="hero">
  <div class="container hero-content">
    <span class="hero-tag">◆ Preserving India's Living Heritage Since Generations</span>
    <h1>Discover the Hands Behind <em>India's Timeless Crafts</em></h1>
    <p>Connect directly with verified traditional artisans from every corner of India — from the looms of Kanchipuram to the kilns of Jaipur. No middlemen. Only authenticity, fair trade, and living culture.</p>
    <div class="hero-btns">
      <a href="artisans.php" class="btn btn-primary">Explore Artisans →</a>
      <a href="crafts.php" class="btn btn-outline">Browse All Crafts</a>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats-strip">
  <div class="container stats-grid">
    <div><h3><?= $totalArtisans ?>+</h3><p>Verified Artisans</p></div>
    <div><h3><?= $totalCrafts ?>+</h3><p>Living Crafts</p></div>
    <div><h3><?= $totalRegions ?></h3><p>States Covered</p></div>
    <div><h3><?= $totalVerified ?></h3><p>Verified Profiles</p></div>
  </div>
</section>

<!-- FEATURED CRAFTS -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Featured Crafts</span>
      <h2>Explore India's Craft Traditions</h2>
      <p>From the ancient lost-wax casting of Bankura to the intricate tie-dye of Kutch — witness centuries of artistry passed down through generations.</p>
    </div>
    <div class="grid grid-3">
      <?php while($c = $crafts->fetch_assoc()): ?>
      <div class="card craft-card">
        <div class="card-img craft-gradient-<?= $c['id'] % 6 ?>">
          <span class="craft-badge"><?= htmlspecialchars($c['category_name'] ?? 'Craft') ?></span>
        </div>
        <div class="card-body">
          <h3><?= htmlspecialchars($c['craft_name']) ?></h3>
          <p class="region">📍 <?= htmlspecialchars($c['state_name'] ?? 'India') ?></p>
          <p><?= htmlspecialchars(substr($c['description'],0,110)) ?>...</p>
          <a href="artisans.php?craft=<?= $c['id'] ?>" class="link-arrow">View Artisans →</a>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <div class="center-btn"><a href="crafts.php" class="btn btn-ghost">View Complete Craft Catalogue →</a></div>
  </div>
</section>

<!-- FEATURED ARTISANS -->
<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Meet the Masters</span>
      <h2>Featured Artisans</h2>
      <p>Real craftspeople, verified profiles, direct contact. Support livelihoods, preserve heritage.</p>
    </div>
    <div class="grid grid-4">
      <?php while($a = $artisans->fetch_assoc()): ?>
      <div class="card artisan-card">
        <div class="artisan-avatar">
          <?= strtoupper(substr($a['name'],0,1)) ?>
          <?php if($a['is_verified']): ?><span class="verified-dot" title="Verified">✔</span><?php endif; ?>
        </div>
        <h4><?= htmlspecialchars($a['name']) ?></h4>
        <span class="craft-tag"><?= htmlspecialchars($a['craft_name']) ?></span>
        <p class="region">📍 <?= htmlspecialchars($a['state_name']) ?> · <?= $a['experience_years'] ?> yrs</p>
        <a href="artisan-profile.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-primary">View Profile</a>
      </div>
      <?php endwhile; ?>
    </div>
    <div class="center-btn"><a href="artisans.php" class="btn btn-primary">Browse Complete Directory →</a></div>
  </div>
</section>

<!-- WHY THIS MATTERS -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Our Purpose</span>
      <h2>Why the Craft Directory Matters</h2>
      <p>Traditional artisans face limited digital presence and heavy dependence on middlemen. We fix that.</p>
    </div>
    <div class="values-grid">
      <div class="value-box">
        <div class="v-icon">◆</div>
        <h3>Direct Connection</h3>
        <p>Artisans reach genuine buyers, researchers, and institutions — without intermediaries reducing their earnings.</p>
      </div>
      <div class="value-box">
        <div class="v-icon">✔</div>
        <h3>Verified Profiles</h3>
        <p>Every artisan profile is manually reviewed. Authentic information you can trust for ethical sourcing.</p>
      </div>
      <div class="value-box">
        <div class="v-icon">★</div>
        <h3>Cultural Preservation</h3>
        <p>Centuries-old craft techniques documented digitally, ensuring they survive for future generations.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-band">
  <div class="container cta-inner">
    <h2>Empower Artisans. Preserve Culture.</h2>
    <p>Every direct connection supports a family and keeps a craft alive.</p>
    <a href="contact.php" class="btn btn-light">Get in Touch →</a>
  </div>
</section>

<?php include 'includes/footer.php'; ?>