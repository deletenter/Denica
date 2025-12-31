<?php
// Connect to database
$conn = mysqli_connect("sql100.infinityfree.com", "if0_40790146", "S9oWrWlbAjuf", "if0_40790146_denicadata");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'date_desc';

// Sort Logic
$orderSQL = "ORDER BY o.OrderDate DESC";
if ($sort == 'date_asc') $orderSQL = "ORDER BY o.OrderDate ASC";
if ($sort == 'total_desc') $orderSQL = "ORDER BY GrandTotal DESC";
if ($sort == 'total_asc') $orderSQL = "ORDER BY GrandTotal ASC";

// Build WHERE conditions
$where = [];
if ($filter == 'daily') {
    $where[] = "DATE(o.OrderDate) = CURDATE()";
} elseif ($filter == 'weekly') {
    $where[] = "YEARWEEK(o.OrderDate, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'monthly') {
    $where[] = "MONTH(o.OrderDate) = MONTH(CURDATE()) AND YEAR(o.OrderDate) = YEAR(CURDATE())";
}

if ($search !== '') {
    $where[] = "(o.OrderID LIKE ? OR c.Name LIKE ?)";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT o.OrderID, o.OrderDate, o.OrderStatus, c.Name AS CustomerName, 
               SUM(oi.Quantity) AS ItemsSold, 
               SUM(oi.Quantity * oi.UnitPrice) AS GrandTotal
        FROM orders o
        JOIN customer c ON o.CustomerID = c.CustomerID
        JOIN orderitem oi ON o.OrderID = oi.OrderID
        $whereSQL
        GROUP BY o.OrderID
        $orderSQL";

$stmt = $conn->prepare($sql);
if ($search !== '') {
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}
$stmt->execute();
$result = $stmt->get_result();

$totalItems = 0;
$totalRevenue = 0;
$orderCount = 0;
$rows = [];
while($row = $result->fetch_assoc()) {
    $totalItems += $row['ItemsSold'];
    $totalRevenue += $row['GrandTotal'];
    $orderCount++;
    $rows[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica Report - <?= ucfirst($filter) ?> Orders</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        /* PDF/Print Specific Styles */
        @media print {
            .main-header, .management-bar, .filter-tabs, .action-row, .delete-btn, .edit-btn {
                display: none !important;
            }
            body { background: white; }
            .admin-content { padding: 0; }
            .print-header { display: block !important; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
            .srs-table { box-shadow: none; border: 1px solid #000; }
            .srs-table th { background: #eee !important; color: #000 !important; }
        }
        .print-header { display: none; }
    </style>
</head>
<body>

<div class="print-header">
    <h1>DENICA TRANSACTION REPORT</h1>
    <p><strong>Report Type:</strong> <?= strtoupper($filter) ?> ORDERS</p>
    <p><strong>Generated On:</strong> <?= date("d-m-Y H:i:s") ?></p>
    <p><strong>Summary:</strong> <?= $orderCount ?> Orders | <?= $totalItems ?> Items Sold | Total RM <?= number_format($totalRevenue, 2) ?></p>
</div>

<header class="main-header">
    <div class="logo">Denica</div>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="customers.php">Customers</a>
        <a href="orders.php" class="active">Orders</a>
    </nav>
    <div class="admin-profile">👤 Admin</div>
</header>

<section class="management-bar">
    <h1>Order Management</h1>
    <form method="get" class="search-box">
        <span>🔍</span>
        <input type="text" name="search" placeholder="Search Order ID or Customer..." value="<?= htmlspecialchars($search) ?>">
    </form>
</section>

<main class="admin-content">
    <div class="filter-tabs">
        <a href="orders.php?filter=all" class="tab <?= $filter=='all'?'active':'' ?>">All</a>
        <a href="orders.php?filter=daily" class="tab <?= $filter=='daily'?'active':'' ?>">Daily</a>
        <a href="orders.php?filter=weekly" class="tab <?= $filter=='weekly'?'active':'' ?>">Weekly</a>
        <a href="orders.php?filter=monthly" class="tab <?= $filter=='monthly'?'active':'' ?>">Monthly</a>
    </div>

    <div class="action-row">
        <div class="sort-dropdown">
            Sort by: 
            <select id="sortSelect" onchange="sortOrders()">
                <option value="date_desc" <?= $sort=='date_desc'?'selected':'' ?>>Newest First</option>
                <option value="total_desc" <?= $sort=='total_desc'?'selected':'' ?>>Highest Amount</option>
            </select>
        </div>
        <div class="table-utilities">
            <button onclick="window.print()" class="add-btn" style="background: #1a1a1a; color: white;">
                📄 Generate PDF Report
            </button>
        </div>
    </div>

    <div style="margin-bottom: 15px; padding: 15px; background: #fff; border-radius: 4px; display: flex; justify-content: space-between;">
        <span>Orders: <strong><?= $orderCount ?></strong></span>
        <span>Items Sold: <strong><?= $totalItems ?></strong></span>
        <span>Total Revenue: <strong style="color: #2e7d32;">RM <?= number_format($totalRevenue, 2) ?></strong></span>
    </div>

    <table class="srs-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
                <tr>
                    <td>#<?= $row['OrderID'] ?></td>
                    <td><?= date("d/m/Y", strtotime($row['OrderDate'])) ?></td>
                    <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                    <td><?= $row['ItemsSold'] ?></td>
                    <td>RM <?= number_format($row['GrandTotal'], 2) ?></td>
                    <td><?= $row['OrderStatus'] ?></td>
                </tr>
            <?php endforeach; ?>
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