<?php
session_start();

$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'denicaData';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
if (!$conn) {
  $_SESSION['login_error'] = "Database connection failed.";
  header("Location: login.php");
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  $_SESSION['login_error'] = "Please enter email and password.";
  header("Location: login.php");
  exit;
}

/* Prepared statement prevents SQL injection */
$stmt = $conn->prepare("SELECT CustomerID, Name, Email, PasswordHash FROM customer WHERE Email = ? LIMIT 1");
if (!$stmt) {
  $_SESSION['login_error'] = "Server error. Please try again.";
  header("Location: login.php");
  exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
  $user = $result->fetch_assoc();

  /* Verify password hash */
  if (password_verify($password, $user['PasswordHash'])) {

    $_SESSION['customer_id'] = $user['CustomerID'];
    $_SESSION['customer_name'] = $user['Name'];

    $stmt->close();
    mysqli_close($conn);

    header("Location: index.php");   // go back to homepage
    exit;
  }
}

/* If email not found OR password wrong */
$_SESSION['login_error'] = "Invalid email or password.";
$stmt->close();
mysqli_close($conn);

header("Location: login.php");
exit;




