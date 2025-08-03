<?php
include '../db.php';

$filter = $_GET['filter'] ?? 'all';

if ($filter === 'pending') {
    $stmt = $conn->prepare("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE orders.status = 'Pending' ORDER BY created_at DESC");
} elseif ($filter === 'completed') {
    $stmt = $conn->prepare("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE orders.status = 'Completed' ORDER BY created_at DESC");
} else {
    $stmt = $conn->prepare("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id ORDER BY created_at DESC");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<link rel="stylesheet" href="dist/css/adminlte.min.css">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

<section class="content-wrapper p-4">
  <div class="container-fluid">
    <h2>Orders</h2>
    
    <div class="mb-3">
      <a href="orders.php?filter=pending" class="btn btn-warning">Pending</a>
      <a href="orders.php?filter=completed" class="btn btn-success">Completed</a>
      <a href="orders.php" class="btn btn-secondary">All Orders</a>
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td>₱<?= number_format($row['total'], 2) ?></td>
          <td><?= $row['status'] ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="order_details.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
            <a href="order_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</a>

            <?php if ($row['status'] === 'Pending'): ?>
              <form method="POST" action="update_order_status.php" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                <button type="submit" name="complete" class="btn btn-sm btn-success">Mark as Completed</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>

<?php include 'partials/footer.php'; ?>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
