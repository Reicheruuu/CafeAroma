<?php
session_start();
include '../db.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Count of Pending Orders
$pendingStmt = $conn->query("SELECT COUNT(*) AS total_pending FROM orders WHERE status = 'Pending'");
$pendingCount = $pendingStmt->fetch_assoc()['total_pending'];

// Total Sales from Completed Orders
$salesStmt = $conn->query("SELECT SUM(total) AS total_sales FROM orders WHERE status = 'Completed'");
$totalSales = $salesStmt->fetch_assoc()['total_sales'] ?? 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Café Aroma</title>

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">


</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="#" class="nav-link">Admin Dashboard</a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light">Café Aroma</span>
    </a>
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- Dashboard -->
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p> Dashboard </p>
            </a>
          </li>
          <!-- Users -->
          <li class="nav-item">
            <a href="users.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>   
          <!-- Orders -->
          <li class="nav-item">
            <a href="orders.php" class="nav-link">
              <i class="nav-icon fas fa-receipt"></i>
              <p>Orders</p>
            </a>
          </li>

          <!-- Products -->
          <li class="nav-item">
            <a href="products.php" class="nav-link">
              <i class="nav-icon fas fa-coffee"></i>
              <p>Products</p>
            </a>
          </li>

          <!-- Logout -->
          <li class="nav-item">
            <a href="../logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>  
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <h1>Welcome, Cafe Aroma Admin!</h1>
    </div>
    <div class="content">
      <!-- Main content here -->
      <p><h3>Total Sales</h3></p>
      <div class="row">
      <!-- Pending Orders Card -->
      <div class="col-lg-6 col-12">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?= $pendingCount ?></h3>
            <p>Pending Orders</p>
          </div>
          <div class="icon">
            <i class="fas fa-clock"></i>
          </div>
          <a href="orders.php?filter=pending" class="small-box-footer">View Pending Orders <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Total Sales Card -->
      <div class="col-lg-6 col-12">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>$<?= number_format($totalSales, 2) ?></h3>
            <p>Total Sales (Completed)</p>
          </div>
          <div class="icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <a href="orders.php?filter=completed" class="small-box-footer">View Completed Orders <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
