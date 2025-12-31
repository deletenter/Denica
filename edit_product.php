<?php
$conn = mysqli_connect("sql100.infinityfree.com", "if0_40790146", "S9oWrWlbAjuf", "if0_40790146_denicadata");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid product ID");
}

// 1. FETCH EXISTING DATA FIRST
$stmt_fetch = $conn->prepare("SELECT * FROM item WHERE ItemID = ?");
$stmt_fetch->bind_param("i", $id);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found");
}

// 2. UPDATE LOGIC
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ProductName = $_POST['ProductName'];
    $brand = $_POST['brand'];
    $scentProfile = $_POST['scentProfile'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    
    // Handle Image Update
    $imagePath = $product['ImagePath']; // Default to current path
    if (isset($_FILES['ProductImage']) && $_FILES['ProductImage']['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['ProductImage']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['ProductImage']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile; // Update with new path
        }
    }

    $stmt = $conn->prepare(
        "UPDATE item 
         SET ProductName=?, Brand=?, ScentProfile=?, Description=?, Category=?, Price=?, ImagePath=?
         WHERE ItemID=?"
    );

    $stmt->bind_param(
        "sssssdsi",
        $ProductName,
        $brand,
        $scentProfile,
        $description,
        $category,
        $price,
        $imagePath,
        $id
    );

    if ($stmt->execute()) {
        header("Location: products.php?update=success");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Denica Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="main-header">
    <div class="logo">Denica</div>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="products.php" class="active">Products</a>
    </nav>
    <div class="admin-profile">👤</div>
</header>

<section class="management-bar">
    <h1>Edit Product: <?= htmlspecialchars($product['ProductName']) ?></h1>
</section>

<main class="admin-content">
    <div class="modal-card" style="display: block; margin: 0 auto; width: 100%; max-width: 900px;">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-body">
                
                <div class="upload-section">
                    <label style="display:block; margin-bottom:10px; font-weight:bold;">Product Image</label>
                    <div class="upload-box" style="height: 350px;">
                        <?php if($product['ImagePath']): ?>
                            <img src="<?= $product['ImagePath'] ?>" style="max-width:100%; max-height:200px; margin-bottom:15px; border-radius:4px;">
                        <?php endif; ?>
                        <p style="font-size: 12px; color: #666;">Change Image:</p>
                        <input type="file" name="ProductImage" accept="image/*">
                    </div>
                </div>

                <div class="form-section">
                    <div class="field">
                        <label>Product Name</label>
                        <input type="text" name="ProductName" value="<?= htmlspecialchars($product['ProductName']) ?>" required>
                    </div>

                    <div class="field">
                        <label>Brand</label>
                        <input type="text" name="brand" value="<?= htmlspecialchars($product['Brand']) ?>" required>
                    </div>

                    <div class="field">
                        <label>Scent Profile</label>
                        <select name="scentProfile">
                            <?php
                            $options = ["Floral","Fruity","Citrus","Woody","Fresh","Musky"];
                            foreach ($options as $opt) {
                                $selected = ($product['ScentProfile'] === $opt) ? "selected" : "";
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" rows="4" required><?= htmlspecialchars($product['Description']) ?></textarea>
                    </div>

                    <div class="field">
                        <label>Category</label>
                        <select name="category">
                            <option value="Woman" <?= $product['Category']=="Woman"?"selected":"" ?>>Woman</option>
                            <option value="Man" <?= $product['Category']=="Man"?"selected":"" ?>>Man</option>
                            <option value="UNISEX" <?= $product['Category']=="UNISEX"?"selected":"" ?>>UNISEX</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Price (RM)</label>
                        <input type="number" name="price" step="0.01" value="<?= $product['Price'] ?>" required>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="confirm-btn">Save Changes</button>
                        <a href="products.php" class="cancel-btn" style="text-decoration:none; text-align:center; line-height:1.2;">Cancel</a>
                    </div>
                </div>

            </div>
        </form>
    </div>
</main>

</body>
</html>
<?php mysqli_close($conn); ?>