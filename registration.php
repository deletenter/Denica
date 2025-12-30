<?php
// Secure session settings (before session_start)
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
// If later you use HTTPS:
// ini_set('session.cookie_secure', '1');

session_start();

// Flash banners (set by customerData.php)
$err = $_SESSION['reg_error'] ?? '';
$msg = $_SESSION['reg_msg'] ?? '';
unset($_SESSION['reg_error'], $_SESSION['reg_msg']);

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Denica - Register</title>

  <style>
    body{
      font-family: Arial, sans-serif;
      background:#f4f4f4;
      display:flex;
      justify-content:center;
      align-items:center;
      height:100vh;
      margin:0;
    }
    .card{
      width:380px;
      background:#fff;
      padding:34px;
      border-radius:10px;
      box-shadow:0 4px 12px rgba(0,0,0,.12);
    }
    h2{ margin:0 0 14px; color:#222; text-align:center; }

    .banner{
      padding:12px;
      border-radius:8px;
      font-size:14px;
      margin-bottom:14px;
      text-align:center;
    }
    .banner.error{
      background:#fdecea;
      color:#b71c1c;
      border:1px solid #f5c6cb;
    }
    .banner.ok{
      background:#e7f6ec;
      color:#1b5e20;
      border:1px solid #b7e0c2;
    }

    .form-group{ margin-bottom:12px; }
    label{ display:block; font-size:13px; margin-bottom:6px; color:#333; }
    input{
      width:100%;
      padding:10px 12px;
      border:1px solid #ddd;
      border-radius:8px;
      box-sizing:border-box;
      outline:none;
    }
    input:focus{ border-color:#111; }

    .error-text{
      display:none;
      margin-top:6px;
      font-size:12px;
      color:#b71c1c;
    }

    .btn{
      width:100%;
      padding:10px;
      margin-top:6px;
      background:#000;
      color:#fff;
      border:none;
      border-radius:8px;
      cursor:pointer;
      font-size:14px;
    }

    .links{
      margin-top:14px;
      font-size:13px;
      text-align:center;
    }
    .links a{ color:#000; text-decoration:none; font-weight:bold; }
  </style>
</head>
<body>

  <div class="card">
    <h2>Create Account</h2>

    <?php if ($err): ?>
      <div class="banner error"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($msg): ?>
      <div class="banner ok"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="customerData.php" method="post" id="registrationForm" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Full Name" maxlength="100" required>
        <div class="error-text" id="nameError">Please enter your full name.</div>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="email@example.com" maxlength="120" required>
        <div class="error-text" id="emailError">Please enter a valid email.</div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Minimum 8 characters" minlength="8" required>
        <div class="error-text" id="passwordError">Password must be at least 8 characters.</div>
      </div>

      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter password" minlength="8" required>
        <div class="error-text" id="confirmPasswordError">Passwords do not match.</div>
      </div>

      <button type="submit" class="btn">Register</button>
    </form>

    <div class="links">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>

<script>
document.getElementById("registrationForm").addEventListener("submit", function (e) {
  let ok = true;

  const name = document.getElementById("name");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirmPassword");

  // Hide all
  document.querySelectorAll(".error-text").forEach(x => x.style.display = "none");

  if (name.value.trim() === "") {
    document.getElementById("nameError").style.display = "block";
    ok = false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email.value.trim())) {
    document.getElementById("emailError").style.display = "block";
    ok = false;
  }

  if (password.value.length < 8) {
    document.getElementById("passwordError").style.display = "block";
    ok = false;
  }

  if (confirmPassword.value === "" || confirmPassword.value !== password.value) {
    document.getElementById("confirmPasswordError").style.display = "block";
    ok = false;
  }

  if (!ok) e.preventDefault();
});
</script>

</body>
</html>
