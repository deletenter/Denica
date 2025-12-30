<?php
$databaseHost = 'localhost';
$databaseName = 'denica';
$databaseUsername = 'root';
$databasePassword = '';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Must match registration.html input name attributes
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passwordRaw = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $passwordRaw === '') {
        die("Missing form data");
    } 

    // Hash password securely
    $passwordHash = password_hash($passwordRaw, PASSWORD_DEFAULT);

    // Insert into table
    $stmt = $conn->prepare("INSERT INTO customer (Name, Email, PasswordHash) VALUES (?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $passwordHash);

    if ($stmt->execute()) {
        echo "INSERT SUCCESS";
    } else {
        echo "INSERT FAILED: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
