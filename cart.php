<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                "name" => $product['name'],
                "price" => $product['price'],
                "image" => $product['image'],
                "quantity" => 1
            ];
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $id = intval($_POST['id']);
        $action = $_POST['update_quantity'];

        if (isset($_SESSION['cart'][$id])) {
            if ($action == 'increase') {
                $_SESSION['cart'][$id]['quantity']++;
            } elseif ($action == 'decrease') {
                $_SESSION['cart'][$id]['quantity']--;
                if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }
    }

    if (isset($_POST['remove'])) {
        $id = intval($_POST['id']);
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
    <body>
        <div class="cart-container">
            <h1>Your Shopping Cart</h1>
            <a href="index.php">← Continue Shopping</a>

            <?php if (!empty($_SESSION['cart'])): ?>
            <?php $total = 0; ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <?php $total += $item['price'] * $item['quantity']; ?>
                    <li class="cart-item">
                        <img src="images/<?= $item['image'] ?>">
                        <div class="cart-details">
                            <?= $item['name'] ?> - $<?= $item['price'] ?> × <?= $item['quantity'] ?>
                        </div>
                        <div class="cart-actions">
                            <form method="post" action="cart.php">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit" name="update_quantity" value="decrease">−</button>
                                <button type="submit" name="update_quantity" value="increase">+</button>
                                <button type="submit" name="remove" value="1">🗑</button>

                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="cart-total">Total: $<?= number_format($total, 2) ?></p>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            <?php else: ?>
            <p>Your cart is empty.</p>
            <?php endif; ?>

        </div>
    </body>
</html>
