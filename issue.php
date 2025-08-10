<?php include 'header.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = (int)$_POST['item_id'];
    $qty = (int)$_POST['quantity'];
    if ($qty <= 0) { echo '<div class="alert alert-danger">Invalid quantity.</div>'; }
    else {
        $row = $pdo->prepare('SELECT quantity FROM items WHERE id=?');
        $row->execute([$item_id]);
        $it = $row->fetch(PDO::FETCH_ASSOC);
        if (!$it) { echo '<div class="alert alert-danger">Item not found.</div>'; }
        elseif ($it['quantity'] < $qty) { echo '<div class="alert alert-danger">Not enough stock.</div>'; }
        else {
            $pdo->prepare('INSERT INTO stock_issues (item_id, quantity, issued_to, issue_type, date) VALUES (?, ?, ?, ?, CURDATE())')
                ->execute([$item_id, $qty, $_POST['issued_to'], $_POST['issue_type']]);
            $pdo->prepare('UPDATE items SET quantity = quantity - ? WHERE id = ?')->execute([$qty, $item_id]);
            echo '<div class="alert alert-success">Issued successfully.</div>';
        }
    }
}
$items = $pdo->query('SELECT * FROM items ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>
<h3>Issue Stock</h3>
<form method="POST">
  <div class="mb-3">
    <label class="form-label">Item</label>
    <select name="item_id" class="form-select" required>
      <?php foreach ($items as $it): ?>
        <option value="<?php echo (int)$it['id']; ?>"><?php echo htmlspecialchars($it['name']); ?> (<?php echo (int)$it['quantity']; ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3"><label class="form-label">Quantity</label><input name="quantity" type="number" class="form-control" value="1" required></div>
  <div class="mb-3"><label class="form-label">Issued To</label><input name="issued_to" class="form-control" required></div>
  <div class="mb-3">
    <label class="form-label">Type</label>
    <select name="issue_type" class="form-select">
      <option value="doctor">Doctor</option>
      <option value="session">Session</option>
    </select>
  </div>
  <button class="btn btn-primary" type="submit">Issue</button>
</form>
<?php include 'footer.php'; ?>
