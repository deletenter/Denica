<?php
session_start();

// Cart count from session
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Logged-in check
$is_logged_in = isset($_SESSION['customer_id']);
$customer_name = $_SESSION['customer_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | Coming Soon - Denica Perfumery</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom styles for the Coming Soon placeholder */
        .coming-soon-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            padding: 40px 20px;
        }
        .coming-soon-container i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        .coming-soon-container h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="promo-banner">
        END OF SEASON SALE UP TO 40% OFF 
    </div>

    <header class="global-header">
        <div class="header-container">
            <a href="index.php" class="brand-identity">Denica</a> 
            
            <nav class="main-nav">
                <a href="products.php">Perfumes</a>
                <a href="quiz/discovery.html">Discovery</a>
            </nav>

            <div class="search-bar">
                <form action="search_handler.php" method="GET" class="search-form">
                    <input type="text" placeholder="Search" name="query" required> 
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="header-utilities">
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count"><?php echo $cart_count; ?></span> 
                </a>

                <?php if ($is_logged_in): ?>
                    <a href="userprofile.php" class="profile-link" style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($customer_name); ?></span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="profile-link" style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-user-circle"></i>
                        <span>Login</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="coming-soon-container">
            <i class="fas fa-pen-nib"></i>
            <h1>Reviews Coming Soon</h1>
            <p class="subtitle">We are currently gathering feedback from our amazing customers. Stay tuned!</p>
            <div class="hero-action" style="margin-top: 30px;">
               <a href="index.php" style="text-decoration: none;">
                   <button class="btn-outline">Back to Home</button>
               </a>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-subscribe">
                <h3>Subscribe for exclusivity:</h3>
                <form class="subscribe-form" action="subscribe_handler.php" method="POST">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <button type="submit">Sign Up</button>
                </form>
            </div>

            <div class="footer-links">
                <div class="link-group">
                    <h4>Shop</h4>
                    <ul>
                        <li><a href="products.php?brand=Medin">Medin Fragrance</a></li>
                        <li><a href="products.php?brand=Szindore">Szindore</a></li>
                        <li><a href="products.php?brand=The%20Toxic%20Lab">The Toxic Lab</a></li>
                    </ul>
                </div>
                <div class="link-group">
                    <h4>Staff</h4>
                    <ul>
                        <li><a href="admin/admin_login.html">Admin Portal</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>