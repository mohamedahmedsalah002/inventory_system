<?php include 'header.php'; ?>
<?php
// Add supplier
if (isset($_POST['add_supplier'])) {
    $pdo->prepare('INSERT INTO suppliers (name, contact_info) VALUES (?, ?)')->execute([$_POST['name'], $_POST['contact']]);
    echo '<div class="alert alert-success">Supplier added.</div>';
}

// Purchases (simple add purchase and increase stock)
if (isset($_POST['add_purchase'])) {
    $supplier_id = (int)$_POST['supplier_id'];
    $date = $_POST['date'] ?: date('Y-m-d');
    $total = 0.0;
    // create purchase
    $pdo->prepare('INSERT INTO purchases (supplier_id, date, total_amount) VALUES (?, ?, ?)')->execute([$supplier_id, $date, $total]);
    $purchase_id = $pdo->lastInsertId();
    // items array: item_id[], qty[], price[]
    foreach ($_POST['item_id'] as $i => $item_id) {
        $qty = (int)$_POST['qty'][$i];
        $price = (float)$_POST['price'][$i];
        if ($qty <= 0) continue;
        $pdo->prepare('INSERT INTO purchase_items (purchase_id, item_id, quantity, unit_price) VALUES (?, ?, ?, ?)')->execute([$purchase_id, $item_id, $qty, $price]);
        $pdo->prepare('UPDATE items SET quantity = quantity + ? WHERE id = ?')->execute([$qty, $item_id]);
        $total += $qty * $price;
    }
    // update total
    $pdo->prepare('UPDATE purchases SET total_amount = ? WHERE id = ?')->execute([$total, $purchase_id]);
    echo '<div class="alert alert-success">Purchase recorded.</div>';
}

$suppliers = $pdo->query('SELECT * FROM suppliers ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$items = $pdo->query('SELECT * FROM items ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
  <div class="col-md-5">
    <h3>Add Supplier</h3>
    <form method="POST">
      <div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Contact</label><input name="contact" class="form-control"></div>
      <button class="btn btn-success" name="add_supplier" type="submit">Add Supplier</button>
    </form>

    <hr>
    <h3>Record Purchase</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-select" required>
          <?php foreach ($suppliers as $s): ?>
            <option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3"><label class="form-label">Date</label><input name="date" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"></div>

      <div id="purchase-items">
        <div class="row mb-2 align-items-end">
          <div class="col">
            <label class="form-label">Item</label>
            <select name="item_id[]" class="form-select">
              <?php foreach($items as $it): ?>
                <option value="<?php echo (int)$it['id']; ?>"><?php echo htmlspecialchars($it['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col">
            <label class="form-label">Qty</label>
            <input name="qty[]" type="number" class="form-control" value="1">
          </div>
          <div class="col">
            <label class="form-label">Price</label>
            <input name="price[]" type="number" step="0.01" class="form-control" value="0.00">
          </div>
        </div>
      </div>

      <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addRow()">Add Row</button>
      <button class="btn btn-primary" name="add_purchase" type="submit">Save Purchase</button>
    </form>
  </div>

  <div class="col-md-7">
    <h3>Suppliers</h3>
    <table class="table table-striped">
      <thead><tr><th>#</th><th>Name</th><th>Contact</th></tr></thead>
      <tbody>
        <?php foreach ($suppliers as $s): ?>
          <tr><td><?php echo (int)$s['id']; ?></td><td><?php echo htmlspecialchars($s['name']); ?></td><td><?php echo htmlspecialchars($s['contact_info']); ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function addRow(){
  const container = document.getElementById('purchase-items');
  const row = document.createElement('div');
  row.className = 'row mb-2 align-items-end';
  row.innerHTML = `
    <div class="col">
      <label class="form-label">Item</label>
      <select name="item_id[]" class="form-select"><?php foreach($items as $it): ?><option value="<?php echo (int)$it['id']; ?>"><?php echo htmlspecialchars($it['name']); ?></option><?php endforeach; ?></select>
    </div>
    <div class="col">
      <label class="form-label">Qty</label>
      <input name="qty[]" type="number" class="form-control" value="1">
    </div>
    <div class="col">
      <label class="form-label">Price</label>
      <input name="price[]" type="number" step="0.01" class="form-control" value="0.00">
    </div>`;
  container.appendChild(row);
}
</script>

<?php include 'footer.php'; ?>
