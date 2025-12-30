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

    $ProductName = trim($_POST['ProductName'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $scentProfile = trim($_POST['scentProfile'] ?? '');
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = (float) ($_POST['price'] ?? 0);

    if ($ProductName === '' || $brand === '' || $scentProfile === '' || $description === '' || $category === '' || $price === '') {
        die("Missing form data");
    }
    // Insert into table
    $stmt = $conn->prepare("INSERT INTO item (ProductName, Brand, ScentProfile, Description, Category, Price) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss" . "d", $ProductName, $brand, $scentProfile, $description, $category, $price);

    if ($stmt->execute()) {
        echo "INSERT SUCCESS";
    } else {
        echo "INSERT FAILED: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
