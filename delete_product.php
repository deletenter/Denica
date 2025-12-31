<?php
$conn = mysqli_connect("sql100.infinityfree.com", "if0_40790146", "S9oWrWlbAjuf", "if0_40790146_denicadata");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Instead of DELETE, we UPDATE the status to 1 (Hidden/Deleted)
    $stmt = $conn->prepare("UPDATE item SET IsDeleted = 1 WHERE ItemID = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: products.php?status=deleted");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

mysqli_close($conn);
exit;