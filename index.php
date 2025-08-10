<?php include 'header.php'; ?>

<?php
$stmt = $pdo->query("SELECT * FROM items WHERE quantity <= min_quantity ORDER BY id DESC");
$low = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalItems = $pdo->query('SELECT COUNT(*) FROM items')->fetchColumn();
$totalSuppliers = $pdo->query('SELECT COUNT(*) FROM suppliers')->fetchColumn();
$totalIssues = $pdo->query('SELECT COUNT(*) FROM stock_issues')->fetchColumn();
?>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Items</h5>
        <p class="card-text display-6"><?php echo (int)$totalItems; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Suppliers</h5>
        <p class="card-text display-6"><?php echo (int)$totalSuppliers; ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-bg-light mb-3">
      <div class="card-body">
        <h5 class="card-title">Stock Issues</h5>
        <p class="card-text display-6"><?php echo (int)$totalIssues; ?></p>
      </div>
    </div>
  </div>
</div>

<h3>Low Stock Alerts</h3>
<?php if (count($low) === 0): ?>
  <div class="alert alert-success">No low-stock items.</div>
<?php else: ?>
  <div class="list-group mb-4">
    <?php foreach ($low as $it): ?>
      <a class="list-group-item list-group-item-danger">
        <?php echo htmlspecialchars($it['name']); ?> — <?php echo (int)$it['quantity']; ?> left (min <?php echo (int)$it['min_quantity']; ?>)
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
