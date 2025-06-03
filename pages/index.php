<?php include 'C:\xampp\htdocs\bellavista\includes\header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Experience the Finest Coffee & Cuisine</h1>
        <p>Authentic flavors crafted with passion and the finest ingredients</p>
        <div class="hero-buttons">
            <a href="#categories" class="btn">Explore Our Menu</a>
            <a href="reservation.php" class="btn btn-reservation">Make Reservation</a>
        </div>
    </div>
</section>

<section id="categories" class="categories">
    <div class="container">
        <h2 class="section-title">Our Menu Categories</h2>
        <p class="section-subtitle">Discover our wide selection of premium beverages and delicious food options</p>
        
        <div class="category-grid">
            <!-- Drinks Category -->
            <div class="category-card">
                <div class="category-img">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&w=600" alt="Coffee Drinks">
                </div>
                <div class="category-content">
                    <h3 class="category-title">Drinks</h3>
                    <p class="category-desc">Artisanal coffees, teas, and specialty beverages</p>
                    <a href="drinks.php" class="btn">View Selection</a>
                </div>
            </div>
            
            <!-- Fast Food Category -->
            <div class="category-card">
                <div class="category-img">
                    <img src="https://images.unsplash.com/photo-1551782450-a2132b4ba21d?ixlib=rb-4.0.3&w=600" alt="Fast Food">
                </div>
                <div class="category-content">
                    <h3 class="category-title">Fast Food</h3>
                    <p class="category-desc">Quick bites, sandwiches, and savory snacks</p>
                    <a href="fastfood.php" class="btn">View Selection</a>
                </div>
            </div>
            
            <!-- Sugar Food Category -->
            <div class="category-card">
                <div class="category-img">
                    <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-4.0.3&w=600" alt="Desserts">
                </div>
                <div class="category-content">
                    <h3 class="category-title">Sugar Food</h3>
                    <p class="category-desc">Decadent pastries, cakes, and sweet treats</p>
                    <a href="sugarfood.php" class="btn">View Selection</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>