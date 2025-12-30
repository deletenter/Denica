<?php
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM item WHERE ItemID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

mysqli_close($conn);
header("Location: products.php");
exit;
