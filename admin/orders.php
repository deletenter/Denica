<?php
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Filter type: all, daily, weekly, monthly
$filter = $_GET['filter'] ?? 'all';

// Search keyword
$search = trim($_GET['search'] ?? '');

// Sort
$sort = $_GET['sort'] ?? 'date_desc';
$orderSQL = "ORDER BY o.OrderDate DESC"; // default
if ($sort == 'date_asc') $orderSQL = "ORDER BY o.OrderDate ASC";
if ($sort == 'grandtotal_desc') $orderSQL = "ORDER BY o.GrandTotal DESC";
if ($sort == 'grandtotal_asc') $orderSQL = "ORDER BY o.GrandTotal ASC";

// Build SQL WHERE conditions
$where = [];
if ($filter == 'daily') {
    $where[] = "DATE(o.OrderDate) = CURDATE()";
} elseif ($filter == 'weekly') {
    $where[] = "YEARWEEK(o.OrderDate, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'monthly') {
    $where[] = "MONTH(o.OrderDate) = MONTH(CURDATE()) AND YEAR(o.OrderDate) = YEAR(CURDATE())";
}

// Add search condition
if ($search !== '') {
    $where[] = "o.OrderID LIKE '%$search%' OR c.FullName LIKE '%$search%'";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Query orders with customer names
$sql = "SELECT o.*, c.FullName AS CustomerName 
        FROM orders o
        JOIN customers c ON o.CustomerID = c.CustomerID
        $whereSQL
        $orderSQL";
$result = mysqli_query($conn, $sql);

// Calculate totals
$totalItems = 0;
$totalRevenue = 0;
if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $totalItems += $row['ItemsSold'];
        $totalRevenue += $row['GrandTotal'];
    }
}

// Re-run query to fetch rows for display
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica Admin - Order Management</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="main-header">
    <div class="logo">Denica</div>
    <nav>
        <a href="admin_dashboard.html">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="customers.html">Customers</a>
        <a href="orders.php" class="active">Orders</a>
    </nav>
    <div class="admin-profile">👤</div>
</header>

<section class="management-bar">
    <h1>Order Management</h1>
    <form method="get" class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Find Order..." value="<?= htmlspecialchars($search) ?>">
        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    </form>
</section>

<main class="admin-content">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="orders.php?filter=all&sort=<?= $sort ?>&search=<?= urlencode($search) ?>" class="tab <?= $filter=='all'?'active':'' ?>">All</a>
        <a href="orders.php?filter=daily&sort=<?= $sort ?>&search=<?= urlencode($search) ?>" class="tab <?= $filter=='daily'?'active':'' ?>">Daily</a>
        <a href="orders.php?filter=weekly&sort=<?= $sort ?>&search=<?= urlencode($search) ?>" class="tab <?= $filter=='weekly'?'active':'' ?>">Weekly</a>
        <a href="orders.php?filter=monthly&sort=<?= $sort ?>&search=<?= urlencode($search) ?>" class="tab <?= $filter=='monthly'?'active':'' ?>">Monthly</a>
    </div>

    <div class="action-row">
        <!-- Sort Dropdown -->
        <div class="sort-dropdown">
            Sort by: 
            <select id="sortSelect" onchange="sortOrders()">
                <option value="date_desc" <?= $sort=='date_desc'?'selected':'' ?>>Date & Time ▼</option>
                <option value="date_asc" <?= $sort=='date_asc'?'selected':'' ?>>Date & Time ▲</option>
                <option value="grandtotal_desc" <?= $sort=='grandtotal_desc'?'selected':'' ?>>Grand Total ▼</option>
                <option value="grandtotal_asc" <?= $sort=='grandtotal_asc'?'selected':'' ?>>Grand Total ▲</option>
            </select>
        </div>

        <div class="table-utilities">
            <button onclick="window.print()">🖨️</button>
        </div>
    </div>

    <!-- Display total items and revenue -->
    <div style="margin-bottom: 15px;">
        <strong>Total Items:</strong> <?= $totalItems ?> |
        <strong>Total Revenue:</strong> RM <?= number_format($totalRevenue, 2) ?>
    </div>

    <table class="srs-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date & Time</th>
                <th>Customer</th>
                <th>Items Sold</th>
                <th>Grand Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['OrderID']) ?></td>
                        <td><?= date("d-m-Y H:i", strtotime($row['OrderDate'])) ?></td>
                        <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                        <td><?= $row['ItemsSold'] ?></td>
                        <td>RM <?= number_format($row['GrandTotal'], 2) ?></td>
                        <td>
                            <?php if($row['Status'] == 'Completed'): ?>
                                <span class="status-completed">Completed</span>
                            <?php elseif($row['Status'] == 'Pending'): ?>
                                <span class="status-active">Pending</span>
                            <?php else: ?>
                                <span><?= htmlspecialchars($row['Status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No orders found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<script>
function sortOrders() {
    const sort = document.getElementById('sortSelect').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sort);
    window.location.search = urlParams.toString();
}
</script>

</body>
</html>

<?php mysqli_close($conn); ?>
