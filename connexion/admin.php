<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Gestion des Commandes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .action-panel {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .commandes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .commandes-table th, .commandes-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .commandes-table th {
            background-color: blue;
            color: white;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-delivered {
            color: #27ae60;
            font-weight: bold;
        }
        
        /* Styles des boutons */
        button, .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        button:hover, .btn:hover {
            opacity: 0.9;
        }
        .validate-btn {
            background-color: #e74c3c; /* Rouge */
        }
        .stat-btn {
            background-color: #3498db; /* Bleu */
        }
        .sup-btn{
            background-color: red;
        }
        .assign-btn {
            background-color: #2ecc71; /* Vert */
        }
        .add-btn {
            background-color: #9b59b6; /* Violet */
        }
        .category-btn {
            background-color: #e67e22; /* Orange */
        }
        .product-btn {
            background-color: #1abc9c; /* Turquoise */
        }
        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        /* Groupes de boutons */
        .button-group {
            display: flex;
            gap: 10px;
            margin-right: 20px;
            align-items: center;
        }
        .button-group:not(:last-child) {
            border-right: 1px solid #ddd;
            padding-right: 20px;
        }
        .button-group label {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes - Espace Admin</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Traitement de la validation de livraison
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate_delivery'])) {
            try {
                $conn = new mysqli('localhost', 'root', '', 'bellavista');
                
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }
                
                $conn->autocommit(FALSE); // Désactive l'autocommit pour la transaction
                
                $commande_ids = $_POST['commande_ids'];
                
                foreach ($commande_ids as $commande_id) {
                    // 1. Récupérer les détails de la commande
                    $stmt = $conn->prepare("SELECT c.*, cl.nom AS client_nom 
                                          FROM commandes c 
                                          JOIN clients cl ON c.client_id = cl.client_id
                                          WHERE c.commande_id = ?");
                    $stmt->bind_param("i", $commande_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur récupération commande: " . $stmt->error);
                    }
                    $commande = $stmt->get_result()->fetch_assoc();
                    $stmt->close();
                    
                    if (!$commande) {
                        throw new Exception("Commande $commande_id introuvable");
                    }
                    
                    // 2. Récupérer les détails des produits de la commande
                    $stmt = $conn->prepare("SELECT dc.*, p.nom AS produit_nom 
                                          FROM details_commandes dc
                                          JOIN produits p ON dc.produit_id = p.produit_id
                                          WHERE dc.commande_id = ?");
                    $stmt->bind_param("i", $commande_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur récupération détails commande: " . $stmt->error);
                    }
                    $details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    
                    // 3. Créer une description détaillée de la commande
                    $commande_description = [];
                    foreach ($details as $detail) {
                        $commande_description[] = $detail['quantite'] . " " . $detail['produit_nom'] . " (" . $detail['prix_unitaire'] . " DT)";
                    }
                    $commande_text = implode(" + ", $commande_description);
                    
                    // 4. Insérer dans l'historique
                    $stmt = $conn->prepare("INSERT INTO historique_commandes 
                                          (commande_id, client_id, date_commande, montant_total, 
                                           montant_paye, statut, notes, commande)
                                          VALUES (?, ?, ?, ?, ?, 'livrée', ?, ?)");
                    $stmt->bind_param("iisddss", 
                        $commande['commande_id'],
                        $commande['client_id'],
                        $commande['date_commande'],
                        $commande['montant_total'],
                        $commande['montant_paye'],
                        $commande['notes'],
                        $commande_text
                    );
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur insertion historique: " . $stmt->error);
                    }
                    $stmt->close();
                    
                    // 5. Supprimer les détails de la commande
                    $stmt = $conn->prepare("DELETE FROM details_commandes WHERE commande_id = ?");
                    $stmt->bind_param("i", $commande_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur suppression détails commande: " . $stmt->error);
                    }
                    $stmt->close();
                    
                    // 6. Supprimer les livraisons associées
                    $stmt = $conn->prepare("DELETE FROM livraisons WHERE commande_id = ?");
                    $stmt->bind_param("i", $commande_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur suppression livraison: " . $stmt->error);
                    }
                    $stmt->close();
                    
                    // 7. Supprimer la commande
                    $stmt = $conn->prepare("DELETE FROM commandes WHERE commande_id = ?");
                    $stmt->bind_param("i", $commande_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur suppression commande: " . $stmt->error);
                    }
                    $stmt->close();
                }
                
                $conn->commit();
                echo '<div class="alert alert-success">Commandes validées avec succès. Rafraîchissement...</div>';
                echo '<script>setTimeout(() => location.reload(), 1500);</script>';
                
            } catch (Exception $e) {
                $conn->rollback();
                echo '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
            } finally {
                $conn->close();
            }
        }

        // Affichage des commandes
        try {
            $conn = new mysqli('localhost', 'root', '', 'bellavista');
            $conn->set_charset("utf8mb4");
            
            $sql = "SELECT c.*, cl.nom AS client, cl.telephone, cl.adresse 
                    FROM commandes c JOIN clients cl ON c.client_id = cl.client_id
                    ORDER BY c.date_commande DESC";
            $result = $conn->query($sql);
        ?>
        
        <form method="post">
            <table class="commandes-table">
                <thead>
                    <tr>
                        <th>Sélection</th>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Commande</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="commande_ids[]" value="<?= $row['commande_id'] ?>"></td>
                            <td><?= $row['commande_id'] ?></td>
                            <td><?= htmlspecialchars($row['client']) ?></td>
                            <td><?= htmlspecialchars($row['telephone']) ?></td>
                            <td><?= htmlspecialchars($row['adresse']) ?></td>
                            <td><?= htmlspecialchars($row['commande'] ?? '') ?></td>
                            <td><?= number_format($row['montant_total'], 2) ?> DT</td>
                            <td class="<?= $row['statut'] === 'livrée' ? 'status-delivered' : '' ?>">
                                <?= $row['statut'] ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: red; font-weight: bold;">
                                Aucune commande trouvée
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="action-panel">
                <div class="button-group">
                    <label>Livreur:</label>
                    <select id="livreur-select">
    <option value="">-- Sélectionner --</option>
    <?php 
    $livreurs = $conn->query("SELECT livreur_id, nom, statut FROM livreurs WHERE statut != 'indisponible' ORDER BY nom");
    while($livreur = $livreurs->fetch_assoc()): ?>
        <option value="<?= $livreur['livreur_id'] ?>">
            <?= htmlspecialchars($livreur['nom']) ?> 
            (<?= $livreur['statut'] ?>)
        </option>
    <?php endwhile; ?>
</select>
                    <button type="button" id="assign-btn" class="assign-btn">Assigner</button>
                </div>
                    <div class="button-group">
                    <a href="gestion_tables.php"><button type="button" class="stat-btn">Gérer les Réservations</button></a>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="validate_delivery" class="validate-btn">Valider la livraison</button>
                </div>
                    <div class="button-group">
                    <a href="liste_livreurs.php"><button type="button" class="stat-btn">Liste des Livreurs</button></a>
                </div>
                
                <div class="button-group">
                    <a href="ajout_livreur.php"><button type="button" class="add-btn">Ajouter un livreur</button></a>
                </div>
                
                <div class="button-group">
                    <a href="ajout_cat.php"><button type="button" class="category-btn">Ajouter une catégorie</button></a>
                    <a href="ajout_produit.php"><button type="button" class="product-btn">Ajouter un produit</button></a>
                </div>
                
                <div class="button-group">
                    <a href="statistique.php"><button type="button" class="stat-btn">Statistiques</button></a>
                </div>

                <div class="button-group">
                <a href="supprimer_categorie.php"><button type="button" class="sup-btn">Supprimer une catégorie</button></a>
                </div>

               <div class="button-group">
                <a href="supprimer_produit.php"><button type="button" class="sup-btn">Supprimer un produit</button></a>
               </div>
               <div class="button-group">
                    <a href="modifier_produit.php"><button type="button" class="product-btn">Modifier un produit</button></a>
                </div>
                <div class="button-group">
                    <a href="modifier_categorie.php"><button type="button" class="product-btn">Modifier une categorie</button></a>
                </div>
            </div>
        </form>
        
        <?php
            $conn->close();
        } catch (Exception $e) {
            echo '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Assignation livreur (nécessite un livreur sélectionné)
            document.getElementById('assign-btn').addEventListener('click', function() {
                const livreurId = document.getElementById('livreur-select').value;
                if (!livreurId) return alert('Pour assigner, sélectionnez un livreur');
                
                const checkboxes = document.querySelectorAll('input[name="commande_ids[]"]:checked');
                if (checkboxes.length === 0) return alert('Sélectionnez des commandes');
                
                const commande_ids = Array.from(checkboxes).map(cb => cb.value);
                
                fetch('assign_livreur.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({livreur_id: livreurId, commande_ids: commande_ids})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Commandes assignées');
                        location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Erreur réseau');
                });
            });
            
            // Validation livraison (ne nécessite pas de livreur)
            document.querySelector('form').addEventListener('submit', function(e) {
                if (e.submitter && e.submitter.name === 'validate_delivery') {
                    const checkboxes = document.querySelectorAll('input[name="commande_ids[]"]:checked');
                    if (checkboxes.length === 0) {
                        e.preventDefault();
                        alert('Sélectionnez des commandes');
                    } else if (!confirm('Confirmer la validation des commandes sélectionnées ?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>
</html>