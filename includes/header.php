<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bella Vista - Coffee & Restaurant</title>
    <link rel="stylesheet" href="style.css">
    <script src="control.js"></script>
    <script>
        // Load cart data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const savedCount = localStorage.getItem('cartCount');
            const savedTotal = localStorage.getItem('cartTotal');
            
            if (savedCount) {
                document.querySelector('.cart-count').textContent = savedCount;
            }
            
            if (savedTotal) {
                document.querySelector('.cart-prix').textContent = savedTotal + ' DNT';
            }
        });
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <a href="index.php" class="logo-link">
                        <div class="logo">BV</div>
                        <div class="logo-text">Bella Vista</div>
                    </a>
                </div>
                <div class="nav-links">
                    <a href="about.php">About Us</a>
                    <a href="contact.php">Contact</a>
                   <div class="cart-icon">
                    <a href="panier.php">
                     <i class="fas fa-utensils"></i> <!-- Updated from fa-shopping-cart -->
                     <div class="cart-count">0</div>
                    </div>
                    <span class="cart-prix">0 DNT</span></a>

                    </a>
                </div>
            </div>
        </div>
    </header>