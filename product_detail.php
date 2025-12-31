<?php
// 1. CONNECT TO DATABASE
$conn = mysqli_connect("localhost", "root", "", "denicaData");

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

// 5. DETERMINE IMAGE PATH
// Check if the database has a path AND if that file actually exists on your server
if (!empty($product['ImagePath']) && file_exists($product['ImagePath'])) {
    $imagePath = $product['ImagePath'];
} else {
    $imagePath = "Assets/product_placeholder.png"; 
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
    body { font-family: 'Calibri', sans-serif; background-color: #f4f7f9; }
    .product-container { display: flex; gap: 40px; max-width: 1000px; margin: 60px auto; }
    .product-image { flex: 1; background-color: #ddd; height: 400px; display: flex; align-items: center; justify-content: center; }
    .product-details { flex: 1; }
  </style>
</head>
<body>

  <div class="promo-banner">
      END OF SEASON SALE UP TO 40% OFF 
  </div>

  <header class="global-header">
     <div class="header-container">
        <a href="index.php" class="brand-identity">Denica</a>
        
        <nav class="main-nav">
            <a href="products.php">Perfumes</a>
            <a href="quiz/discovery.html">Discovery</a>
        </nav>

        <div class="search-bar">
            <form action="search_handler.php" method="GET" class="search-form">
                <input type="text" placeholder="Search" name="query" required> 
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="header-utilities">
            <a href="cart.php" class="cart-link">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count"><?php echo $cart_count; ?></span> 
            </a>
            <a href="<?php echo $profile_link; ?>" class="profile-link">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
     </div>
  </header>

    <main>
        <div class="breadcrumb" style="max-width: 1400px; margin: 20px auto; padding: 0 5%;">
            <a href="index.php">Home</a> / <a href="products.php">Perfumes</a> / <span class="active"><?php echo $product['ProductName']; ?></span>
        </div>

        <div class="container">
            
            <div class="detail-left">
                <img src="<?php echo $imagePath; ?>" 
                    alt="<?php echo htmlspecialchars($product['ProductName']); ?>" 
                    class="detail-image perfume-image">
            </div>

            <div class="detail-right">
                
                <span style="text-transform: uppercase; letter-spacing: 2px; color: #666; font-size: 0.9rem;">
                    <?php echo htmlspecialchars($product['Brand']); ?>
                </span>

                <h1 style="margin-top: 10px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($product['ProductName']); ?>
                </h1>
                
                <div style="font-size: 1.5rem; font-weight: 600; margin-bottom: 30px;">
                    RM <?php echo number_format($product['Price'], 2); ?>
                </div>
                
                <p style="margin-bottom: 30px; line-height: 1.8; color: #444;">
                    <?php echo nl2br(htmlspecialchars($product['Description'])); ?>
                </p>

                <div style="margin-bottom: 40px; padding: 20px; background: #fff; border: 1px solid #eee;">
                    <strong>Scent Profile:</strong> <?php echo htmlspecialchars($product['ScentProfile']); ?>
                    <br>
                    <strong>Category:</strong> <?php echo htmlspecialchars($product['Category']); ?>
                </div>

                <form action="add_to_cart.php" method="POST" style="display: flex; gap: 20px;">
                    <input type="hidden" name="product_id" value="<?php echo $product['ItemID']; ?>">
                    
                    <div style="width: 80px;">
                        <input type="number" name="quantity" value="1" min="1" max="10" style="text-align: center;">
                    </div>

                    <button type="submit" class="btn-primary" style="flex: 1;">Add to Cart</button>
                </form>

                <div style="margin-top: 20px;">
                     <a href="review.php" style="text-decoration: none; color: #666; font-size: 0.9rem;">
                        <i class="fas fa-star"></i> Read Reviews
                     </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="main-footer">
        <div class="footer-container">
            <p>&copy; 2025 Denica Perfumery. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
