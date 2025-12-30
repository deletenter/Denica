<?php
session_start();

// 1. Check cart count (Defaults to 0 if empty)
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// 2. Dynamic profile link (Login if guest, Profile if logged in)
$profile_link = isset($_SESSION['user_id']) ? "profile.php" : "login.html";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Perfumes - Denica</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <header class="global-header">
        <div class="header-container">
            <a href="index.php" class="brand-identity">Denica</a> 
            
            <nav class="main-nav">
                <a href="products.html">Perfumes</a>
                <a href="quiz/discovery.html">Discovery</a>
            </nav>

            <div class="search-bar">
                <form action="search_handler.php" method="GET" class="search-form">
                    <input type="text" placeholder="Search" name="query" required> 
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="header-utilities">
                <a href="cart.html" class="cart-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count"><?php echo $cart_count; ?></span> 
                </a>
                <a href="<?php echo $profile_link; ?>" class="profile-link">
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
    </header>

    <div class="hero-section">
        <h1>Shop Perfumes</h1>
        <p>Handpicked timeless fragrances for your curated collection.</p>
    </div>

    <div class="container">
        <aside class="sidebar">
            <div class="filter-header">
                <h3>Filters</h3>
                <span class="clear-filters" onclick="clearFilters()">Clear all</span>
            </div>

            <div class="filter-group">
                <h4>Brand</h4>
                <label class="checkbox-item">
                    <input type="checkbox" class="brand-filter" value="Medin" checked onchange="filterProducts()"> Medin
                </label>
                
                <label class="checkbox-item">
                    <input type="checkbox" class="brand-filter" value="Szindore" checked onchange="filterProducts()"> Szindore
                </label>
                
                <label class="checkbox-item">
                    <input type="checkbox" class="brand-filter" value="The Toxic Lab" checked onchange="filterProducts()"> The Toxic Lab
                </label>
            </div>

            <div class="filter-group">
                <h4>Price Limit</h4>
                <input type="range" min="0" max="250" value="250" id="priceRange" oninput="filterProducts()">
                <div id="priceLabel" style="font-size: 0.8rem; margin-top: 5px;">Max: RM 250</div>
            </div>
        </aside>

        <main class="products-area">
            <div class="sort-bar">
                <select class="sort-dropdown" id="sortSelect" onchange="sortProducts()">
                    <option value="popular">Sort By Popular</option>
                    <option value="low">Price: Low to High</option>
                    <option value="high">Price: High to Low</option>
                </select>
                <div class="item-count" id="itemCount">Loading products...</div>
            </div>

            <div class="product-grid" id="productGrid"></div>

            <div class="pagination">
                <button class="load-more-btn" onclick="loadMore()">Load more</button>
            </div>
        </main>
    </div>

    <script src="product_script.js"></script>
</body>
</html>
