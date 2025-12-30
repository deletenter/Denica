<?php
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid product ID");
}

// UPDATE LOGIC
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ProductName = $_POST['ProductName'];
    $brand = $_POST['brand'];
    $scentProfile = $_POST['scentProfile'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    $stmt = $conn->prepare(
        "UPDATE item 
         SET ProductName=?, Brand=?, ScentProfile=?, Description=?, Category=?, Price=?
         WHERE ItemID=?"
    );

    $stmt->bind_param(
        "sssssd" . "i",
        $ProductName,
        $brand,
        $scentProfile,
        $description,
        $category,
        $price,
        $id
    );

    $stmt->execute();
    $stmt->close();

    header("Location: products.php");
    exit;
}

// FETCH EXISTING DATA
$result = mysqli_query($conn, "SELECT * FROM item WHERE ItemID=$id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2 style="padding:20px;">Edit Product</h2>

<form method="post" style="padding:20px; max-width:600px;">

    <label>Product Name</label><br>
    <input type="text" name="ProductName" value="<?= $product['ProductName'] ?>" required><br><br>

    <label>Brand</label><br>
    <input type="text" name="brand" value="<?= $product['Brand'] ?>" required><br><br>

    <label>Scent Profile</label><br>
    <select name="scentProfile">
        <?php
        $options = ["Floral","Fruity","Citrus","Woody","Fresh","Musky"];
        foreach ($options as $opt) {
            $selected = ($product['ScentProfile'] === $opt) ? "selected" : "";
            echo "<option $selected>$opt</option>";
        }
        ?>
    </select><br><br>

    <label>Description</label><br>
    <textarea name="description" required><?= $product['Description'] ?></textarea><br><br>

    <label>Category</label><br>
    <select name="category">
        <option <?= $product['Category']=="Woman"?"selected":"" ?>>Woman</option>
        <option <?= $product['Category']=="Man"?"selected":"" ?>>Man</option>
         <option <?= $product['Category']=="UNISEX"?"selected":"" ?>>UNISEX</option>
    </select><br><br>

    <label>Price (RM)</label><br>
    <input type="number" name="price" step="0.01" value="<?= $product['Price'] ?>" required><br><br>

    <button type="submit" class="confirm-btn">Update Product</button>
    <a href="products.php" class="cancel-btn">Cancel</a>

</form>

</body>
</html>

<?php mysqli_close($conn); ?>
