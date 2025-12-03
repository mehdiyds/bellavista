 <?php
            // Database connection
            $host = 'localhost';
            $dbname = 'bellavista';
            $username = 'root';
            $password = '';
            
try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Récupération de l'ID de commande (par exemple depuis l'URL)
$commande_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête pour obtenir l'état de la commande
$stmt = $conn->prepare("SELECT status FROM livraisons WHERE commande_id = :commande_id");
$stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$status = $result['status'] ?? 'inconnu';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>État de votre commande - Bellavista</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .status-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .status-step {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 5px;
            position: relative;
        }
        .status-step.active {
            background-color: #e8f4fd;
            border-left: 4px solid #3498db;
        }
        .status-step.completed {
            background-color: #e8f8f0;
            border-left: 4px solid #2ecc71;
        }
        .status-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .status-icon.active {
            background-color: #3498db;
            color: white;
        }
        .status-icon.completed {
            background-color: #2ecc71;
            color: white;
        }
        .status-icon.inactive {
            background-color: #ecf0f1;
            color: #7f8c8d;
        }
        .status-text {
            flex-grow: 1;
        }
        .status-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .status-description {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .commande-id {
            text-align: center;
            margin-bottom: 30px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Suivi de votre commande</h1>
        
        <div class="commande-id">
            <strong>Numéro de commande :</strong> #<?php echo htmlspecialchars($commande_id); ?>
        </div>
        
        <div class="status-container">
            <!-- Étape 1: Commande en attente -->
            <div class="status-step <?php echo ($status == 'en attente' || $status == 'en livraison' || $status == 'livree') ? 'completed' : (($status == 'inconnu') ? '' : 'active'); ?>">
                <div class="status-icon <?php echo ($status == 'en attente' || $status == 'en livraison' || $status == 'livree') ? 'completed' : (($status == 'inconnu') ? 'inactive' : 'active'); ?>">
                    <?php echo ($status == 'en attente' || $status == 'en livraison' || $status == 'livree') ? '✓' : '1'; ?>
                </div>
                <div class="status-text">
                    <div class="status-title">Commande en attente</div>
                    <div class="status-description">Votre commande est en cours de traitement par notre équipe.</div>
                </div>
            </div>
            
            <!-- Étape 2: Commande en livraison -->
            <div class="status-step <?php echo ($status == 'en livraison' || $status == 'livree') ? 'completed' : (($status == 'en attente' || $status == 'inconnu') ? '' : 'active'); ?>">
                <div class="status-icon <?php echo ($status == 'en livraison' || $status == 'livree') ? 'completed' : (($status == 'en attente' || $status == 'inconnu') ? 'inactive' : 'active'); ?>">
                    <?php echo ($status == 'en livraison' || $status == 'livree') ? '✓' : '2'; ?>
                </div>
                <div class="status-text">
                    <div class="status-title">Commande en cours de livraison</div>
                    <div class="status-description">Votre commande a été assignée à un livreur et est en route.</div>
                </div>
            </div>
            
            <!-- Étape 3: Commande livrée -->
            <div class="status-step <?php echo ($status == 'livree') ? 'completed' : (($status == 'inconnu' || $status == 'en attente' || $status == 'en livraison') ? '' : 'active'); ?>">
                <div class="status-icon <?php echo ($status == 'livree') ? 'completed' : (($status == 'inconnu' || $status == 'en attente' || $status == 'en livraison') ? 'inactive' : 'active'); ?>">
                    <?php echo ($status == 'livree') ? '✓' : '3'; ?>
                </div>
                <div class="status-text">
                    <div class="status-title">Commande livrée</div>
                    <div class="status-description">Votre commande a été livrée avec succès.</div>
                </div>
            </div>
        </div>
        
        <?php if($status == 'inconnu'): ?>
            <div style="margin-top: 30px; padding: 15px; background-color: #fff4e6; border-radius: 5px; text-align: center;">
                Nous n'avons pas trouvé d'information pour cette commande. Veuillez vérifier le numéro de commande.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>