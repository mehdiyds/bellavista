<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes Annulées - Bella Vista</title>
    <script src="controle.js"></script>
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
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
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
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .statut-annule {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <script>
        function annulerCommande(commandeId) {
    const raison = prompt("Veuillez saisir la raison de l'annulation:");
    
    if (raison !== null) {
        fetch('archive_commande.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `commande_id=${commandeId}&raison=${encodeURIComponent(raison)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Commande annulée et archivée avec succès');
                // Actualiser le tableau des commandes
                location.reload();
            } else {
                alert('Erreur: ' + (data.message || 'Échec de l\'annulation'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}
    </script>
    <div class="container">
        <h1>Historique des Commandes Livrés</h1>
        
        <?php
        // Connexion à la base de données
        try {
            $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Récupération des commandes annulées
            $query = "SELECT * FROM historique ORDER BY date_annulation DESC";
            $stmt = $db->query($query);
            $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($commandes) > 0) {
                echo '<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Commande</th>
                                <th>Montant</th>
                                <th>Payé</th>
                                <th>Reste</th>
                                <th>Date Commande</th>
                                <th>Date livraison</th>
                                <th>Statut avant</th>
                                <th>Raison</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($commandes as $commande) {
                    echo '<tr>
                            <td>'.$commande['commande_id'].'</td>
                            <td>'.$commande['nom_client'].'</td>
                            <td>'.$commande['telephone'].'</td>
                            <td>'.$commande['adresse'].'</td>
                            <td>'.$commande['commande'].'</td>
                            <td>'.$commande['montant_total'].' DT</td>
                            <td>'.$commande['montant_paye'].' DT</td>
                            <td>'.$commande['reste'].' DT</td>
                            <td>'.$commande['date_commande'].'</td>
                            <td>'.$commande['date_annulation'].'</td>
                            <td>'.$commande['statut_avant_annulation'].'</td>
                            <td>'.($commande['raison_annulation'] ?: 'Non spécifiée').'</td>
                          </tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<p class="no-data">Aucune commande annulée trouvée dans l\'historique.</p>';
            }
            
        } catch (PDOException $e) {
            echo '<p class="no-data">Erreur de connexion à la base de données: '.$e->getMessage().'</p>';
        }
        ?>
    </div>
</body>
</html>