<?php
include 'includes/db.php';
include 'includes/header.php';

$where = ["1=1"];
$craftFilter = $_GET['craft'] ?? '';
$stateFilter = $_GET['state'] ?? '';
$search      = $_GET['q'] ?? '';

if ($craftFilter) $where[] = "a.craft_id = ".intval($craftFilter);
if ($stateFilter) $where[] = "a.region_id = ".intval($stateFilter);
if ($search)      $where[] = "a.name LIKE '%".$conn->real_escape_string($search)."%'";

$whereSQL = implode(' AND ', $where);

$sql = "SELECT a.*, c.craft_name, r.state_name
        FROM artisans a
        LEFT JOIN crafts c ON a.craft_id=c.id
        LEFT JOIN regions r ON a.region_id=r.id
        WHERE $whereSQL ORDER BY a.is_verified DESC, a.experience_years DESC";

$artisans = $conn->query($sql);
$craftsList = $conn->query("SELECT * FROM crafts ORDER BY craft_name");
$regionsList = $conn->query("SELECT * FROM regions ORDER BY state_name");
?>

<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Artisan Directory</span>
    <h1>Meet Our Master Artisans</h1>
    <p>Directly connect with verified craftspeople from every state — the true custodians of India's living heritage.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <form class="filter-bar" method="GET">
      <input type="text" name="q" placeholder="🔍 Search by artisan name..." value="<?= htmlspecialchars($search) ?>">
      <select name="craft">
        <option value="">All Crafts</option>
        <?php while($c=$craftsList->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" <?= $craftFilter==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['craft_name']) ?></option>
        <?php endwhile; ?>
      </select>
      <select name="state">
        <option value="">All States</option>
        <?php while($r=$regionsList->fetch_assoc()): ?>
        <option value="<?= $r['id'] ?>" <?= $stateFilter==$r['id']?'selected':'' ?>><?= htmlspecialchars($r['state_name']) ?></option>
        <?php endwhile; ?>
      </select>
      <button class="btn btn-primary">Apply Filters</button>
      <a href="artisans.php" class="btn btn-ghost">Reset</a>
    </form>

    <p class="result-count">✦ <?= $artisans->num_rows ?> artisan(s) found matching your criteria</p>

    <div class="grid grid-4">
      <?php if($artisans->num_rows === 0): ?>
        <div style="grid-column:1/-1;text-align:center;padding:60px 20px;">
          <h3 style="font-family:var(--font-display);font-size:26px;color:var(--indigo);margin-bottom:12px;">No artisans found</h3>
          <p style="color:var(--muted);">Try adjusting your filters or search terms.</p>
        </div>
      <?php else: while($a = $artisans->fetch_assoc()): ?>
      <div class="card artisan-card">
        <div class="artisan-avatar">
          <?= strtoupper(substr($a['name'],0,1)) ?>
          <?php if($a['is_verified']): ?><span class="verified-dot">✔</span><?php endif; ?>
        </div>
        <h4><?= htmlspecialchars($a['name']) ?></h4>
        <span class="craft-tag"><?= htmlspecialchars($a['craft_name']) ?></span>
        <p class="region">📍 <?= htmlspecialchars($a['state_name']) ?> · <?= $a['experience_years'] ?> yrs</p>
        <a href="artisan-profile.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-primary">View Profile</a>
      </div>
      <?php endwhile; endif; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>