<?php
session_start();

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$profile_link = isset($_SESSION['user_id']) ? "profile.php" : "login.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denica Perfumery | Proudly Malaysian</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <a href="quiz/discovery.php">Discovery</a>
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
                <a href="<?php echo $profile_link; ?>" class="profile-link">
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="hero-section">
            <h1>Proudly Malaysian.</h1> 
            <p class="subtitle">Discover and shop curated local fragrances — all in one place.</p>
            
            <div class="hero-action">
               <button class="btn-outline" onclick="window.location.href='products.php';">
                 Shop All
              </button>
            </div>

            <div class="hero-image-placeholder">
              <img src="Assets/perfumery.jpg" alt="Denica Perfumery Image">
            </div>

            <div class="brand-logo-grid">
                <div class="logo-item"><img src="Assets/medin_logo.jpg" alt="Médin Fragrance"></div>
                <div class="logo-item"><img src="Assets/szindore_logo.png" alt="Szindore"></div>
                <div class="logo-item"><img src="Assets/toxiclab.png" alt="The Toxic Lab"></div>
            </div>
        </section>

        <section class="about-us">
            <div class="about-header-bar">
                <h2>About Us</h2>
            </div>
            
            <div class="about-content">
                <p>
                    Denica Perfumery is where discovering your perfect scent feels effortless. We curate authentic local 
                    perfumes in one refined space, making it easy to explore, match, and shop fragrances that truly reflect 
                    you.
                </p>
                <p>
                    Finding perfume online doesn't have to be complicated. With personalized scent matching, discovery 
                    tools, and trusted reviews, Denica guides you gently toward fragrances you'll love — no guessing, no 
                    overwhelm.
                </p>
                <p>
                    Whether you're searching for a signature scent or exploring something new, Denica offers a curated 
                    experience where luxury meets ease — thoughtfully selected, quietly confident, and effortlessly 
                    Malaysian.
                </p>
                
                <div class="about-footer">
                    <button class="btn-outline" onclick="window.location.href='quiz/discovery.php';">
                    Start your journey now
                    </button>
                </div>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-subscribe">
                <h3>Subscribe for exclusivity:</h3>
                <p>Be the first to know about our special offers, new product launches, and events</p>
                <form class="subscribe-form" action="subscribe_handler.php" method="POST">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <button type="submit">Sign Up</button>
                </form>
            </div>

            <div class="footer-links">
                <div class="link-group">
                    <h4>Shop</h4>
                    <ul>
                        <li><a href="products.php?brand=medin">Medin Fragrance</a></li>
                        <li><a href="products.php?brand=szindore">Szindore</a></li>
                        <li><a href="products.php?brand=toxiclab">The Toxic Lab</a></li>
                    </ul>
                </div>

                <div class="link-group">
                    <h4>Help</h4>
                    <ul>
                        <li><a href="order_status.php">Order Status</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <div class="link-group">
                    <h4>Staff</h4>
                    <ul>
                        <li><a href="admin/admin_login.php">Admin Portal</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>