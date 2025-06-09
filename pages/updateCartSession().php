<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script>
        function updateCartSession() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    fetch('update_cart_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cart_data: cart })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'checkout.php'; // Rediriger vers la page de paiement
        } else {
            alert('Error updating cart. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
    </script>
</body>
</html>