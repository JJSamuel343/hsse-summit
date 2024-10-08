<?php
session_start();

// Define a simple hardcoded admin username and password (plaintext check)
$admin_username = "hssepartners2024";
$admin_password = "showmethemoney"; // Change this to whatever you like

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the submitted username and password from the form
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if the submitted credentials match the hardcoded ones
  if ($username === $admin_username && $password === $admin_password) {
    // Set session variable to indicate successful login
    $_SESSION['authenticated'] = true;
    // Redirect to the attendance page
    header('Location: attendance_view.php');
    exit();
  } else {
    // If credentials are incorrect, set an error message
    $error_message = "Invalid username or password!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <h2>Admin Login</h2>

  <?php
  // Display error message if login failed
  if (isset($error_message)) {
    echo "<p style='color:red;'>$error_message</p>";
  }
  ?>

  <form method="POST" action="login.php">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>
</body>

</html>