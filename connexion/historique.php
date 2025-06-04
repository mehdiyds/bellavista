<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes - Bella Vista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-error {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historique des Commandes</h1>
        
        <?php
        // Activer le rapport d'erreurs
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Connexion à la base de données
        try {
            $conn = new mysqli('localhost', 'root', '', 'bellavista');
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            // Définir le charset
            $conn->set_charset("utf8mb4");
            
            // Requête pour récupérer l'historique des commandes avec les informations client
            $sql = "SELECT 
                        h.historique_id,
                        h.commande_id,
                        c.nom AS client,
                        c.telephone,
                        h.date_commande,
                        h.montant_total,
                        h.montant_paye,
                        h.reste,
                        h.commande,
                        h.date_archivage
                    FROM historique_commandes h
                    JOIN clients c ON h.client_id = c.client_id
                    ORDER BY h.date_archivage DESC";
            
            $result = $conn->query($sql);
            
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
        ?>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Historique</th>
                        <th>ID Commande</th>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Date Commande</th>
                        <th>Montant Total</th>
                        <th>Montant Payé</th>
                        <th>Reste</th>
                        <th>Commande</th>
                        <th>Date Archivage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['historique_id']) ?></td>
                            <td><?= htmlspecialchars($row['commande_id']) ?></td>
                            <td><?= htmlspecialchars($row['client']) ?></td>
                            <td><?= htmlspecialchars($row['telephone']) ?></td>
                            <td><?= htmlspecialchars($row['date_commande']) ?></td>
                            <td><?= number_format($row['montant_total'], 2, '.', '') ?> DT</td>
                            <td><?= number_format($row['montant_paye'], 2, '.', '') ?> DT</td>
                            <td><?= number_format(abs($row['reste']), 2, '.', '') ?> DT</td>
                            <td><?= htmlspecialchars($row['commande'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['date_archivage']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-error">Aucune commande trouvée dans l'historique.</div>
        <?php endif; ?>
        
        <a href="admin.php" class="back-btn">Retour à l'administration</a>
        
        <?php
            $conn->close();
        } catch (Exception $e) {
            echo '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
</body>
</html>