<?php include 'header.php'; ?>
<?php
// Add item
if (isset($_POST['add_item'])) {
    $stmt = $pdo->prepare("INSERT INTO items (name, description, quantity, unit_price, min_quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['description'], (int)$_POST['quantity'], (float)$_POST['unit_price'], (int)$_POST['min_quantity']]);
    echo '<div class="alert alert-success">Item added.</div>';
}

// Update item
if (isset($_POST['update_item'])) {
    $stmt = $pdo->prepare("UPDATE items SET name=?, description=?, quantity=?, unit_price=?, min_quantity=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['description'], (int)$_POST['quantity'], (float)$_POST['unit_price'], (int)$_POST['min_quantity'], (int)$_POST['id']]);
    echo '<div class="alert alert-success">Item updated.</div>';
}

// Delete
if (isset($_GET['delete'])) {
    $pdo->prepare('DELETE FROM items WHERE id=?')->execute([(int)$_GET['delete']]);
    header('Location: items.php');
    exit;
}

$items = $pdo->query('SELECT * FROM items ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

// For editing
$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM items WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="row">
  <div class="col-md-6">
    <h3><?php echo $editItem ? 'Edit Item' : 'Add Item'; ?></h3>
    <form method="POST">
      <?php if ($editItem): ?>
        <input type="hidden" name="id" value="<?php echo (int)$editItem['id']; ?>">
      <?php endif; ?>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" required value="<?php echo $editItem ? htmlspecialchars($editItem['name']) : ''; ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <input name="description" class="form-control" value="<?php echo $editItem ? htmlspecialchars($editItem['description']) : ''; ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input name="quantity" type="number" class="form-control" value="<?php echo $editItem ? (int)$editItem['quantity'] : 0; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Unit Price</label>
        <input name="unit_price" type="number" step="0.01" class="form-control" value="<?php echo $editItem ? number_format($editItem['unit_price'],2) : '0.00'; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Minimum Quantity</label>
        <input name="min_quantity" type="number" class="form-control" value="<?php echo $editItem ? (int)$editItem['min_quantity'] : 5; ?>" required>
      </div>
      <?php if ($editItem): ?>
        <button class="btn btn-primary" name="update_item" type="submit">Save Changes</button>
        <a class="btn btn-secondary" href="items.php">Cancel</a>
      <?php else: ?>
        <button class="btn btn-success" name="add_item" type="submit">Add Item</button>
      <?php endif; ?>
    </form>
  </div>

  <div class="col-md-6">
    <h3>All Items</h3>
    <table class="table table-striped">
      <thead><tr><th>#</th><th>Name</th><th>Qty</th><th>Min</th><th>Price</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?php echo (int)$it['id']; ?></td>
            <td><?php echo htmlspecialchars($it['name']); ?></td>
            <td><?php echo (int)$it['quantity']; ?></td>
            <td><?php echo (int)$it['min_quantity']; ?></td>
            <td><?php echo number_format($it['unit_price'],2); ?></td>
            <td>
              <a class="btn btn-sm btn-primary" href="items.php?edit=<?php echo (int)$it['id']; ?>">Edit</a>
              <a class="btn btn-sm btn-danger" href="items.php?delete=<?php echo (int)$it['id']; ?>" onclick="return confirm('Delete this item?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>
