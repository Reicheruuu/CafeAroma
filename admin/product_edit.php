<?php
include '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: products.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $imageName = $product['image'];

    if ($_FILES['image']['name']) {
        // Remove old image if it exists
        if ($product['image'] && file_exists("../images/" . $product['image'])) {
            unlink("../images/" . $product['image']);
        }

        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = "../images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $imageName, $id);
    $stmt->execute();

    header("Location: products.php");
    exit;
}
?>
<?php include 'partials/navbar.php'; ?>
<?php include 'partials/sidebar.php'; ?>

<section class="content-wrapper p-4">
  <div class="container-fluid">
    <h3>Edit Product</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Product Name</label>
        <input name="name" type="text" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
      </div>
      <div class="form-group">
        <label>Price (₱)</label>
        <input name="price" type="number" step="0.01" class="form-control" value="<?= $product['price'] ?>" required>
      </div>
      <div class="form-group">
        <label>Current Image</label><br>
        <?php if ($product['image']): ?>
            <img src="../images/<?= $product['image'] ?>" style="width: 100px;">
        <?php endif; ?>
        <input name="image" type="file" class="form-control-file mt-2">
      </div>
      <button type="submit" class="btn btn-success">Update Product</button>
      <a href="products.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</section>

<?php include 'footer.php'; ?>
