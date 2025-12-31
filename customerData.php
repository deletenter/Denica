<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$databaseHost = 'sql100.infinityfree.com';
$databaseName = 'if0_40790146_denicadata';
$databaseUsername = 'if0_40790146';
$databasePassword = 'S9oWrWlbAjuf';

try {
    $conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Must match registration.html input name attributes
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passwordRaw = $_POST['password'] ?? '';

        // Basic presence check
        if ($name === '' || $email === '' || $passwordRaw === '') {
            die("Missing form data");
        }

        // Length limits (prevents abuse)
        if (mb_strlen($name) > 100) {
            die("Name is too long");
        }
        if (mb_strlen($email) > 120) {
            die("Email is too long");
        }
        if (strlen($passwordRaw) < 8) {
            die("Password must be at least 8 characters");
        }

        // Validate email format (server-side)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

        // Check duplicate email safely (prevents duplicate accounts)
        $check = $conn->prepare("SELECT CustomerID FROM customer WHERE Email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            die("Email already registered");
        }
        $check->close();

        // Hash password securely
        $passwordHash = password_hash($passwordRaw, PASSWORD_DEFAULT);

        // Insert into table using prepared statement (SQL injection safe)
        $stmt = $conn->prepare("INSERT INTO customer (Name, Email, PasswordHash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $passwordHash);
        $stmt->execute();

        echo "INSERT SUCCESS";
        $stmt->close();
    }

    $conn->close();

} catch (mysqli_sql_exception $e) {
    // Do not reveal SQL errors to users (security)
    // For debugging only, you can temporarily use: echo $e->getMessage();
    die("Server error. Please try again.");
}
?>
