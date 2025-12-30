<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "denica");

if (!$conn) {
    die("Database connection failed");
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['login_error'] = "Email and password are required.";
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT CustomerID, Name, PasswordHash FROM customer WHERE Email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['PasswordHash'])) {

        // Store session data
        $_SESSION['customer_id'] = $user['CustomerID'];
        $_SESSION['customer_name'] = $user['Name'];

        // Redirect to homepage
        header("Location: index.php");
        exit;
    }
}

$_SESSION['login_error'] = "Invalid email or password.";
header("Location: login.php");
exit;

