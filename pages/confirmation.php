<?php
session_start();

if (!isset($_SESSION['order_details'])) {
    header('Location: form.php');
    exit;
}

$order_details = $_SESSION['order_details'];
$is_new_client = isset($_SESSION['is_new_client']) ? $_SESSION['is_new_client'] : false;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BV Bella Vista - Commande Confirmée</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5dc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .confirmation-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .confirmation-icon {
            font-size: 80px;
            color: #27ae60;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        .confirmation-title {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .confirmation-subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        
        .new-client-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .order-details h3 {
            color: #2c3e50;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }
        
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #2c3e50;
        }
        
        .detail-value {
            flex: 1;
            color: #7f8c8d;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .action-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .home-btn {
            background-color: #8B4513;
            color: white;
        }
        
        .home-btn:hover {
            background-color: #A0522D;
            transform: translateY(-3px);
        }
        
        .order-btn {
            background-color: #3498db;
            color: white;
        }
        
        .order-btn:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
        }
        
        .delivery-message {
            margin-top: 30px;
            color: #7f8c8d;
            font-style: italic;
            text-align: center;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @media (max-width: 768px) {
            .confirmation-container {
                padding: 20px;
                margin: 20px;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
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
                
                <?php if ($is_new_client): ?>
                    <div class="new-client-badge">
                        <i class="fas fa-user-plus"></i> Nouveau client enregistré
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="order-details">
                <h3><i class="fas fa-receipt"></i> Détails de la commande</h3>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-hashtag"></i> Numéro de commande:</div>
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
                    <div class="detail-label"><i class="fas fa-money-bill"></i> Montant payé:</div>
                    <div class="detail-value"><?php echo $order_details['montant_paye']; ?></div>
                </div>
                
                <?php if ($order_details['monnaie_rendue'] !== '0.00 DNT'): ?>
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-exchange-alt"></i> Monnaie rendue:</div>
                    <div class="detail-value"><?php echo $order_details['monnaie_rendue']; ?></div>
                </div>
                <?php endif; ?>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-shopping-basket"></i> Produits commandés:</div>
                    <div class="detail-value"><?php echo $order_details['produits_commandes']; ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label"><i class="fas fa-truck"></i> Statut:</div>
                    <div class="detail-value" style="color: #27ae60; font-weight: bold;">
                        <i class="fas fa-motorcycle"></i> En préparation
                    </div>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="action-btn home-btn">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
            </div>
            
            <div class="delivery-message">
                <i class="fas fa-info-circle"></i> Votre livreur arrivera dans environ 30 minutes!
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>