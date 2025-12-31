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

    // 1. Capture and Clean Data
    $ProductName = trim($_POST['ProductName'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $scentProfile = trim($_POST['scentProfile'] ?? '');
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = (float) ($_POST['price'] ?? 0);

    // 2. Image Upload Logic
    $imagePath = '';
    if (isset($_FILES['ProductImage']) && $_FILES['ProductImage']['error'] === 0) {
        $uploadDir = 'uploads/';
        
        // Create folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['ProductImage']['name']));
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['ProductImage']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    // 3. Validation
    if ($ProductName === '' || $brand === '' || $price <= 0) {
        die("Error: Please fill in all required fields and a valid price.");
    }

    // 4. Database Insert
    // NOTE: Ensure your table has an 'ImagePath' column
    $sql = "INSERT INTO item (ProductName, Brand, ScentProfile, Description, Category, Price, ImagePath) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssds", $ProductName, $brand, $scentProfile, $description, $category, $price, $imagePath);

    if ($stmt->execute()) {
        header("Location: products.php?success=1");
        exit;
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>