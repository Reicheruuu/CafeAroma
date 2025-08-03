<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM orders WHERE id = $id");
}

header("Location: orders.php");
exit;
