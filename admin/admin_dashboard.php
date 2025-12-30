<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$conn = mysqli_connect("localhost", "root", "", "denica");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Stats queries
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM item"))['total'] ?? 0;
$totalCustomers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM customers"))['total'] ?? 0;
$recentOrders = mysqli_query($conn, "SELECT o.OrderID, c.FullName AS CustomerName, o.GrandTotal, o.Status 
                    FROM orders o JOIN customers c ON o.CustomerID = c.CustomerID 
                    ORDER BY o.OrderDate DESC LIMIT 5");

// --- DATA FOR CHART ---
// Fetching revenue for the last 7 days
$chartDataSQL = "SELECT DATE(OrderDate) as date, SUM(GrandTotal) as daily_total 
                 FROM orders 
                 WHERE OrderDate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                 GROUP BY DATE(OrderDate) 
                 ORDER BY DATE(OrderDate) ASC";
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
    <p>Welcome back, Admin. Here is what's happening today.</p>
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

    <div class="chart-container">
        <h2>Revenue Trends (Last 7 Days)</h2>
        <canvas id="salesChart"></canvas>
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
                <?php if(mysqli_num_rows($recentOrders) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($recentOrders)): ?>
                        <tr>
                            <td><?= $row['OrderID'] ?></td>
                            <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                            <td>RM <?= number_format($row['GrandTotal'], 2) ?></td>
                            <td>
                                <span class="<?= $row['Status'] == 'Completed' ? 'status-completed' : 'status-active' ?>">
                                    <?= $row['Status'] ?>
                                </span>
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
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Daily Revenue (RM)',
            data: <?php echo json_encode($data); ?>,
            borderColor: '#1a1a1a',
            backgroundColor: 'rgba(26, 26, 26, 0.05)',
            borderWidth: 3,
            tension: 0.4, // Makes the line smooth
            fill: true,
            pointBackgroundColor: '#1a1a1a',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#eee' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>

</body>
</html>