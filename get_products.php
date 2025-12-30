<?php
header('Content-Type: application/json');

// UPDATE THESE with your InfinityFree details for the live site
$databaseHost = 'localhost'; 
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'denica';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// We select the columns defined in your ProductData.php
$sql = "SELECT ProductName as name, Brand as brand, Price as price, ScentProfile as size FROM item";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['price'] = (float)$row['price']; // Convert to number for JS sorting
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>