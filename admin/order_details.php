<?php
// order_details.php?id=ORDER_ID

session_start();
include '../db.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Order ID not specified.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $orderId = $_GET['id']; // Make sure you pass ?id= to this page

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();

    // Redirect to refresh the page and show updated status
    
    header("Location: order_details.php?id=" . $orderId . "&updated=1");
    exit;
}

if (isset($_GET['updated'])) {
    echo '<div class="alert alert-success">Order status updated successfully.</div>';
}


$order_id = $_GET['id'];

// Fetch order and user
$stmt = $conn->prepare("
    SELECT o.id, o.created_at, o.status, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch ordered items
$stmt = $conn->prepare("
    SELECT p.name, p.price, oi.quantity, (p.price * oi.quantity) AS total
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

$total_order_price = 0;

?>

<?php
function getStatusBadge($status) {
    switch (strtolower($status)) {
        case 'pending': return '<span class="badge badge-warning">Pending</span>';
        case 'processing': return '<span class="badge badge-info">Processing</span>';
        case 'completed': return '<span class="badge badge-success">Completed</span>';
        case 'cancelled': return '<span class="badge badge-danger">Cancelled</span>';
        default: return '<span class="badge badge-secondary">'.htmlspecialchars($status).'</span>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order #<?= htmlspecialchars($order['id']) ?> Details</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'partials/navbar.php'; ?>
    <?php include 'partials/sidebar.php'; ?>

    <div class="content-wrapper p-4">
        <h2>Order Details - #<?= htmlspecialchars($order['id']) ?></h2>
        <form method="post">
        <div class="form-group">
            <label for="status">Update Order Status:</label>
            <select name="status" id="status" class="form-control" required>
            <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
            <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" name="update_status" class="btn btn-primary mt-2">Update Status</button>
        </form>
        
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?> (<?= htmlspecialchars($order['email']) ?>)</p>
                <p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
                <p><strong>Status:</strong> <?= getStatusBadge($order['status']) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">Ordered Products</div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items_result->fetch_assoc()): 
                            $total_order_price += $item['total'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($item['total'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total:</th>
                            <th>₱<?= number_format($total_order_price, 2) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <a href="orders.php" class="btn btn-secondary mt-3">Back to Orders</a>
    </div>
</div>

<!-- Scripts -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
