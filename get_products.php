<?php
header('Content-Type: application/json');

$databaseHost = 'localhost'; 
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'denica';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Fixed Query: Using ItemID as 'id' and ScentProfile as 'size' to match your JS
$sql = "SELECT ItemID as id, ProductName as name, Brand as brand, Price as price, ScentProfile as size, Description as description FROM item";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['price'] = (float)$row['price']; 
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>
