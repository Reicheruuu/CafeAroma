<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    $imageName = '';
    if ($_FILES['image']['name']) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = "../images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $imageName);
    $stmt->execute();

    header("Location: products.php");
    exit;
}
?>
<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

<section class="content-wrapper p-4">
  <div class="container-fluid">
    <h3>Add New Product</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Product Name</label>
        <input name="name" type="text" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label>Price (₱)</label>
        <input name="price" type="number" step="0.01" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Image</label>
        <input name="image" type="file" class="form-control-file" required>
      </div>
      <button type="submit" class="btn btn-primary">Add Product</button>
      <a href="products.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</section>

<?php include 'partials/footer.php'; ?>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
