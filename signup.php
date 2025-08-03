<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $password);

  if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit;
  } else {
    $error = "Registration failed.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register - Café Aroma</title>
  <link rel="stylesheet" href="css/login.css"> 
</head>
<body>
  <form method="POST" class="form">
    <p id="heading">Register</p>

    <div class="field">
      <svg class="input-icon" viewBox="0 0 20 20">
        <path d="M14 7a4 4 0 1 0-8 0 4 4 0 0 0 8 0ZM2 17a8 8 0 1 1 16 0H2Z"></path>
      </svg>
      <input type="username" name="username" class="input-field" placeholder="Username" required>
    </div>

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
      <button class="button1" type="submit">Register</button>
      <button class="button2" type="button" onclick="window.location.href='login.php'">Already have an account?</button>
    </div>


    <?php if (isset($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
  </form>
</body>
</html>

