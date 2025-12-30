<?php
session_start();

// Get error message (if any)
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Denica - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f4f4;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:0;
        }
        .login-card{
            width:350px;
            background:#fff;
            padding:40px;
            border-radius:8px;
            box-shadow:0 4px 10px rgba(0,0,0,.1);
            text-align:center;
        }
        .warning-banner{
            background:#fdecea;
            color:#b71c1c;
            border:1px solid #f5c6cb;
            padding:12px;
            border-radius:6px;
            margin-bottom:15px;
            font-size:14px;
        }
        .form-group{
            margin-bottom:15px;
            text-align:left;
        }
        label{
            font-size:14px;
            display:block;
            margin-bottom:5px;
        }
        input{
            width:100%;
            padding:10px;
            border:1px solid #ddd;
            border-radius:4px;
        }
        .login-btn{
            width:100%;
            padding:10px;
            background:#000;
            color:#fff;
            border:none;
            border-radius:4px;
            cursor:pointer;
            margin-top:10px;
        }
        .footer-links{
            margin-top:25px;
            border-top:1px solid #ddd;
            padding-top:20px;
            font-size:13px;
        }
        a{ color:#000; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Customer Login</h2>

    <?php if ($error): ?>
        <div class="warning-banner">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="footer-links">
        <p>Still not have an account?</p>
        <a href="registration.php">Register Here</a>

        <p style="margin-top:15px;">Staff access only?</p>
        <a href="admin/admin_login.html" style="color:#d9534f;">Admin Login</a>
    </div>
</div>

</body>
</html>

