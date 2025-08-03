<?php include '../db.php';?>
<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>


<section class="content-wrapper p-4">
  <div class="container-fluid">
    <h3>Products</h3>
    <a href="product_add.php" class="btn btn-success mb-3">➕ Add Product</a>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price (₱)</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php   
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><img src="../images/<?= $row['image'] ?>" width="50"></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>₱<?= number_format($row['price'], 2) ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td>
            <a href="product_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="product_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
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
