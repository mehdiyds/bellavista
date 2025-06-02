<?php
// Récupérer les informations de la commande
session_start();
$order_details = isset($_SESSION['order_details']) ? $_SESSION['order_details'] : [
    'order_id' => '#'.rand(1000, 9999),
    'delivery_time' => 30,
    'customer_name' => 'Mohamed Ali',
    'address' => '123 Avenue Habib Bourguiba, Tunis',
    'total' => '24.75 DNT'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BV Bella Vista - Commande Confirmée</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-content">
        <div class="confirmation-container">
            <div class="confirmation-header">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="confirmation-title">Commande Confirmée!</h1>
                <p class="confirmation-subtitle">Votre commande est en route vers vous!</p>
            </div>
            

        
        



            <div class="delivery-time">
             <i class="fas fa-clock"></i> Temps estimé d'arrivée: 
                <span><?php echo $order_details['delivery_time']; ?> minutes</span>
            </div>
            
            <div class="order-details">
                <h3 style="margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #8B4513; padding-bottom: 10px;">
                    <i class="fas fa-receipt"></i> Détails de la commande
                </h3>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-hashtag"></i> N° de commande:</div>
                    <div class="detail-value"><?php echo $order_details['order_id']; ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-user"></i> Nom du client:</div>
                    <div class="detail-value"><?php echo $order_details['customer_name']; ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-map-marker-alt"></i> Adresse:</div>
                    <div class="detail-value"><?php echo $order_details['address']; ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-money-bill-wave"></i> Montant total:</div>
                    <div class="detail-value"><?php echo $order_details['total']; ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-truck"></i> Statut:</div>
                    <div class="detail-value" style="color: #27ae60; font-weight: bold;">
                        <i class="fas fa-motorcycle"></i> En chemin
                    </div>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="../index.php" class="action-btn home-btn">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
                <a href="../pages/panier.php" class="action-btn order-btn">
                    <i class="fas fa-coffee"></i> Nouvelle commande
                </a>
            </div>
            
            <div style="margin-top: 30px; color: #7f8c8d; font-style: italic;">
                <i class="fas fa-info-circle"></i> Votre livreur arrive bientôt avec votre commande!
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>