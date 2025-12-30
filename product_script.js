let allProducts = []; 
let visibleCount = 6; 
let currentFilteredList = [];

// Fetch products from your PHP API
async function fetchProducts() {
    const grid = document.getElementById('productGrid');
    try {
        const response = await fetch('get_products.php');
        const data = await response.json();
        allProducts = data;
        currentFilteredList = [...allProducts];
        filterProducts(); 
    } catch (error) {
        console.error("Fetch error:", error);
        grid.innerHTML = "<p>Could not connect to database.</p>";
    }
}

// Render the product cards into the grid
function renderProducts(list) {
    const grid = document.getElementById('productGrid');
    grid.innerHTML = ""; 
    document.getElementById('itemCount').innerText = `Showing ${Math.min(visibleCount, list.length)} of ${list.length} Products`;

    list.slice(0, visibleCount).forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.innerHTML = `
            <div class="card-image"><span class="brand-tag">${product.brand}</span></div>
            <div class="card-info">
                <div class="info-row">
                    <span class="p-name">${product.name}</span>
                    <span class="p-size">${product.size || ''}</span>
                </div>
                <div class="p-price">RM ${product.price}</div>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Handle checkbox and price slider filtering
function filterProducts() {
    const brands = Array.from(document.querySelectorAll('.brand-filter:checked')).map(cb => cb.value);
    const price = document.getElementById('priceRange').value;
    document.getElementById('priceLabel').innerText = `Max: RM ${price}`;

    currentFilteredList = allProducts.filter(p => brands.includes(p.brand) && p.price <= price);
    renderProducts(currentFilteredList);
}

// Sort products by price
function sortProducts() {
    const val = document.getElementById('sortSelect').value;
    if (val === 'low') currentFilteredList.sort((a, b) => a.price - b.price);
    if (val === 'high') currentFilteredList.sort((a, b) => b.price - a.price);
    renderProducts(currentFilteredList);
}

// Show more products
function loadMore() { 
    visibleCount += 3; 
    renderProducts(currentFilteredList); 
}

// Reset all filters to default
function clearFilters() {
    document.querySelectorAll('.brand-filter').forEach(cb => cb.checked = true);
    document.getElementById('priceRange').value = 250;
    filterProducts();
}

// Initialize when the page finishes loading
window.onload = fetchProducts;