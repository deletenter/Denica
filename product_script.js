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

function renderProducts(list) {
    const grid = document.getElementById('productGrid');
    grid.innerHTML = ""; 
    document.getElementById('itemCount').innerText = `Showing ${Math.min(visibleCount, list.length)} of ${list.length} Products`;

    list.slice(0, visibleCount).forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';


    card.innerHTML = `
        <a href="product_detail.php?id=${product.id}" class="card-link">
            <div class="card-image">
                <img src="${product.image}" alt="${product.name}">
                </div>
            <div class="card-text">
                <span class="card-brand">${product.brand}</span>
                <h3 class="card-title">${product.name}</h3>
                <div class="card-meta">
                    <span class="card-price">RM ${parseFloat(product.price).toFixed(2)}</span>
                    <span class="card-size">${product.size || '15ML'}</span>
                </div>
            </div>
        </a>
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

/* --- AUTO-FILTER FROM URL (Footer Links) --- */
document.addEventListener("DOMContentLoaded", function() {
    // 1. Get the brand from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const brandToSelect = urlParams.get('brand');

    // 2. If a brand exists in the URL...
    if (brandToSelect) {
        
        // --- FIX STARTS HERE ---
        // A. Uncheck ALL boxes first so we isolate the selected brand
        const allCheckboxes = document.querySelectorAll('.brand-filter');
        allCheckboxes.forEach(box => {
            box.checked = false;
        });
        // --- FIX ENDS HERE ---

        // B. Find the specific checkbox (Now matches "Medin" or "The Toxic Lab")
        // We use quotes around value to handle spaces in "The Toxic Lab"
        const targetCheckbox = document.querySelector(`input[value="${brandToSelect}"]`);

        // C. Check ONLY that box and trigger filter
        if (targetCheckbox) {
            targetCheckbox.checked = true;
            
            if (typeof filterProducts === "function") {
                filterProducts(); 
            }
        }
    }
});
