<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Optional: check if admin is logged in
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: admin_login.php");
//     exit;
// }

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Total products
$totalProductsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM item");
$totalProductsRow = mysqli_fetch_assoc($totalProductsQuery);
$totalProducts = $totalProductsRow['total'] ?? 0;

// Total customers
$totalCustomersQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers");
$totalCustomersRow = mysqli_fetch_assoc($totalCustomersQuery);
$totalCustomers = $totalCustomersRow['total'] ?? 0;

// Recent orders (latest 5)
$recentOrdersSQL = "SELECT o.OrderID, c.FullName AS CustomerName, o.GrandTotal, o.Status 
                    FROM orders o
                    JOIN customers c ON o.CustomerID = c.CustomerID
                    ORDER BY o.OrderDate DESC
                    LIMIT 5";
$recentOrders = mysqli_query($conn, $recentOrdersSQL);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica Admin - Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="main-header">
    <div class="logo">Denica</div>
    <nav>
        <a href="admin_dashboard.php" class="active">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="customers.php">Customers</a>
        <a href="orders.php">Orders</a>
    </nav>
    <div class="admin-profile">
        👤
        <a href="admin_logout.php" style="color:#fff; margin-left:10px; text-decoration:none;">Logout</a>
    </div>
</header>

<section class="management-bar">
    <h1>Dashboard Overview</h1>
    <p>Welcome back, Admin. Here is what's happening with Denica Perfumery today.</p>
</section>

<main class="admin-content">
    <div class="stats-row">
        <div class="stat-card">
            <h3>Total Products</h3>
            <p><?= $totalProducts ?></p>
            <a href="products.php">Manage Products →</a>
        </div>
        <div class="stat-card">
            <h3>Registered Members</h3>
            <p><?= $totalCustomers ?></p>
            <a href="customers.php">View Members →</a>
        </div>
        <div class="stat-card">
            <h3>Recent Orders</h3>
            <p><?= mysqli_num_rows($recentOrders) ?></p>
            <a href="orders.php">Track Orders →</a>
        </div>
    </div>

    <div class="recent-activity">
        <h2>Recent Transactions</h2>
        <table class="srs-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if($recentOrders && mysqli_num_rows($recentOrders) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($recentOrders)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['OrderID']) ?></td>
                            <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                            <td>RM <?= number_format($row['GrandTotal'], 2) ?></td>
                            <td>
                                <?php 
                                    if($row['Status'] == 'Completed') echo '<span class="status-completed">Completed</span>';
                                    elseif($row['Status'] == 'Pending') echo '<span class="status-active">Pending</span>';
                                    else echo htmlspecialchars($row['Status']);
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No recent transactions</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>

<?php mysqli_close($conn); ?>