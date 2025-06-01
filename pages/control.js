const drinkDetails = {
        'Espresso': {
            caffeine: 'High',
            size: '2oz',
            temp: 'Very Hot',
            prepTime: '2-3 minutes'
        },
        'Cappuccino': {
            caffeine: 'Medium',
            size: '8oz',
            temp: 'Hot',
            prepTime: '4-5 minutes'
        },
        'Latte': {
            caffeine: 'Medium',
            size: '12oz',
            temp: 'Hot',
            prepTime: '4-6 minutes'
        },
        'Iced Latte': {
            caffeine: 'Medium',
            size: '16oz',
            temp: 'Cold',
            prepTime: '3-5 minutes'
        },
        'Herbal Tea': {
            caffeine: 'None',
            size: '12oz',
            temp: 'Hot',
            prepTime: '5-7 minutes'
        },
        'Matcha Latte': {
            caffeine: 'Low',
            size: '12oz',
            temp: 'Warm',
            prepTime: '5-6 minutes'
        }
    };

function showDrinkDetails(name, description, price, imageUrl) {
        // Set basic drink info
        document.getElementById('popupDrinkName').textContent = name;
        document.getElementById('popupDrinkDescription').textContent = description;
        document.getElementById('popupDrinkPrice').textContent = price;
        document.getElementById('popupDrinkImage').src = imageUrl;
        
        // Set characteristics from our stored data
        const details = drinkDetails[name];
        if (details) {
            document.getElementById('caffeineLevel').textContent = details.caffeine;
            document.getElementById('drinkSize').textContent = details.size;
            document.getElementById('drinkTemp').textContent = details.temp;
            document.getElementById('prepTime').textContent = details.prepTime;
        }
        
        // Show popup with animation
        document.getElementById('drinkPopup').style.display = 'flex';
    }
    
    function closePopup() {
        document.getElementById('drinkPopup').style.display = 'none';
    }
    
    function addToCart() {
        const drinkName = document.getElementById('popupDrinkName').textContent;
        const drinkPrice = document.getElementById('popupDrinkPrice').textContent;
        
        // Here you would normally send this to your cart system
        console.log(`Added to cart: ${drinkName} (${drinkPrice})`);
        
        // For now, just show a confirmation
        alert(`${drinkName} added to cart!`);
        
        // Close popup
        closePopup();
        
        // In a real implementation, you would update the cart counter here
        // updateCartCounter();
    }

    // tzid lel panier


    function addToCart() {
    const drinkName = document.getElementById('popupDrinkName').textContent;
    const drinkPrice = document.getElementById('popupDrinkPrice').textContent;
    const priceValue = parseFloat(drinkPrice.replace('$', ''));
    
    // Update cart count
    const cartCount = document.querySelector('.cart-count');
    let currentCount = parseInt(cartCount.textContent) || 0;
    cartCount.textContent = currentCount + 1;
    
    // Update cart total price
    const cartPrice = document.querySelector('.cart-prix');
    let currentTotal = parseFloat(cartPrice.textContent.replace('DNT', '').trim()) || 0;
    const newTotal = currentTotal + priceValue;
    cartPrice.textContent = newTotal.toFixed(2) + ' DNT';
    
    // Show confirmation
    alert(`${drinkName} added to cart!`);
    
    // Close popup
    closePopup();
    
    // You can also store this in localStorage for persistence
    localStorage.setItem('cartCount', cartCount.textContent);
    localStorage.setItem('cartTotal', newTotal.toFixed(2));
}
function addToCart() {
    const drinkName = document.getElementById('popupDrinkName').textContent;
    const drinkPrice = parseFloat(document.getElementById('popupDrinkPrice').textContent.replace('$', ''));
    
    // Get or create cart
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if item already exists in cart
    const existingItem = cart.find(item => item.name === drinkName);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: drinkName,
            price: drinkPrice,
            quantity: 1
        });
    }
    
    // Save cart
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count
    const cartCount = document.querySelector('.cart-count');
    let currentCount = parseInt(cartCount.textContent) || 0;
    cartCount.textContent = currentCount + 1;
    
    // Update cart total price
    const cartPrice = document.querySelector('.cart-prix');
    let currentTotal = parseFloat(cartPrice.textContent.replace('DNT', '').trim()) || 0;
    const newTotal = currentTotal + drinkPrice;
    cartPrice.textContent = newTotal.toFixed(2) + ' DNT';
    
    // Save to localStorage
    localStorage.setItem('cartCount', cartCount.textContent);
    localStorage.setItem('cartTotal', newTotal.toFixed(2));
    
    // Show confirmation
    alert(`${drinkName} added to cart!`);
    
    // Close popup
    closePopup();
}

    