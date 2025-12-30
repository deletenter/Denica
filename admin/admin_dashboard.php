<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/**
 * 1. STATS QUERIES
 * Counts only active items (IsDeleted = 0)
 */
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM item WHERE IsDeleted = 0"))['total'] ?? 0;
$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM customer"))['total'] ?? 0;
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'] ?? 0;

// Calculate Total Lifetime Revenue
$revenueSQL = "SELECT SUM(Quantity * UnitPrice) AS total_rev FROM orderitem";
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, $revenueSQL))['total_rev'] ?? 0;

/**
 * 2. RECENT ORDERS QUERY
 */
$recentOrdersSQL = "SELECT 
                        o.OrderID, 
                        c.Name AS CustomerName, 
                        o.OrderStatus AS Status,
                        SUM(oi.Quantity * oi.UnitPrice) AS GrandTotal
                    FROM orders o 
                    JOIN customer c ON o.CustomerID = c.CustomerID 
                    LEFT JOIN orderitem oi ON o.OrderID = oi.OrderID
                    GROUP BY o.OrderID
                    ORDER BY o.OrderDate DESC 
                    LIMIT 5";
$recentOrders = mysqli_query($conn, $recentOrdersSQL);

/**
 * 3. CHART DATA (Last 7 Days Revenue)
 */
$chartDataSQL = "SELECT 
                    o.OrderDate as date, 
                    SUM(oi.Quantity * oi.UnitPrice) as daily_total 
                 FROM orders o
                 JOIN orderitem oi ON o.OrderID = oi.OrderID
                 WHERE o.OrderDate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                 GROUP BY o.OrderDate 
                 ORDER BY o.OrderDate ASC";
$chartResult = mysqli_query($conn, $chartDataSQL);

$labels = [];
$data = [];
while($row = mysqli_fetch_assoc($chartResult)) {
    $labels[] = date('M d', strtotime($row['date']));
    $data[] = (float)$row['daily_total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica Admin - Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <div class="admin-profile">👤 <a href="admin_logout.php" style="color:#fff; margin-left:10px; text-decoration:none;">Logout</a></div>
</header>

<section class="management-bar">
    <h1>Dashboard Overview</h1>
    <p>Welcome back, Admin. Here is your business summary.</p>
</section>

<main class="admin-content">
    <div class="stats-row">
        <div class="stat-card">
            <h3>Revenue</h3>
            <p>RM <?= number_format($totalRevenue, 2) ?></p>
            <a href="orders.php">View Reports →</a>
        </div>
        <div class="stat-card">
            <h3>Orders</h3>
            <p><?= $totalOrders ?></p>
            <a href="orders.php">Manage Orders →</a>
        </div>
        <div class="stat-card">
            <h3>Products</h3>
            <p><?= $totalProducts ?></p>
            <a href="products.php">Inventory →</a>
        </div>
        <div class="stat-card">
            <h3>Customers</h3>
            <p><?= $totalCustomers ?></p>
            <a href="customers.php">Users →</a>
        </div>
    </div>

    <div class="chart-card">
        <h2>Revenue Trends (Last 7 Days)</h2>
        <div style="height: 300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="recent-activity" style="margin-top: 30px;">
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
                <?php if(mysqli_num_rows($recentOrders) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($recentOrders)): ?>
                        <tr>
                            <td>#<?= $row['OrderID'] ?></td>
                            <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                            <td><strong>RM <?= number_format($row['GrandTotal'], 2) ?></strong></td>
                            <td>
                                <?php 
                                $statusClass = 'status-active';
                                if($row['Status'] == 'Completed') $statusClass = 'status-completed';
                                ?>
                                <span class="<?= $statusClass ?>"><?= $row['Status'] ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center;">No recent transactions</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{
            label: 'Daily Revenue (RM)',
            data: <?= json_encode($data); ?>,
            borderColor: '#1a1a1a',
            backgroundColor: 'rgba(26, 26, 26, 0.05)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#1a1a1a',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

</body>
</html>