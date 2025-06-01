<?php include 'C:\xampp\htdocs\bellavista\includes\header.php'; ?>
<section class="categories">
    <div class="container">
        <h2 class="section-title">Our Drinks Menu</h2>
        <p class="section-subtitle">Premium beverages crafted with care</p>
        
        <div class="category-grid">
            <!-- Coffee Classics -->
            <div class="menu-item" onclick="showDrinkDetails('Espresso', 'Strong, concentrated coffee', '$3.50', 'https://cdn.pixabay.com/photo/2018/10/19/16/47/coffee-3759024_640.jpg')">
                <div class="item-img">
                    <img src="https://cdn.pixabay.com/photo/2018/10/19/16/47/coffee-3759024_640.jpg" alt="Espresso">
                </div>
                <div class="item-content">
                    <h3>Espresso</h3>
                    <p>Strong, concentrated coffee</p>
                    <span class="price">3.50DNT</span>
                </div>
            </div>
            
            <div class="menu-item" onclick="showDrinkDetails('Cappuccino', 'Espresso with steamed milk foam', '$4.50', 'https://www.polobar.co.uk/cdn/shop/products/Cappuccino.jpg?v=1671112378&width=1946')">
                <div class="item-img">
                    <img src="https://www.polobar.co.uk/cdn/shop/products/Cappuccino.jpg?v=1671112378&width=1946" alt="Cappuccino">
                </div>
                <div class="item-content">
                    <h3>Cappuccino</h3>
                    <p>Espresso with steamed milk foam</p>
                    <span class="price">4.50DNT</span>
                </div>
            </div>
            
           <div class="menu-item" onclick="showDrinkDetails('Latte', 'Smooth espresso with steamed milk', '$4.75', 'https://media.istockphoto.com/id/183138035/photo/cup-of-latte-coffee-and-spoon-on-gray-counter.jpg?s=612x612&w=0&k=20&c=Iht-hG2bzxiZgpjao6RELKAbw4oG7ujS2wQNkiM2rqU=')">
    <div class="item-img">
        <img src="https://media.istockphoto.com/id/183138035/photo/cup-of-latte-coffee-and-spoon-on-gray-counter.jpg?s=612x612&w=0&k=20&c=Iht-hG2bzxiZgpjao6RELKAbw4oG7ujS2wQNkiM2rqU=" alt="Latte">
    </div>
    <div class="item-content">
        <h3>Latte</h3>
        <p>Smooth espresso with steamed milk</p>
        <span class="price">4.75DNT</span>
    </div>
</div>
            
            <div class="menu-item" onclick="showDrinkDetails('Iced Latte', 'Espresso with chilled milk over ice', '$5.00', 'https://t4.ftcdn.net/jpg/06/53/78/73/360_F_653787364_RSq2W0SuSzTB4G8owzSmkGkEZdy6s4ud.jpg')">
                <div class="item-img">
                    <img src="https://t4.ftcdn.net/jpg/06/53/78/73/360_F_653787364_RSq2W0SuSzTB4G8owzSmkGkEZdy6s4ud.jpg" alt="Iced Latte">
                </div>
                <div class="item-content">
                    <h3>Iced Latte</h3>
                    <p>Espresso with chilled milk over ice</p>
                    <span class="price">5DNT</span>
                </div>
            </div>
            
            <!-- Teas -->
            <div class="menu-item" onclick="showDrinkDetails('Herbal Tea', 'Selection of premium loose-leaf teas', '$3.75', 'https://t4.ftcdn.net/jpg/01/98/93/59/360_F_198935939_rvUXMPDkMfSE66I4tDXG5qu7ghhBZr7H.jpg')">
                <div class="item-img">
                    <img src="https://t4.ftcdn.net/jpg/01/98/93/59/360_F_198935939_rvUXMPDkMfSE66I4tDXG5qu7ghhBZr7H.jpg" alt="Herbal Tea">
                </div>
                <div class="item-content">
                    <h3>Herbal Tea</h3>
                    <p>Selection of premium loose-leaf teas</p>
                    <span class="price">3.7DNT</span>
                </div>
            </div>
            
            <!-- Specialties -->
            <div class="menu-item" onclick="showDrinkDetails('Matcha Latte', 'Japanese green tea with milk', '$5.25', 'https://t4.ftcdn.net/jpg/11/94/69/21/360_F_1194692177_3gh4pLuz0NlbFBNSQu50YhsOw8A1NlhU.jpg')">
                <div class="item-img">
                    <img src="https://t4.ftcdn.net/jpg/11/94/69/21/360_F_1194692177_3gh4pLuz0NlbFBNSQu50YhsOw8A1NlhU.jpg" alt="Matcha Latte">
                </div>
                <div class="item-content">
                    <h3>Matcha Latte</h3>
                    <p>Japanese green tea with milk</p>
                    <span class="price">5.25DNT</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Drink Details Popup -->
<div id="drinkPopup" class="popup-container">
    <div class="popup-content">
        <div class="popup-animation"></div>
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
            <span class="price" id="popupDrinkPrice">$0.00</span>
        </div>
        <div class="popup-footer">
            <button class="cancel-btn" onclick="closePopup()">Cancel</button>
            <button class="add-to-cart" onclick="addToCart()">Add to Cart</button>
        </div>
    </div>
</div>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>



    