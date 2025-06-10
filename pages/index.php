<?php 
include 'C:\xampp\htdocs\bellavista\includes\header.php'; 

// Define base URL
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";
?>
<section class="hero">
    <div class="hero-content">
        <h1>Experience the Finest Coffee & Cuisine</h1>
        <p>Authentic flavors crafted with passion and the finest ingredients</p>
        <div class="hero-buttons">
            <a href="#categories" class="btn">Explore Our Menu</a>
            <a href="reservation1.php" class="btn btn-reservation">Make Reservation</a>
        </div>
    </div>
</section>

<section id="categories" class="categories">
    <div class="container">
        <h2 class="section-title">Our Menu Categories</h2>
        <p class="section-subtitle">Discover our wide selection of premium beverages and delicious food options</p>
        
        <div class="categories-grid">
            <?php
            // Database connection
            $host = 'localhost';
            $dbname = 'bellavista';
            $username = 'root';
            $password = '';
            
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Get categories
                $stmt = $pdo->query("SELECT * FROM categories");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $category) {
                    $imagePath = $base_url . $category['image'];
                    echo '
                    <div class="category-card" data-category-id="'.$category['id_cat'].'">
                        <div class="category-image">
                            <img src="'.$imagePath.'" alt="'.$category['nom'].'" 
                                 onerror="this.src=\''.$base_url.'uploads/default.jpg\'">
                        </div>
                        <div class="category-info">
                            <h3>'.$category['nom'].'</h3>
                            <p>'.$category['description'].'</p>
                        </div>
                    </div>';
                }
            } catch (PDOException $e) {
                echo '<p class="error">Error loading categories: '.$e->getMessage().'</p>';
            }
            ?>
        </div>
    </div>
</section>

<section id="products" class="products">
    <div class="container">
        <h2 class="section-title">Our Products</h2>
        <p class="section-subtitle" id="category-name">Select a category to view products</p>
        
        <div class="products-grid" id="products-container">
            <!-- Products will be loaded here dynamically -->
        </div>
    </div>
</section>

<!-- Product Details Popup -->
<div id="productPopup" class="popup-container">
    <div class="popup-content">
        <div class="popup-header">
            <h2 id="popupProductName">Product Name</h2>
            <span class="close-popup" onclick="closePopup()">&times;</span>
        </div>
        <div class="popup-body">
            <img id="popupProductImage" src="" alt="Product Image">
            <p id="popupProductDescription">Product description</p>
            <div class="product-characteristics">
                <h3>Characteristics:</h3>
                <ul id="characteristicsList">
                    <!-- Characteristics will be added dynamically -->
                </ul>
            </div>
            <span class="price" id="popupProductPrice">0.00 DNT</span>
        </div>
        <div class="popup-footer">
            <button class="cancel-btn" onclick="closePopup()">Cancel</button>
            <button class="add-to-cart" onclick="addToCart()">Add to Cart</button>
        </div>
    </div>
</div>

<style>
    .categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.category-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.category-image {
    height: 200px;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-info {
    padding: 20px;
    text-align: center;
}

.category-info h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #333;
}

.category-info p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Styles des produits */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.product-image {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 20px;
}

.product-info h3 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: #333;
    font-weight: 600;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #c8a97e;
}

.loading, .no-products, .error {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    font-size: 1.1rem;
}

.loading {
    color: #888;
}

.no-products {
    color: #666;
}

.error {
    color: #e74c3c;
}

/* Styles de la popup */
.popup-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.popup-content {
    background-color: white;
    width: 90%;
    max-width: 600px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    animation: popupFadeIn 0.3s ease;
}

@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.popup-header {
    padding: 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popup-header h2 {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
}

.close-popup {
    font-size: 1.8rem;
    color: #666;
    cursor: pointer;
    transition: color 0.3s;
}

.close-popup:hover {
    color: #333;
}

.popup-body {
    padding: 20px;
}

.popup-body img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 20px;
}

.popup-body p {
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
}

.product-characteristics {
    margin-bottom: 20px;
}

.product-characteristics h3 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: #333;
}

.product-characteristics ul {
    list-style-type: none;
}

.product-characteristics li {
    margin-bottom: 8px;
    color: #555;
}

.product-characteristics li strong {
    color: #333;
}

.popup-footer {
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 15px;
}

.cancel-btn, .add-to-cart {
    padding: 10px 25px;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.cancel-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
}

.add-to-cart {
    background-color: #2ecc71;
    color: white;
    border: none;
}

.cancel-btn:hover, .add-to-cart:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2.2rem;
    }
    
    .hero p {
        font-size: 1rem;
    }
    
    .categories-grid, .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .popup-content {
        width: 95%;
    }
}

@media (max-width: 480px) {
    .hero-buttons {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
        margin: 0 auto;
    }
    
    .categories-grid, .products-grid {
        grid-template-columns: 1fr;
    }
    
    .popup-footer {
        justify-content: center;
    }
}
/* [All your existing CSS styles remain unchanged] */


.cart-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #2ecc71;
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 15px;
    z-index: 1001;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
}

.cart-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.cart-notification a {
    color: white;
    text-decoration: underline;
    font-weight: bold;
    margin-left: 10px;
}
</style>

<script>
// Global variables
const baseUrl = '<?php echo $base_url; ?>';
let currentProduct = null;
let currentCategoryName = '';

// Show product details
function showProductDetails(product) {
    document.getElementById('popupProductName').textContent = product.nom;
    document.getElementById('popupProductDescription').textContent = product.description;
    document.getElementById('popupProductPrice').textContent = parseFloat(product.prix).toFixed(2) + ' DNT';
    
    const imgElement = document.getElementById('popupProductImage');
    imgElement.src = baseUrl + product.image;
    imgElement.alt = product.nom;
    
    const characteristicsList = document.getElementById('characteristicsList');
    characteristicsList.innerHTML = '';
    
    let characteristics = {};
    try {
        if (product.caracteristiques && product.caracteristiques.startsWith('{')) {
            characteristics = JSON.parse(product.caracteristiques);
            for (const [key, value] of Object.entries(characteristics)) {
                const li = document.createElement('li');
                li.innerHTML = `<strong>${key}:</strong> <span>${value}</span>`;
                characteristicsList.appendChild(li);
            }
        } else {
            const li = document.createElement('li');
            li.textContent = product.caracteristiques || "No characteristics available";
            characteristicsList.appendChild(li);
        }
    } catch (e) {
        console.error("Error parsing characteristics:", e);
        const li = document.createElement('li');
        li.textContent = "Characteristics: " + (product.caracteristiques || "Not specified");
        characteristicsList.appendChild(li);
    }
    
    document.getElementById('productPopup').style.display = 'flex';
    
    currentProduct = {
        id: product.produit_id,
        name: product.nom,
        price: parseFloat(product.prix),
        image: product.image,
        characteristics: characteristics
    };
}

function closePopup() {
    document.getElementById('productPopup').style.display = 'none';
}

function addToCart() {
    if (!currentProduct) return;

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    const existingProductIndex = cart.findIndex(item => item.id === currentProduct.id);

    if (existingProductIndex >= 0) {
        cart[existingProductIndex].quantity += 1;
    } else {
        const productToAdd = {
            id: currentProduct.id,
            name: currentProduct.name,
            price: currentProduct.price,
            image: currentProduct.image,
            characteristics: currentProduct.characteristics || {},
            quantity: 1
        };
        cart.push(productToAdd);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    closePopup();
    showCartNotification(`${currentProduct.name} a été ajouté à votre panier!`);
    updateCartHeader();
}

function showCartNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <span>${message}</span>
        <a href="panier.php">Voir le panier</a>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => document.body.removeChild(notification), 300);
    }, 3000);
}

function updateCartHeader() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity || 0), 0);
    
    const counter = document.querySelector('.cart-count');
    const priceDisplay = document.querySelector('.cart-prix');
    
    if (counter) counter.textContent = totalItems;
    if (priceDisplay) priceDisplay.textContent = totalPrice.toFixed(2) + ' DNT';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category-id');
            currentCategoryName = this.querySelector('h3').textContent;
            
            document.getElementById('category-name').textContent = `Products in ${currentCategoryName} category`;
            fetchProducts(categoryId);
            document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    window.addEventListener('click', function(event) {
        const popup = document.getElementById('productPopup');
        if (event.target === popup) {
            closePopup();
        }
    });
    
    function fetchProducts(categoryId) {
        const productsContainer = document.getElementById('products-container');
        productsContainer.innerHTML = '<div class="loading">Loading products...</div>';
        
        fetch('get_products.php?category_id=' + categoryId)
            .then(response => response.json())
            .then(products => {
                if (products.error) {
                    productsContainer.innerHTML = `<p class="error">${products.error}</p>`;
                    return;
                }
                
                if (products.length > 0) {
                    let html = '';
                    products.forEach(product => {
                        const productImage = baseUrl + product.image;
                        html += `
                        <div class="product-card" onclick="showProductDetails(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                            <div class="product-image">
                                <img src="${productImage}" alt="${product.nom}"
                                     onerror="this.src='${baseUrl}uploads/default.jpg'">
                                <span class="category-tag">${currentCategoryName}</span>
                            </div>
                            <div class="product-info">
                                <h3>${product.nom}</h3>
                                <p class="product-description">${product.description}</p>
                                <div class="product-details">
                                    <span class="price">${parseFloat(product.prix).toFixed(2)} DNT</span>
                                </div>
                            </div>
                        </div>`;
                    });
                    productsContainer.innerHTML = html;
                } else {
                    productsContainer.innerHTML = '<p class="no-products">No products found in this category.</p>';
                }
            })
            .catch(error => {
                productsContainer.innerHTML = '<p class="error">Error loading products. Please try again.</p>';
                console.error('Error:', error);
            });
    }
    
    updateCartHeader();
});
</script>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>