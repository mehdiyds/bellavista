<?php
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'livreur') {
    header('Location: login.php');
    exit();
}

// Vérifier que les données de session existent
if (!isset($_SESSION['user']['prenom']) || !isset($_SESSION['user']['nom']) || !isset($_SESSION['user']['telephone'])) {
    die("Erreur: Données de session incomplètes. Veuillez vous reconnecter.");
}

// Connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=bellavista;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Traitement de la marque comme livrée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commande_id'])) {
    $commande_id = $_POST['commande_id'];
    $livreur_id = $_SESSION['user']['id'];
    
    try {
        // Mettre à jour le statut dans la table livraisons
        $stmt = $pdo->prepare("
            UPDATE livraisons 
            SET statut = 'livrée' 
            WHERE commande_id = ? AND livreur_id = ?
        ");
        $stmt->execute([$commande_id, $livreur_id]);
        
        // Mettre à jour le statut dans la table commandes
        $stmt = $pdo->prepare("
            UPDATE commandes 
            SET statut = 'livrée' 
            WHERE commande_id = ?
        ");
        $stmt->execute([$commande_id]);
        
        // Rafraîchir la page
        header("Location: livreur.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour de la livraison: " . $e->getMessage());
    }
}

// Récupérer les livraisons assignées à ce livreur avec les infos clients
$livreur_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("
    SELECT c.*, cl.nom AS client_nom, cl.telephone AS client_telephone, cl.adresse AS client_adresse, l.statut as statut_livraison 
    FROM commandes c
    JOIN livraisons l ON c.commande_id = l.commande_id
    JOIN clients cl ON c.client_id = cl.client_id
    WHERE l.livreur_id = ? AND l.statut != 'livrée'
    ORDER BY c.date_commande DESC
");
$stmt->execute([$livreur_id]);
$livraisons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Livreur - Bella Vista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #2196F3;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .livreur-info {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .livraisons-list {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .btn {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .status-pending {
            color: #FF9800;
            font-weight: bold;
        }
        .status-in-progress {
            color: #2196F3;
            font-weight: bold;
        }
        .logout {
            float: right;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .logout:hover {
            text-decoration: underline;
        }
        .no-data {
            text-align: center;
            color: #666;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Espace Livreur</h1>
        <a href="logout.php" class="logout">Déconnexion</a>
    </div>

    <div class="container">
        <div class="livreur-info">
            <h2>Bonjour <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?></h2>
            <p>Téléphone: <?= htmlspecialchars($_SESSION['user']['telephone']) ?></p>
        </div>
        
        <h2>Livraisons assignées</h2>
        <table class="commandes-table">
            <thead>
                <tr>
                    <th>ID Livraison</th>
                    <th>Commande</th>
                    <th>Client</th>
                    <th>Adresse</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="livraisons-list">
                <?php if (empty($livraisons)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Aucune livraison assignée</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($livraisons as $livraison): ?>
                        <tr>
                            <td><?= htmlspecialchars($livraison['commande_id']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($livraison['date_commande'])) ?></td>
                            <td><?= htmlspecialchars($livraison['client_nom']) ?></td>
                            <td><?= htmlspecialchars($livraison['client_telephone']) ?></td>
                            <td><?= htmlspecialchars($livraison['client_adresse']) ?></td>
                            <td><?= number_format($livraison['montant_total'], 2) ?> DT</td>
                            <td class="<?= $livraison['statut_livraison'] === 'assignée' ? 'status-pending' : 'status-in-progress' ?>">
                                <?= ucfirst($livraison['statut_livraison']) ?>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="commande_id" value="<?= $livraison['commande_id'] ?>">
                                    <button type="submit" class="btn">Livrée</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>