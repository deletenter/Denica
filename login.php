<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Denica - Login</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-card {
      background: white;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    .warning-banner {
      background-color: #fdecea;
      color: #b71c1c;
      border: 1px solid #f5c6cb;
      padding: 12px;
      border-radius: 6px;
      font-size: 14px;
      margin-bottom: 15px;
      text-align: left;
    }
    .form-group { margin-bottom: 15px; text-align: left; }
    .form-group label { display: block; margin-bottom: 5px; font-size: 14px; }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    .login-btn {
      width: 100%;
      padding: 10px;
      background-color: #000;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 10px;
    }
    .footer-links { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
    .footer-links a { display: inline-block; margin: 6px 0; }
  </style>
</head>
<body>

  <div class="login-card">
    <h2>Customer Login</h2>

    <?php if ($error): ?>
      <div class="warning-banner"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="email@example.com" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="footer-links">
      <p>Still not have an account?</p>
      <a href="registration.html">Register Here</a>

      <p style="margin-top: 15px;">Staff access only?</p>
      <a href="admin/admin_login.html" style="color:#d9534f; font-weight:bold;">Admin Login</a>
    </div>
  </div>

</body>
</html>


