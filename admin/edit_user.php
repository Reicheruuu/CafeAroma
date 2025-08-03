<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header("Location: ../login.php");
  exit;
}

include '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: users.php");
  exit;
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $hashed, $id);
  } else {
    $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $email, $id);
  }

  if ($stmt->execute()) {
    header("Location: users.php");
    exit;
  } else {
    $error = "Failed to update user.";
  }
}

// Load existing user
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include 'partials/navbar.php'; ?>
  <?php include 'partials/sidebar.php'; ?>

  <div class="content-wrapper p-4">
    <div class="container-fluid">
      <h2>Edit User</h2>
      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
        </div>
        <div class="form-group">
          <label>New Password (optional)</label>
          <input type="password" name="password" class="form-control">
        </div>
        <button class="btn btn-primary">Save Changes</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>

  <?php include 'partials/footer.php'; ?>
</div>
</body>
</html>
