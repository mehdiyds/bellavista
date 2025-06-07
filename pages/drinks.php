<?php include 'C:\xampp\htdocs\bellavista\includes\header.php'; ?>
<section class="categories">
    <div class="container">
        <h2 class="section-title">Our Drinks Menu</h2>
        <p class="section-subtitle">Premium beverages crafted with care</p>
        
        <div class="category-grid">
            <?php
            // Connexion à la base de données
            $conn = new mysqli('localhost', 'root', '', 'bellavista');
            
            // Vérification de la connexion
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Récupération des produits de la catégorie "drinks"
            $sql = "SELECT * FROM produits WHERE id_cat = (SELECT id_cat FROM categories WHERE nom_cat = 'drinks')";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $caracteristiques = json_decode($row['caracteristiques'], true);
                    ?>
                    <div class="menu-item" onclick="showDrinkDetails(
                        '<?php echo htmlspecialchars($row['nom']); ?>', 
                        '<?php echo htmlspecialchars($row['description']); ?>', 
                        '<?php echo number_format($row['prix'], 2); ?>DNT', 
                        '<?php echo htmlspecialchars($row['image']); ?>',
                        '<?php echo htmlspecialchars($caracteristiques['caffeineLevel'] ?? 'Medium'); ?>',
                        '<?php echo htmlspecialchars($caracteristiques['size'] ?? '8oz'); ?>',
                        '<?php echo htmlspecialchars($caracteristiques['temp'] ?? 'Hot'); ?>',
                        '<?php echo htmlspecialchars($caracteristiques['prepTime'] ?? '3-5 minutes'); ?>'
                    )">
                        <div class="item-img">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['nom']); ?>">
                        </div>
                        <div class="item-content">
                            <h3><?php echo htmlspecialchars($row['nom']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <span class="price"><?php echo number_format($row['prix'], 2); ?>DNT</span>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='no-products'>No drinks available at the moment. Please check back later.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</section>

<!-- Drink Details Popup -->
<div id="drinkPopup" class="popup-container">
    <div class="popup-content">
        <div class="popup-header">
            <h2 id="popupDrinkName">Drink Name</h2>
            <span class="close-popup" onclick="closePopup()">&times;</span>
        </div>
        <div class="popup-body">
            <img id="popupDrinkImage" src="" alt="Drink Image">
            <p id="popupDrinkDescription">Drink description</p>
            <div class="drink-characteristics">
                <h3>Characteristics:</h3>
                <ul>
                    <li>Caffeine Content: <span id="caffeineLevel">Medium</span></li>
                    <li>Size: <span id="drinkSize">8oz</span></li>
                    <li>Temperature: <span id="drinkTemp">Hot</span></li>
                    <li>Preparation Time: <span id="prepTime">3-5 minutes</span></li>
                </ul>
            </div>
            <span class="price" id="popupDrinkPrice">0.00DNT</span>
        </div>
        <div class="popup-footer">
            <button class="cancel-btn" onclick="closePopup()">Cancel</button>
            <button class="add-to-cart" onclick="addToCart()">Add to Cart</button>
        </div>
    </div>
</div>

<script>
// Variable pour stocker le produit actuel
let currentProduct = null;

function showDrinkDetails(name, description, price, image, caffeineLevel = 'Medium', size = '8oz', temp = 'Hot', prepTime = '3-5 minutes') {
    // Mettre à jour le contenu du popup
    document.getElementById('popupDrinkName').textContent = name;
    document.getElementById('popupDrinkDescription').textContent = description;
    document.getElementById('popupDrinkPrice').textContent = price;
    document.getElementById('popupDrinkImage').src = image;
    document.getElementById('popupDrinkImage').alt = name;
    
    // Mettre à jour les caractéristiques
    document.getElementById('caffeineLevel').textContent = caffeineLevel;
    document.getElementById('drinkSize').textContent = size;
    document.getElementById('drinkTemp').textContent = temp;
    document.getElementById('prepTime').textContent = prepTime;
    
    // Afficher le popup
    document.getElementById('drinkPopup').style.display = 'block';
    
    // Stocker les détails du produit pour le panier
    currentProduct = {
        name: name,
        description: description,
        price: parseFloat(price.replace('DNT', '')),
        image: image,
        characteristics: {
            caffeineLevel: caffeineLevel,
            size: size,
            temp: temp,
            prepTime: prepTime
        }
    };
}

function closePopup() {
    document.getElementById('drinkPopup').style.display = 'none';
}

function addToCart() {
    if (!currentProduct) return;
    
    // Récupérer le panier existant ou en créer un nouveau
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Ajouter le produit au panier
    cart.push(currentProduct);
    
    // Sauvegarder le panier
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Fermer le popup et donner un feedback
    closePopup();
    alert(`${currentProduct.name} has been added to your cart!`);
    
    // Mettre à jour le compteur du panier (si vous en avez un)
    updateCartCounter();
}

function updateCartCounter() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const counter = document.getElementById('cart-counter');
    if (counter) {
        counter.textContent = cart.length;
    }
}

// Fermer le popup si on clique en dehors
window.addEventListener('click', function(event) {
    const popup = document.getElementById('drinkPopup');
    if (event.target === popup) {
        closePopup();
    }
});
</script>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>