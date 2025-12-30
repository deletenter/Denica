<?php
header('Content-Type: application/json');

$databaseHost = 'localhost'; 
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'denicaData';

$conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT 
            ItemID as id, 
            ProductName as name, 
            Brand as brand, 
            Price as price, 
            ScentProfile as scent_profile, 
            Description as description,
            CONCAT('Assets/', ProductName, '.png') as image 
        FROM item";

$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['price'] = (float)$row['price'];
        
        // OPTIONAL: Fallback if the specific image doesn't exist
        // This ensures the site doesn't look broken if you haven't uploaded 'Mahsuri.png' yet.
        if (!file_exists($row['image'])) {
            $row['image'] = 'Assets/perfumery.jpg'; // Use a default image you know works
        }
        
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>
