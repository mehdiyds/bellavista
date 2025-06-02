<?php
session_start();

// Récupérer le panier depuis la session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Si le panier est vide, rediriger vers la page panier
if (empty($cart)) {
    header('Location: panier.php');
    exit;
}

// Calculer le total
foreach ($cart as $item) {
    $itemTotal = $item['price'] * $item['quantity'];
    $total += $itemTotal;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BV Bella Vista - Validation de commande</title>
</head>
<style>
    

        
</style>
<body>
    <?php include 'C:\xampp\htdocs\bellavista\includes\header.php'; ?>

    <div class="container">
        <h1 class="section-title">Validation de Commande</h1>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="success-message">
                 Votre panier est prêt.
            </div>
        <?php endif; ?>
        
        <div class="panier-summary">
            <h2 style="padding: 15px; background: #f8f9fa; margin: 0;">Récapitulatif de votre panier</h2>
            <table>
                <thead>
                    <tr>
                        <th class="th_form">Produit</th>
                        <th class="th_form">Image</th>
                        <th class="th_form">Prix</th>
                        <th class="th_form">Quantité</th>
                        <th class="th_form">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $productImages = [
                        'Espresso' => 'https://cdn.pixabay.com/photo/2018/10/19/16/47/coffee-3759024_640.jpg',
                        'Cappuccino' => 'https://www.polobar.co.uk/cdn/shop/products/Cappuccino.jpg?v=1671112378&width=1946',
                        'Latte' => 'https://media.istockphoto.com/id/183138035/photo/cup-of-latte-coffee-and-spoon-on-gray-counter.jpg?s=612x612&w=0&k=20&c=Iht-hG2bzxiZgpjao6RELKAbw4oG7ujS2wQNkiM2rqU=',
                        'Iced Latte' => 'https://t4.ftcdn.net/jpg/06/53/78/73/360_F_653787364_RSq2W0SuSzTB4G8owzSmkGkEZdy6s4ud.jpg',
                        'Herbal Tea' => 'https://t4.ftcdn.net/jpg/01/98/93/59/360_F_198935939_rvUXMPDkMfSE66I4tDXG5qu7ghhBZr7H.jpg',
                        'Matcha Latte' => 'https://t4.ftcdn.net/jpg/11/94/69/21/360_F_1194692177_3gh4pLuz0NlbFBNSQu50YhsOw8A1NlhU.jpg'
                    ];
                    
                    foreach ($cart as $item) {
                        $itemTotal = $item['price'] * $item['quantity'];
                        $image = $productImages[$item['name']] ?? 'https://via.placeholder.com/50';
                        echo '<tr>';
                        echo '<td  class="td_form">' . htmlspecialchars($item['name']) . '</td>';
                        echo '<td  class="td_form"><img src="' . $image . '" alt="' . htmlspecialchars($item['name']) . '" class="product-image"></td>';
                        echo '<td  class="td_form">' . number_format($item['price'], 2) . ' DNT</td>';
                        echo '<td  class="td_form">' . $item['quantity'] . '</td>';
                        echo '<td  class="td_form">' . number_format($itemTotal, 2) . ' DNT</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    
                        <td colspan="4" style="text-align: right; font-weight: bold; padding: 15px;" class="td_form">Total TTC:</td>
                        <td style="font-weight: bold; padding: 15px;" class="td_form"><?php echo number_format($total, 2); ?> DNT</td>
                </tfoot>
            </table>
        </div>


        <div class="form-container">
            <form action="confirmation.php" method="post">
                <div class="form-group">
                    <label for="nom">Nom complet:</label>
                    <input type="text" id="nom" name="nom" required placeholder="Votre nom complet">
                </div>
                
                <div class="form-group">
                    <label for="adresse">Adresse de livraison:</label>
                    <input type="text" id="adresse" name="adresse" required placeholder="Votre adresse complète">
                </div>

                <div class="form-group">
                    <label for="telephone">Numéro de téléphone:</label>
                    <input type="tel" id="telephone" name="telephone" required placeholder="Votre numéro de téléphone">
                </div>

                <div class="radio-group">
                    <label>Méthode de paiement:</label>
                    <div class="radio-option">
                        <input type="radio" id="exact" name="paiement" value="exact" checked>
                        <label for="exact">J'ai le montant exact (<?php echo number_format($total, 2); ?> DNT)</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="plus" name="paiement" value="plus">
                        <label for="plus">J'ai un montant plus grand que le total</label>
                    </div>
                </div>

                <div id="montant-group" class="form-group">
                    <label for="montant">Montant que vous avez:</label>
                    <input type="number" id="montant" name="montant" min="<?php echo $total; ?>" step="0.01" value="<?php echo $total; ?>" placeholder="Entrez le montant en DNT">
                    <small>Le montant doit être supérieur ou égal à <?php echo number_format($total, 2); ?> DNT</small>
                </div>

                <!-- Champ caché pour envoyer le total -->
                <input type="hidden" name="total" value="<?php echo $total; ?>">
                
                <!-- Champ caché pour envoyer les articles du panier -->
                <input type="hidden" name="cart_items" value="<?php echo htmlspecialchars(json_encode($cart)); ?>">

                <button type="submit" class="button_commander">Valider la commande</button>
                
            </form>
        </div>
    </div>
    <?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exactRadio = document.getElementById('exact');
            const plusRadio = document.getElementById('plus');
            const montantGroup = document.getElementById('montant-group');
            const total = <?php echo $total; ?>;

            function toggleMontantField() {
                if (plusRadio.checked) {
                    montantGroup.style.display = 'block';
                    document.getElementById('montant').min = total;
                    document.getElementById('montant').value = total;
                } else {
                    montantGroup.style.display = 'none';
                }
            }

            exactRadio.addEventListener('change', toggleMontantField);
            plusRadio.addEventListener('change', toggleMontantField);

            // Initial state
            toggleMontantField();
            
            // Validation du formulaire
            document.querySelector('form').addEventListener('submit', function(e) {
                if (plusRadio.checked) {
                    const montant = parseFloat(document.getElementById('montant').value);
                    if (montant < total) {
                        alert('Le montant doit être supérieur ou égal au total de la commande.');
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>
</html>