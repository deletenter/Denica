<?php
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'denica';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ProductName = trim($_POST['ProductName'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $scentProfile = trim($_POST['scentProfile'] ?? '');
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = (float) ($_POST['price'] ?? 0);

    if ($ProductName === '' || $brand === '' || $scentProfile === '' || 
    $description === '' || $category === '' || $price <= 0) {
    die("Missing or invalid form data");
}

    // Insert into table
    $stmt = $conn->prepare("INSERT INTO item (ProductName, Brand, ScentProfile, Description, Category, Price) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
    "sssssd",
    $ProductName,
    $brand,
    $scentProfile,
    $description,
    $category,
    $price
);

if ($stmt->execute()) {
    header("Location: products.php?success=1");
    exit;
}

    $stmt->close();
}

$conn->close();
?>
