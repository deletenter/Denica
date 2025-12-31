<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile - Denica</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
    .wrap { max-width: 800px; margin: 40px auto; background:#fff; padding: 24px; border-radius: 10px; }
    .top { display:flex; justify-content:space-between; align-items:center; }
    .name { font-size: 22px; font-weight: 700; }
    .muted { color:#666; }
    a.btn { background:#000; color:#fff; padding:10px 14px; border-radius:6px; text-decoration:none; }
  </style>
</head>
<body>

  <div class="wrap">
    <div class="top">
      <div>
        <div class="name"><?= htmlspecialchars($_SESSION['customer_name']) ?></div>
        <div class="muted">Customer ID: <?= (int)$_SESSION['customer_id'] ?></div>
      </div>
      <div>
        <a class="btn" href="logout.php">Logout</a>
      </div>
    </div>

    <hr style="margin:20px 0;">

    <p>This is your profile page. You can add order history, reviews, and settings here.</p>
  </div>

</body>
</html>





