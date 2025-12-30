<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "denica");
$conn->set_charset("utf8mb4");

$search = trim($_GET['q'] ?? '');

// If empty search → show all products
if ($search === '') {
    $stmt = $conn->prepare("SELECT * FROM item ORDER BY ProductName");
} else {
    // SQL injection safe search
    $stmt = $conn->prepare("
        SELECT *
        FROM item
        WHERE ProductName LIKE ?
           OR Brand LIKE ?
           OR Description LIKE ?
        ORDER BY ProductName
    ");

    $keyword = "%" . $search . "%";
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Perfumes</title>
</head>
<body>

<h2>Search Results for: <?= htmlspecialchars($search) ?></h2>

<?php if ($result->num_rows === 0): ?>
    <p>No products found.</p>
<?php endif; ?>

<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <strong><?= htmlspecialchars($row['ProductName']) ?></strong><br>
        Brand: <?= htmlspecialchars($row['Brand']) ?><br>
        Price: RM <?= number_format($row['Price'], 2) ?>
    </li>
<?php endwhile; ?>
</ul>

</body>
</html>
