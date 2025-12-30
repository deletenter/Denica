<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SEARCH LOGIC
$search = $_GET['search'] ?? '';

if ($search !== '') {
    $stmt = $conn->prepare(
        "SELECT * FROM item 
         WHERE ProductName LIKE ? 
            OR Brand LIKE ? 
            OR ScentProfile LIKE ?
         ORDER BY CreatedAt DESC"
    );

    $keyword = "%$search%";
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    $result = mysqli_query($conn, "SELECT * FROM item ORDER BY CreatedAt DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica Admin - Product Management</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="main-header">
    <div class="logo">Denica</div>
    <nav>
        <a href="admin_dashboard.html">Dashboard</a>
        <a href="products.php" class="active">Products</a>
        <a href="customers.html">Customers</a>
        <a href="orders.html">Orders</a>
    </nav>
    <div class="admin-profile">👤</div>
</header>

<section class="management-bar">
    <h1>Product Management</h1>

    <!-- SEARCH FORM -->
    <form method="get" class="search-box">
        <span>🔍</span>
        <input
            type="text"
            name="search"
            placeholder="Find Product..."
            value="<?= htmlspecialchars($search) ?>"
        >
    </form>
</section>

<main class="admin-content">

    <div class="action-row">
        <div class="sort-dropdown">
            <?= $search ? "Search results for <strong>'" . htmlspecialchars($search) . "'</strong>" : "Sort by: <strong>Latest</strong>" ?>
        </div>
        <button class="add-btn" onclick="openModal()">+ Add New Product</button>
    </div>

    <table class="srs-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Scent Profile</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price (RM)</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><div class="img-placeholder"></div></td>
                    <td><?= htmlspecialchars($row['ProductName']) ?></td>
                    <td><?= htmlspecialchars($row['Brand']) ?></td>
                    <td><?= htmlspecialchars($row['ScentProfile']) ?></td>
                    <td><?= htmlspecialchars($row['Description']) ?></td>
                    <td><?= htmlspecialchars($row['Category']) ?></td>
                    <td><?= number_format($row['Price'], 2) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['ItemID'] ?>" class="edit-btn">Edit</a>
                        |
                        <a href="delete_product.php?id=<?= $row['ItemID'] ?>"
                           class="delete-btn"
                           onclick="return confirm('Delete this product?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8" style="text-align:center;">
                    No products found
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</main>

<!-- ADD PRODUCT MODAL -->
<div id="addModal" class="modal">
    <div class="modal-card">
        <h2>Add New Product</h2>

        <div class="modal-body">
            <div class="upload-section">
                <div class="upload-box">
                    <span>⬆️</span>
                    <p>Upload Image Here</p>
                </div>
            </div>

            <div class="form-section">
                <form action="ProductData.php" method="post">

                    <div class="field">
                        <label>Product Name</label><br>
                        <input type="text" name="ProductName" required>
                    </div>

                    <div class="field">
                        <label>Brand</label><br>
                        <input type="text" name="brand" required>
                    </div>

                    <div class="field">
                        <label>Scent Profile</label><br>
                        <select name="scentProfile" required>
                            <option>Floral</option>
                            <option>Fruity</option>
                            <option>Citrus</option>
                            <option>Woody</option>
                            <option>Fresh</option>
                            <option>Musky</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Description</label><br>
                        <textarea name="description" required></textarea>
                    </div>

                    <div class="field">
                        <label>Category</label><br>
                        <select name="category" required>
                            <option>Woman</option>
                            <option>Man</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Price (RM)</label><br>
                        <input type="number" name="price" step="0.01" required>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="confirm-btn">+ Add New Product</button>
                        <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('addModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('addModal').style.display = 'none';
}
</script>

</body>
</html>

<?php
if (isset($stmt)) $stmt->close();
mysqli_close($conn);
?>
