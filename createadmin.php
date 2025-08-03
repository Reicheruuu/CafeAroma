<?php
include 'db.php'; // this should connect to your database

$username = 'admin';
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT); // secure password
$is_admin = 1;

$stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $username, $email, $password, $is_admin);

if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . $stmt->error;
}
?>
