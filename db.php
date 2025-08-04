<?php
$conn = new mysqli("localhost", "root", "", "cafe_aroma");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
