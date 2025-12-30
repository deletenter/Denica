<?php
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Search keyword
$search = trim($_GET['search'] ?? '');

// Sort Logic - Using 'Name' and 'CustomerID' from your SQL dump
$sort = $_GET['sort'] ?? 'id_desc';

$orderSQL = "CustomerID DESC"; // Default
switch ($sort) {
    case 'name_asc':  $orderSQL = "Name ASC"; break;
    case 'name_desc': $orderSQL = "Name DESC"; break;
    case 'email_asc': $orderSQL = "Email ASC"; break;
    case 'email_desc':$orderSQL = "Email DESC"; break;
    case 'id_desc':   $orderSQL = "CustomerID DESC"; break;
}

// Build Query targeting the 'customer' table
if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE Name LIKE ? OR Email LIKE ? ORDER BY $orderSQL");
    $keyword = "%$search%";
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, "SELECT * FROM customer ORDER BY $orderSQL");
}
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
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="customers.php" class="active">Customers</a>
        <a href="orders.php">Orders</a>
    </nav>
    <div class="admin-profile">👤 Admin</div>
</header>

<section class="management-bar">
    <h1>Customer Management</h1>
    <form method="get" class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Search by Name or Email..." value="<?= htmlspecialchars($search) ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    </form>
</section>

<main class="admin-content">
    <div class="action-row">
        <div class="sort-dropdown">
            Sort by: 
            <select id="sortSelect" onchange="sortCustomers()">
                <option value="id_desc" <?= $sort=='id_desc'?'selected':'' ?>>Newest First</option>
                <option value="name_asc" <?= $sort=='name_asc'?'selected':'' ?>>Name A-Z</option>
                <option value="name_desc" <?= $sort=='name_desc'?'selected':'' ?>>Name Z-A</option>
                <option value="email_asc" <?= $sort=='email_asc'?'selected':'' ?>>Email A-Z</option>
            </select>
        </div>
        <div class="sort-dropdown">
            Total Customers: <strong><?= mysqli_num_rows($result) ?></strong>
        </div>
    </div>

    <table class="srs-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email Address</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>#<?= $row['CustomerID'] ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($row['Name']) ?></td>
                        <td><?= htmlspecialchars($row['Email']) ?></td>
                        <td style="text-align: center;">
                            <span class="status-active">● Active</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center; padding: 40px;">No customers found.</td></tr>
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