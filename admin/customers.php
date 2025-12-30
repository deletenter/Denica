<?php
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Search keyword
$search = trim($_GET['search'] ?? '');

// Sort
$sort = $_GET['sort'] ?? 'name_asc';
$orderSQL = "ORDER BY FullName ASC"; // default
if ($sort == 'name_desc') $orderSQL = "ORDER BY FullName DESC";
if ($sort == 'email_asc') $orderSQL = "ORDER BY Email ASC";
if ($sort == 'email_desc') $orderSQL = "ORDER BY Email DESC";

// Build SQL WHERE for search
$whereSQL = $search ? "WHERE FullName LIKE '%$search%' OR Email LIKE '%$search%' OR Phone LIKE '%$search%'" : "";

// Query customers
$sql = "SELECT * FROM customers $whereSQL $orderSQL";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica Admin - Customers</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="main-header">
    <div class="logo">Denica</div>
    <nav>
        <a href="admin_dashboard.html">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="customers.php" class="active">Customers</a>
        <a href="orders.php">Orders</a>
    </nav>
    <div class="admin-profile">👤</div>
</header>

<section class="management-bar">
    <h1>Customer Management</h1>
    <form method="get" class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Search Customers..." value="<?= htmlspecialchars($search) ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    </form>
</section>

<main class="admin-content">

    <div class="action-row">
        <!-- Sort Dropdown -->
        <div class="sort-dropdown">
            Sort by: 
            <select id="sortSelect" onchange="sortCustomers()">
                <option value="name_asc" <?= $sort=='name_asc'?'selected':'' ?>>Name A-Z</option>
                <option value="name_desc" <?= $sort=='name_desc'?'selected':'' ?>>Name Z-A</option>
                <option value="email_asc" <?= $sort=='email_asc'?'selected':'' ?>>Email A-Z</option>
                <option value="email_desc" <?= $sort=='email_desc'?'selected':'' ?>>Email Z-A</option>
            </select>
        </div>
    </div>

    <table class="srs-table">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['CustomerID'] ?></td>
                        <td><?= htmlspecialchars($row['FullName']) ?></td>
                        <td><?= htmlspecialchars($row['Email']) ?></td>
                        <td><?= htmlspecialchars($row['Phone']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No customers found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<script>
function sortCustomers() {
    const sort = document.getElementById('sortSelect').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sort);
    window.location.search = urlParams.toString();
}
</script>

</body>
</html>

<?php mysqli_close($conn); ?>
