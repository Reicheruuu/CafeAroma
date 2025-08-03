<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM products WHERE id = $id");
}

header("Location: products.php");
exit;
