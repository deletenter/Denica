<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "denicaData"); // <-- change to your db name
$conn->set_charset("utf8mb4");

// IMPORTANT: match your search bar input name="query"
$search = trim($_GET['query'] ?? '');

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
           OR ScentProfile LIKE ?
           OR Category LIKE ?
           OR Description LIKE ?
        ORDER BY ProductName
    ");

    $keyword = "%" . $search . "%";
    $stmt->bind_param("sssss", $keyword, $keyword, $keyword, $keyword, $keyword);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denica - Perfumes</title>
    <link rel="stylesheet" href="style.css">

    <style>
      .wrap { max-width: 900px; margin: 40px auto; padding: 0 16px; }
      .top { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
      .searchAgain { display:flex; gap:8px; }
      .searchAgain input { padding:10px 12px; border:1px solid #ddd; border-radius:8px; width:260px; }
      .searchAgain button { padding:10px 14px; border:none; background:#000; color:#fff; border-radius:8px; cursor:pointer; }
      .list { list-style:none; padding:0; margin-top:20px; }
      .item { background:#fff; border:1px solid #eee; border-radius:12px; padding:16px; margin-bottom:12px; }
      .name { font-weight:800; font-size:18px; margin-bottom:6px; }
      .meta { color:#666; font-size:14px; margin-bottom:8px; }
      .price { font-weight:800; }
      .empty { background:#fff; border:1px solid #eee; padding:18px; border-radius:12px; margin-top:20px; }
    </style>
</head>
<body>

<div class="wrap">

  <div class="top">
    <div>
      <h2>
        <?php if ($search !== ''): ?>
          Search Results for: <?= htmlspecialchars($search) ?>
        <?php else: ?>
          All Perfumes
        <?php endif; ?>
      </h2>
    </div>

    <!-- Search again -->
    <form class="searchAgain" action="products.php" method="GET">
      <input type="text" name="query" placeholder="Search again..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>
  </div>

  <?php if ($result->num_rows === 0): ?>
      <div class="empty">
        <p><strong>No products found.</strong></p>
        <p>Try keywords like “Medin”, “Fresh”, “Fruity”, “UNISEX”.</p>
      </div>
  <?php else: ?>
      <ul class="list">
      <?php while ($row = $result->fetch_assoc()): ?>
          <li class="item">
              <div class="name"><?= htmlspecialchars($row['ProductName']) ?></div>
              <div class="meta">
                Brand: <?= htmlspecialchars($row['Brand']) ?> |
                Scent: <?= htmlspecialchars($row['ScentProfile'] ?? '-') ?> |
                Category: <?= htmlspecialchars($row['Category'] ?? '-') ?>
              </div>
              <div class="price">RM <?= number_format((float)$row['Price'], 2) ?></div>
          </li>
      <?php endwhile; ?>
      </ul>
  <?php endif; ?>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

