<?php include 'header.php'; ?>
<h3>Reports</h3>
<p><a class="btn btn-sm btn-secondary" href="index.php">← Dashboard</a></p>

<h5 class="mt-3">Stock Issues</h5>
<table class="table table-bordered">
<thead><tr><th>Date</th><th>Item</th><th>Qty</th><th>Issued To</th><th>Type</th></tr></thead>
<tbody>
<?php
$stmt = $pdo->query("SELECT s.date, i.name, s.quantity, s.issued_to, s.issue_type FROM stock_issues s JOIN items i ON s.item_id = i.id ORDER BY s.date DESC, s.id DESC");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr><td>'.htmlspecialchars($r['date']).'</td><td>'.htmlspecialchars($r['name']).'</td><td>'.(int)$r['quantity'].'</td><td>'.htmlspecialchars($r['issued_to']).'</td><td>'.htmlspecialchars($r['issue_type']).'</td></tr>';
}
?>
</tbody>
</table>

<h5 class="mt-4">Current Stock</h5>
<table class="table table-striped">
<thead><tr><th>#</th><th>Item</th><th>Qty</th><th>Min</th></tr></thead>
<tbody>
<?php
$itms = $pdo->query('SELECT id, name, quantity, min_quantity FROM items ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
foreach ($itms as $it) {
    echo '<tr><td>'.(int)$it['id'].'</td><td>'.htmlspecialchars($it['name']).'</td><td>'.(int)$it['quantity'].'</td><td>'.(int)$it['min_quantity'].'</td></tr>';
}
?>
</tbody>
</table>

<?php include 'footer.php'; ?>
