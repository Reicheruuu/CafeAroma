<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header("Location: ../login.php");
  exit;
}

include '../db.php';

$id = $_GET['id'] ?? null;

if ($id) {
  // Optional: prevent deleting own admin account
  if ($_SESSION['user_id'] == $id) {
    header("Location: users.php?error=You can't delete your own account");
    exit;
  }

  $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: users.php");
exit;
