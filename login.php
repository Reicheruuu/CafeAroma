<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['is_admin'] = $user['is_admin'] == 1;

      // Redirect based on admin status
      if ($_SESSION['is_admin']) {
        header("Location: admin/dashboard.php");
      } else {
        header("Location: index.php");
      }
      exit;
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "User not found.";
  }
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Login - Café Aroma</title>
  <link rel="stylesheet" href="css/login.css"> <!-- Add this file -->
</head>
<body>
  <form method="POST" class="form">
    <p id="heading">Login</p>

    <div class="field">
      <svg class="input-icon" viewBox="0 0 20 20">
        <path d="M14 7a4 4 0 1 0-8 0 4 4 0 0 0 8 0ZM2 17a8 8 0 1 1 16 0H2Z"></path>
      </svg>
      <input type="email" name="email" class="input-field" placeholder="Email" required>
    </div>

    <div class="field">
      <svg class="input-icon" viewBox="0 0 20 20">
        <path d="M10 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"></path>
        <path fill-rule="evenodd" d="M.458 11.042A10 10 0 0 1 18.94 12.31a1.001 1.001 0 0 1 .043.997A10 10 0 0 1 .458 11.042Zm13.02-2.308a4 4 0 1 1-6.956 0 8 8 0 0 0-4.662 4.56 8 8 0 0 0 16.28 0 8 8 0 0 0-4.662-4.56Z" clip-rule="evenodd"></path>
      </svg>
      <input type="password" name="password" class="input-field" placeholder="Password" required>
    </div>

    <div class="btn">
      <button class="button1" type="submit">Login</button>
      <button class="button2" type="button" onclick="window.location.href='signup.php'">Sign Up</button>
    </div>

    <?php if (isset($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
  </form>
</body>
</html>
