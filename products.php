<?php
// Database connection
$conn = mysqli_connect("sql100.infinityfree.com", "if0_40790146", "S9oWrWlbAjuf", "if0_40790146_denicadata");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SEARCH LOGIC
$search = $_GET['search'] ?? '';

if ($search !== '') {
    // We added "AND IsDeleted = 0" to hide soft-deleted items
    $stmt = $conn->prepare(
        "SELECT * FROM item 
         WHERE (ProductName LIKE ? 
            OR Brand LIKE ? 
            OR ScentProfile LIKE ?)
         AND IsDeleted = 0
         ORDER BY ItemID DESC"
    );

    $keyword = "%$search%";
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default view: only show items where IsDeleted is 0
    $result = mysqli_query($conn, "SELECT * FROM item WHERE IsDeleted = 0 ORDER BY ItemID DESC");
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
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="products.php" class="active">Products</a>
        <a href="customers.php">Customers</a>
        <a href="orders.php">Orders</a>
    </nav>
    <div class="admin-profile">👤 Admin</div>
</header>

<section class="management-bar">
    <h1>Product Management</h1>
    <form method="get" class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Find Product..." value="<?= htmlspecialchars($search) ?>">
    </form>
</section>

<main class="admin-content">

    <?php if(isset($_GET['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #c3e6cb;">
            Product added successfully!
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['update'])): ?>
        <div style="background: #cce5ff; color: #004085; padding: 10px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #b8daff;">
            Product updated successfully!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb;">
            Product removed from active listing.
        </div>
    <?php endif; ?>

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
                <th>Category</th>
                <th>Price (RM)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>
                        <?php if (!empty($row['ImagePath'])): ?>
                            <img src="<?= $row['ImagePath'] ?>" class="prod-img" alt="Perfume">
                        <?php else: ?>
                            <div class="img-placeholder"></div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['ProductName']) ?></td>
                    <td><?= htmlspecialchars($row['Brand']) ?></td>
                    <td><?= htmlspecialchars($row['ScentProfile']) ?></td>
                    <td><?= htmlspecialchars($row['Category']) ?></td>
                    <td><?= number_format($row['Price'], 2) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['ItemID'] ?>" class="edit-btn">Edit</a>
                        <a href="delete_product.php?id=<?= $row['ItemID'] ?>" 
                           class="delete-btn" 
                           onclick="return confirm('Archive this product? (It will be hidden but kept for order history)');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="7" style="text-align:center; padding: 40px;">No products found in active listings.</td></tr>
        <?php } ?>
        </tbody>
    </table>
</main>

<div id="addModal" class="modal">
    <div class="modal-card">
        <h2>Add New Product</h2>
        <form action="ProductData.php" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="upload-section">
                    <div class="upload-box">
                        <span>⬆️</span>
                        <p>Upload Image</p>
                        <input type="file" name="ProductImage" accept="image/*" required>
                    </div>
                </div>

                <div class="form-section">
                    <div class="field">
                        <label>Product Name</label>
                        <input type="text" name="ProductName" required>
                    </div>
                    <div class="field">
                        <label>Brand</label>
                        <input type="text" name="brand" required>
                    </div>
                    <div class="field">
                        <label>Scent Profile</label>
                        <select name="scentProfile" required>
                            <option value="Floral">Floral</option>
                            <option value="Fruity">Fruity</option>
                            <option value="Citrus">Citrus</option>
                            <option value="Woody">Woody</option>
                            <option value="Fresh">Fresh</option>
                            <option value="Musky">Musky</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" rows="3" required></textarea>
                    </div>
                    <div class="field">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="Woman">Woman</option>
                            <option value="Man">Man</option>
                            <option value="UNISEX">UNISEX</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Price (RM)</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="confirm-btn">Save Product</button>
                        <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() { document.getElementById('addModal').style.display = 'flex'; }
function closeModal() { document.getElementById('addModal').style.display = 'none'; }
window.onclick = function(event) { if (event.target == document.getElementById('addModal')) closeModal(); }
</script>

</body>
</html>
<?php mysqli_close($conn); ?>