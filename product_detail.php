<?php
// 1. CONNECT TO DATABASE
$conn = mysqli_connect("localhost", "root", "", "denica");

// 2. GET ID FROM URL
// If no ID is set, default to 0 (or redirect)
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 3. FETCH PRODUCT DATA
$sql = "SELECT * FROM item WHERE ItemID = $product_id";
$result = $conn->query($sql);

// 4. CHECK IF PRODUCT EXISTS
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<h1>Product not found.</h1>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['ProductName']; ?> | Denica</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* ... keep your existing CSS styles from the HTML file ... */
    body { font-family: 'Calibri', sans-serif; background-color: #f4f7f9; }
    .product-container { display: flex; gap: 40px; max-width: 1000px; margin: 60px auto; }
    .product-image { flex: 1; background-color: #ddd; height: 400px; display: flex; align-items: center; justify-content: center; }
    .product-details { flex: 1; }
    /* Add the rest of your CSS here */
  </style>
</head>
<body>

  <header class="global-header">
     <div class="header-container">
        <a href="index.html" class="brand-identity">Denica</a>
        </div>
  </header>

    <main>
        <div class="product-container">
        <div class="product-image">
            Image for <?php echo htmlspecialchars($product['ProductName']); ?>
        </div>

        <div class="product-details">
            <div class="product-title-row">
            <h2><?php echo htmlspecialchars($product['ProductName']); ?></h2>
            <div class="review-buttons">
                <a href="review.html">♥ Rating & Reviews</a>
            </div>
            </div>

            <div class="price">RM <?php echo number_format($product['Price'], 2); ?></div>
            
            <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['Brand']); ?></p>
            
            <p><?php echo nl2br(htmlspecialchars($product['Description'])); ?></p>

            <p><strong>Scent Profile:</strong> <?php echo htmlspecialchars($product['ScentProfile']); ?></p>

            <div class="action-row">
            <button class="btn-add">Add to Cart</button>
            </div>
        </div>
        </div>
    </main>

</body>
</html>