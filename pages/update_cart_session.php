<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_data'])) {
    // Décoder les données JSON du panier
    $cart = json_decode($_POST['cart_data'], true);
    
    // Valider et stocker dans la session
    if (is_array($cart)) {
        $_SESSION['cart'] = $cart;
        // Rediriger vers form.php avec un paramètre de succès
        header('Location: form.php?success=1');
        exit;
    } else {
        echo 'Données de panier invalides';
        exit;
    }
} else {
    echo 'Aucune donnée de panier reçue';
    exit;
}
?>