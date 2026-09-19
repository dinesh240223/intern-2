<?php
include 'includes/db.php';
include 'includes/header.php';

$crafts = $conn->query("SELECT c.*, cat.category_name, r.state_name
                        FROM crafts c
                        LEFT JOIN categories cat ON c.category_id=cat.id
                        LEFT JOIN regions r ON c.region_id=r.id
                        ORDER BY c.craft_name");
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Craft Catalogue</span>
    <h1>Traditional Crafts of India</h1>
    <p>Explore the diverse craft traditions that have shaped India's cultural identity for centuries — each carrying the soul of a region and the skill of generations.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="filter-bar">
      <input type="text" id="craftSearch" placeholder="🔍 Search a craft by name...">
      <select id="categoryFilter">
        <option value="">All Categories</option>
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while($cat = $cats->fetch_assoc()){
          echo "<option value='".htmlspecialchars($cat['category_name'])."'>".htmlspecialchars($cat['category_name'])."</option>";
        }
        ?>
      </select>
    </div>
    <p class="result-count" id="craftCount"></p>
    <div class="grid grid-3" id="craftGrid">
      <?php while($c = $crafts->fetch_assoc()): ?>
      <div class="card craft-card" data-category="<?= htmlspecialchars($c['category_name'] ?? '') ?>" data-name="<?= strtolower(htmlspecialchars($c['craft_name'])) ?>">
        <div class="card-img craft-gradient-<?= $c['id'] % 6 ?>">
          <span class="craft-badge"><?= htmlspecialchars($c['category_name'] ?? 'Craft') ?></span>
        </div>
        <div class="card-body">
          <h3><?= htmlspecialchars($c['craft_name']) ?></h3>
          <p class="region">📍 <?= htmlspecialchars($c['state_name'] ?? 'India') ?></p>
          <p><?= htmlspecialchars(substr($c['description'],0,130)) ?>...</p>
          <a href="artisans.php?craft=<?= $c['id'] ?>" class="link-arrow">View Artisans →</a>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>