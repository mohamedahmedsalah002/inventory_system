<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Inventory</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="items.php">Items</a></li>
        <li class="nav-item"><a class="nav-link" href="suppliers.php">Suppliers</a></li>
        <li class="nav-item"><a class="nav-link" href="issue.php">Issue Stock</a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
