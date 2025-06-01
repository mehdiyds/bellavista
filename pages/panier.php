<?php 
session_start();
include 'C:\xampp\htdocs\bellavista\includes\header.php'; 
?>

<section class="panier-section">
    <div class="container">
        <h1 class="section-title">PANIER</h1>
        
        <div class="cart-controls">
            <button id="clearCartBtn" class="clear-cart-btn">Clear Cart</button>
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
// Product images mapping
const productImages = {
    'Espresso': 'https://cdn.pixabay.com/photo/2018/10/19/16/47/coffee-3759024_640.jpg',
    'Cappuccino': 'https://www.polobar.co.uk/cdn/shop/products/Cappuccino.jpg?v=1671112378&width=1946',
    'Latte': 'https://media.istockphoto.com/id/183138035/photo/cup-of-latte-coffee-and-spoon-on-gray-counter.jpg?s=612x612&w=0&k=20&c=Iht-hG2bzxiZgpjao6RELKAbw4oG7ujS2wQNkiM2rqU=',
    'Iced Latte': 'https://t4.ftcdn.net/jpg/06/53/78/73/360_F_653787364_RSq2W0SuSzTB4G8owzSmkGkEZdy6s4ud.jpg',
    'Herbal Tea': 'https://t4.ftcdn.net/jpg/01/98/93/59/360_F_198935939_rvUXMPDkMfSE66I4tDXG5qu7ghhBZr7H.jpg',
    'Matcha Latte': 'https://t4.ftcdn.net/jpg/11/94/69/21/360_F_1194692177_3gh4pLuz0NlbFBNSQu50YhsOw8A1NlhU.jpg'
};

// Load cart from localStorage and update session
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    
    // Continue shopping button
    document.querySelector('.continue-btn').addEventListener('click', function() {
        window.location.href = 'index.php';
    });
    
    // Checkout button
    document.querySelector('.checkout-btn').addEventListener('click', function() {
        // Mettre à jour la session PHP avant de rediriger
        updateCartSession();
    });
    
    // Clear cart button
    document.getElementById('clearCartBtn').addEventListener('click', clearCart);
});

// Mettre à jour la session PHP avec le panier actuel
function updateCartSession() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Créer un formulaire dynamique pour envoyer les données au serveur
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
    
    // Soumettre le formulaire
    form.submit();
}

function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsBody = document.getElementById('cartItemsBody');
    const cartTotalElement = document.getElementById('cartTotal');
    let total = 0;
    
    cartItemsBody.innerHTML = '';
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td class="product-image-cell">
                <img src="${productImages[item.name] || 'https://via.placeholder.com/50'}" alt="${item.name}" class="product-image">
            </td>
            <td>${item.price} DNT</td>
            <td>${item.quantity}</td>
            <td>${itemTotal} DNT</td>
            <td><button class="remove-btn" data-index="${index}">✕</button></td>
        `;
        cartItemsBody.appendChild(row);
    });
    
    cartTotalElement.textContent = total + ' DNT';
    
    // Add event listeners to remove buttons
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            removeFromCart(index);
        });
    });
}

function removeFromCart(index) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const removedItem = cart.splice(index, 1)[0];
    
    // CORRECTION : Ne pas mettre à jour la session ici
    // Juste mettre à jour le localStorage et l'affichage
    
    // Update localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartHeader(cart);
    loadCartItems();
    
    // Show animation
    const button = document.querySelector(`.remove-btn[data-index="${index}"]`);
    if (button) {
        button.textContent = '-1';
        button.style.color = 'red';
        setTimeout(() => {
            button.textContent = '✕';
            button.style.color = '';
        }, 1000);
    }
    
    // CORRECTION : Ne pas appeler updateCartSession() ici
    // On synchronise uniquement au moment du checkout
}

function clearCart() {
    // Clear all cart data
    localStorage.removeItem('cart');
    localStorage.removeItem('cartCount');
    localStorage.removeItem('cartTotal');
    
    // Update header
    document.querySelector('.cart-count').textContent = '0';
    document.querySelector('.cart-prix').textContent = '0 DNT';
    
    // Reload cart items
    loadCartItems();
    
    // Show confirmation
    alert('Cart cleared successfully!');
    
    // CORRECTION : Ne pas appeler updateCartSession() ici
    // On synchronise uniquement au moment du checkout
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
    document.querySelector('.cart-count').textContent = count;
    document.querySelector('.cart-prix').textContent = total.toFixed(2) + ' DNT';
    
    // Save to localStorage
    localStorage.setItem('cartCount', count);
    localStorage.setItem('cartTotal', total.toFixed(2));
}
</script>
<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>