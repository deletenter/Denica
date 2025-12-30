<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli("localhost", "root", "", "denica");
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: login.php");
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: login.php");
        exit;
    }

    // SQL injection safe
    $stmt = $conn->prepare(
        "SELECT CustomerID, Name, PasswordHash FROM customer WHERE Email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['PasswordHash'])) {
            // Successful login
            session_regenerate_id(true);
            $_SESSION['customer_id'] = $user['CustomerID'];
            $_SESSION['customer_name'] = $user['Name'];

            header("Location: index.php");
            exit;
        }
    }

    // Login failed
    $_SESSION['login_error'] = "Email or password is incorrect.";
    header("Location: login.php");
    exit;

} catch (mysqli_sql_exception $e) {
    $_SESSION['login_error'] = "Server error. Please try again.";
    header("Location: login.php");
    exit;
}



