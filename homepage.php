<?php
    session_start();
    $profileLink = isset($_SESSION['customer_id']) ? 'profile.php' : 'registration.html';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Denica | Proudly Malaysian</title>
        <link rel="stylesheet" href="homepage.css">
    </head>
    <body>

    <header class="navbar">
        <div class="nav-links">
            <a href="homepage.php" class="logo">Denica</a>
            <a href="#">Perfumes</a>
            <a href="#">Discovery</a>
            <a href="#">About</a>

            <span class="search-icon">🔍 Search</span>
        </div>

        <div class="nav-icons">
        <a href="cart.html" aria-label="Open cart" style="text-decoration:none;">
            <span>👜</span>
        </a>

            <a href="cart.php" aria-label="Open profile" style="text-decoration:none;">
                <span style="cursor:pointer;">👤</span>
            </a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Proudly Malaysian.</h1>
            <p>Discover and shop curated local fragrances — all in one place.</p>
            <button class="btn-shop">Shop All</button>
        </div>

        <div class="hero-image-container">
            <div class="image-placeholder"></div>
        </div>

        <div class="brand-logos">
            <div class="brand">MÉDIN</div>
            <div class="brand">SZINDORE</div>
            <div class="brand">THE TOXIC LAB</div>
        </div>
    </section>

</body>
</html>
