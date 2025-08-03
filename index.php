<?php session_start(); ?>
<?php include 'db.php'; ?>
<?php $result = $conn->query("SELECT * FROM products"); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Café Aroma</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>☕ Café Aroma</h1>
    <nav>
      <?php if (isset($_SESSION['username'])): ?>
        <span>Welcome, <?= $_SESSION['username'] ?></span>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>

  <section class="hero">
    <h2>Freshly Brewed Happiness</h2>
    <p>Explore our handcrafted coffee and delicious pastries</p>
    

  </section>

  <section class="products">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
        <h3><?php echo $row['name']; ?></h3>
        <p>$<?php echo $row['price']; ?></p>
        <a href="cart.php?action=add&id=<?php echo $row['id']; ?>" class="btn">Add to Cart</a>
      </div>
    <?php endwhile; ?>
  </section>
</body>
</html>


