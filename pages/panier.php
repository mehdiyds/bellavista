<?php 
session_start();
include 'C:\xampp\htdocs\bellavista\includes\header.php'; 

// Synchroniser le panier avec la session PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_data'])) {
    $cartData = json_decode($_POST['cart_data'], true);
    
    // Valider et nettoyer les données du panier
    $_SESSION['cart'] = [];
    if (is_array($cartData)) {
        foreach ($cartData as $item) {
            if (isset($item['id'], $item['name'], $item['price'], $item['quantity']) && 
                is_numeric($item['id']) && is_numeric($item['price']) && is_numeric($item['quantity'])) {
                $cleanItem = [
                    'id' => (int)$item['id'],
                    'name' => htmlspecialchars($item['name']),
                    'price' => (float)$item['price'],
                    'quantity' => (int)$item['quantity'],
                    'image' => isset($item['image']) ? htmlspecialchars($item['image']) : 'uploads/default.jpg',
                    'characteristics' => isset($item['characteristics']) && is_array($item['characteristics']) ? 
                                        $item['characteristics'] : []
                ];
                $_SESSION['cart'][] = $cleanItem;
            }
        }
    }
}
?>

<form id="cartForm" method="post" action="panier.php" style="display: none;">
    <input type="hidden" name="cart_data" id="cartDataInput">
</form>
<style>
    /* === Panier Page Styles === */

/* Ajouter dans la section style */
input[type="number"] {
    width: 60px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

.cart-table tr:hover {
    background-color: #f9f9f9;
}

.cart-table a {
    color: var(--primary-color);
    text-decoration: none;
}

.cart-table a:hover {
    text-decoration: underline;
}













.panier-section {
    padding: 60px 0;
    background-color: var(--light-color);
    min-height: 70vh;
}

.container-panier {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    background-color: var(--white);
    padding: 30px;
    border-radius: 12px;
    box-shadow: var(--shadow);
}

.panier-section .section-title {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 30px;
    font-size: 2.2rem;
}

/* Cart Controls */
.cart-controls {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.clear-cart-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.clear-cart-btn:hover {
    background-color: #c0392b;
    transform: translateY(-2px);
}

/* Cart Table */
.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.cart-table th {
    text-align: left;
    padding: 15px 10px;
    background-color: var(--primary-color);
    color: var(--white);
    font-weight: 600;
}

.cart-table td {
    padding: 15px 10px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.cart-table tr:last-child td {
    border-bottom: none;
}

/* Product Image */
.product-image-cell {
    width: 80px;
}

.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

/* Quantity Controls */
.quantity-controls {
    display: flex;
    align-items: center;
    gap: 5px;
}

.quantity-btn {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background-color: #f8f8f8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s;
}

.quantity-btn:hover {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.quantity {
    min-width: 20px;
    text-align: center;
}

/* Remove Button */
.remove-btn {
    background: none;
    border: none;
    color: #e74c3c;
    font-size: 18px;
    cursor: pointer;
    transition: transform 0.3s;
    padding: 5px;
}

.remove-btn:hover {
    transform: scale(1.2);
}

/* Cart Total */
.cart-total {
    text-align: right;
    margin-bottom: 30px;
    padding-top: 20px;
    border-top: 2px solid var(--secondary-color);
}

.cart-total h3 {
    font-size: 1.5rem;
    color: var(--dark-color);
}

.cart-total span {
    color: var(--accent-color);
    font-weight: 700;
}

/* Cart Actions */
.cart-actions {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.cart-actions .btn {
    flex: 1;
    text-align: center;
    max-width: 300px;
}

.continue-btn {
    background-color: var(--secondary-color);
    color: var(--dark-color);
}

.continue-btn:hover {
    background-color: var(--primary-color);
    color: var(--white);
}

.checkout-btn {
    background-color: var(--accent-color);
    color: var(--white);
    margin-left: auto;
}

.checkout-btn:hover {
    background-color: var(--primary-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-table thead {
        display: none;
    }
    
    .cart-table tr {
        display: block;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--secondary-color);
    }
    
    .cart-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 5px;
        border-bottom: 1px solid #eee;
    }
    
    .cart-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--primary-color);
        margin-right: 15px;
    }
    
    .quantity-controls {
        margin-left: auto;
    }
    
    .cart-actions {
        flex-direction: column;
    }
    
    .cart-actions .btn {
        max-width: 100%;
    }
}

/* Animation for cart actions */
@keyframes cartItemAdded {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.cart-item-added {
    animation: cartItemAdded 0.5s ease;
}
</style>

<section class="panier-section">
    <div class="container-panier">
        <h1 class="section-title">PANIER</h1>
        
        <div class="cart-controls">
            <button id="clearCartBtn" class="clear-cart-btn">Vider le panier</button>
        </div>
        
        <div class="cart-items">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Image</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cartItemsBody">
                    <!-- Cart items will be loaded here by JavaScript -->
                </tbody>
            </table>
            
            <div class="cart-total">
                <h3>Total TTC: <span id="cartTotal">0 DNT</span></h3>
            </div>
            
            <div class="cart-actions">
                <button class="btn continue-btn">Continuer Mes Achats</button>
                <button class="btn checkout-btn">Commander &gt;</button>
            </div>
        </div>
    </div>
</section>

<script>
// Base URL for images
const baseUrl = '<?php echo "http://".$_SERVER['HTTP_HOST']."/bellavista/"; ?>';

document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    
    document.querySelector('.continue-btn').addEventListener('click', function() {
        window.location.href = 'index.php';
    });
    
    document.querySelector('.checkout-btn').addEventListener('click', function() {
        updateCartSession();
    });
    
    document.getElementById('clearCartBtn').addEventListener('click', clearCart);
});

<<<<<<< HEAD
function updateCartSession() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Create dynamic form to send data to server
    const form = document.createElement('form');
    form.method = 'post';
    form.action = 'update_cart_session.php';
    form.style.display = 'none';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'cart_data';
    input.value = JSON.stringify(cart);
    
    form.appendChild(input);
    document.body.appendChild(form);
    
    // Submit the form
    form.submit();
}

=======
>>>>>>> f2bdaf7e42d58031a45b2b1365ac345981a820f5
function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsBody = document.getElementById('cartItemsBody');
    const cartTotalElement = document.getElementById('cartTotal');
    let total = 0;
    
    cartItemsBody.innerHTML = '';
    
    if (cart.length === 0) {
        cartItemsBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    Votre panier est vide. <a href="index.php">Continuer vos achats</a>
                </td>
            </tr>
        `;
        cartTotalElement.textContent = '0.00 DH';
        return;
    }
    
    cart.forEach((item, index) => {
        // Assurer que l'item a toutes les propriétés nécessaires avec des valeurs par défaut
        const safeItem = {
            id: item.id || 0,
            name: item.name || 'Produit sans nom',
            price: parseFloat(item.price) || 0,
            quantity: parseInt(item.quantity) || 1,
            image: item.image ? baseUrl + item.image : baseUrl + 'uploads/default.jpg',
            characteristics: item.characteristics || {}
        };
        
        const itemTotal = safeItem.price * safeItem.quantity;
        total += itemTotal;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td data-label="Produit">${safeItem.name}</td>
            <td data-label="Image" class="product-image-cell">
                <img src="${safeItem.image}" 
                     alt="${safeItem.name}" class="product-image"
                     onerror="this.src='${baseUrl}uploads/default.jpg'">
            </td>
            <td data-label="Prix">${safeItem.price.toFixed(2)} DH</td>
            <td data-label="Quantité">
                <input type="number" min="1" value="${safeItem.quantity}" 
                       onchange="updateQuantity(${index}, this.value)">
            </td>
            <td data-label="Total">${itemTotal.toFixed(2)} DH</td>
            <td data-label="Action">
                <button class="remove-btn" onclick="removeFromCart(${index})">✕</button>
            </td>
<<<<<<< HEAD
            <td>${item.price} DNT</td>
            <td>
                <div class="quantity-controls">
                    <button class="quantity-btn minus" data-index="${index}">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="quantity-btn plus" data-index="${index}">+</button>
                </div>
            </td>
            <td>${itemTotal} DNT</td>
            <td><button class="remove-btn" data-index="${index}">✕</button></td>
=======
>>>>>>> f2bdaf7e42d58031a45b2b1365ac345981a820f5
        `;
        
        if (index === cart.length - 1) {
            row.classList.add('cart-item-added');
        }
        
        cartItemsBody.appendChild(row);
    });
    
    cartTotalElement.textContent = total.toFixed(2) + ' DH';
    updateCartHeader();
}

function updateQuantity(index, newQuantity) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    newQuantity = parseInt(newQuantity);
    
<<<<<<< HEAD
    // Add event listeners to remove buttons
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            removeFromCart(index);
        });
    });
    
    // Add event listeners to quantity buttons
    document.querySelectorAll('.quantity-btn.minus').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            updateQuantity(index, -1);
        });
    });
    
    document.querySelectorAll('.quantity-btn.plus').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            updateQuantity(index, 1);
        });
    });
}

function updateQuantity(index, change) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart[index]) {
        cart[index].quantity += change;
        
        // Remove item if quantity reaches 0
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartHeader(cart);
        loadCartItems();
=======
    if (index >= 0 && index < cart.length && newQuantity > 0) {
        cart[index].quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartItems();
    } else if (newQuantity <= 0) {
        removeFromCart(index);
>>>>>>> f2bdaf7e42d58031a45b2b1365ac345981a820f5
    }
}

function removeFromCart(index) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
<<<<<<< HEAD
    cart.splice(index, 1);
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartHeader(cart);
    loadCartItems();
}

function clearCart() {
    // Show confirmation dialog
    const confirmed = confirm("Êtes-vous sûr de vouloir vider votre panier ? Cette action est irréversible.");
    
    if (confirmed) {
        // Clear all cart data
        localStorage.removeItem('cart');
        
        // Update header
        updateCartHeader([]);
        
        // Reload cart items
        loadCartItems();
        
        // Show visual feedback
        const btn = document.getElementById('clearCartBtn');
        btn.innerHTML = '<i class="fas fa-check"></i> Panier vidé !';
        btn.style.backgroundColor = '#4CAF50';
        
        // Reset button after 2 seconds
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-trash"></i> Vider le panier';
            btn.style.backgroundColor = '#e74c3c';
        }, 2000);
    }
}

function updateCartHeader(cart) {
    // Calculate total count and price
    let count = 0;
    let total = 0;
    
    cart.forEach(item => {
        count += item.quantity;
        total += item.price * item.quantity;
    });
    
    // Update header
    const cartCountElement = document.querySelector('.cart-count');
    const cartPriceElement = document.querySelector('.cart-prix');
    
    if (cartCountElement) cartCountElement.textContent = count;
    if (cartPriceElement) cartPriceElement.textContent = total.toFixed(2) + ' DNT';
    
    // Save to localStorage
    localStorage.setItem('cartCount', count);
    localStorage.setItem('cartTotal', total.toFixed(2));
=======
    if (index >= 0 && index < cart.length) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCartItems();
        updateCartHeader();
    }
}

function clearCart() {
    if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
        localStorage.removeItem('cart');
        loadCartItems();
        updateCartHeader();
    }
}

function updateCartSession() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    document.getElementById('cartDataInput').value = JSON.stringify(cart);
    document.getElementById('cartForm').submit();
}

function updateCartHeader() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    const counter = document.getElementById('cart-counter');
    if (counter) {
        counter.textContent = totalItems;
    }
>>>>>>> f2bdaf7e42d58031a45b2b1365ac345981a820f5
}
</script>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>