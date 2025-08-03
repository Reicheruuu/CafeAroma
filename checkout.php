<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Make sure cart is not empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}

// Fetch logged-in user ID
$username = $_SESSION['username'];
$userQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}

$user_id = $user['id'];

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert into orders
$orderQuery = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$orderQuery->bind_param("id", $user_id, $total);
$orderQuery->execute();
$order_id = $orderQuery->insert_id;

// Optional: Insert order items (if you want to track them per order)
// You need to create an `order_items` table if not yet created.

foreach ($_SESSION['cart'] as $product_id => $item) {
    $itemQuery = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $itemQuery->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
    $itemQuery->execute();
}

// Clear the cart
$_SESSION['cart'] = [];

header("Location: thankyou.php");
exit;
?>
