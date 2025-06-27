<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Gestion des Commandes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .toolbar-section {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            padding: 5px;
            border-right: 1px solid #ddd;
        }
        
        .toolbar-section:last-child {
            border-right: none;
        }
        
        .toolbar-section-title {
            font-weight: bold;
            margin-right: 5px;
            color: #333;
        }
        
        button, .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s;
            white-space: nowrap;
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
            min-width: 150px;
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
        .admin-btn{
            background-color: burlywood;
        }
        
        /* Styles pour la notification */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-with-notification {
            position: relative;
            display: inline-block;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(46, 204, 113, 0); }
            100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
        }
        
        .pulse-animation {
            animation: pulse 1s 2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes - Espace Admin</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Fonction pour vérifier les nouvelles commandes
        function checkNewOrdersCount() {
            try {
                $conn = new mysqli('localhost', 'root', '', 'bellavista');
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }
                
                $result = $conn->query("SELECT COUNT(*) as count FROM commandes WHERE statut = 'en attente'");
                $row = $result->fetch_assoc();
                $count = (int)$row['count'];
                
                $conn->close();
                return $count;
            } catch (Exception $e) {
                return 0;
            }
        }

        $newOrdersCount = checkNewOrdersCount();

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
            <div class="toolbar">
                <!-- Section Gestion des Commandes -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Commandes:</span>
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
                    <div class="btn-with-notification">
                        <button type="button" id="assign-btn" class="assign-btn <?= $newOrdersCount > 0 ? 'pulse-animation' : '' ?>">Assigner</button>
                        <?php if ($newOrdersCount > 0): ?>
                            <span id="new-orders-badge" class="notification-badge"><?= $newOrdersCount ?></span>
                        <?php else: ?>
                            <span id="new-orders-badge" class="notification-badge" style="display: none;">0</span>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="validate_delivery" class="validate-btn">Valider livraison</button>
                </div>
                
                <!-- Section Livreurs -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Livreurs:</span>
                    <a href="liste_livreurs.php"><button type="button" class="stat-btn">Liste</button></a>
                </div>
                
                <!-- Section Produits -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Produits:</span>
                    <a href="ajout_produit.php"><button type="button" class="product-btn">Ajouter</button></a>
                    <a href="modifier_produit.php"><button type="button" class="product-btn">Modifier</button></a>
                    <a href="supprimer_produit.php"><button type="button" class="sup-btn">Supprimer</button></a>
                </div>
                
                <!-- Section Catégories -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Catégories:</span>
                    <a href="ajout_cat.php"><button type="button" class="category-btn">Ajouter</button></a>
                    <a href="modifier_categorie.php"><button type="button" class="category-btn">Modifier</button></a>
                    <a href="supprimer_categorie.php"><button type="button" class="sup-btn">Supprimer</button></a>
                </div>
                
                <!-- Section Tables/Réservations -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Tables:</span>
                    <a href="gestion_tables.php"><button type="button" class="stat-btn">Gérer</button></a>
                    <button type="button" id="show-unavailable-tables" class="category-btn">Tables indisponibles</button>
                </div>
                
                <!-- Section Statistiques -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Rapports:</span>
                    <a href="statistique.php"><button type="button" class="stat-btn">Statistiques</button></a>
                </div>
            </div>
            
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
            // Fonction pour vérifier les nouvelles commandes
            function checkNewOrders() {
                fetch('check_new_orders.php')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('new-orders-badge');
                        const assignBtn = document.getElementById('assign-btn');
                        
                        if (data.count > 0) {
                            badge.style.display = 'flex';
                            badge.textContent = data.count;
                            assignBtn.classList.add('pulse-animation');
                        } else {
                            badge.style.display = 'none';
                            assignBtn.classList.remove('pulse-animation');
                        }
                    })
                    .catch(error => console.error('Error checking new orders:', error));
            }

            // Vérifier les nouvelles commandes toutes les 30 secondes
            checkNewOrders();
            setInterval(checkNewOrders, 30000);

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
                        // Masquer la notification après assignation
                        document.getElementById('new-orders-badge').style.display = 'none';
                        document.getElementById('assign-btn').classList.remove('pulse-animation');
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
            
            // Gestion des tables indisponibles
            document.getElementById('show-unavailable-tables').addEventListener('click', function() {
                fetch('get_unavailable_tables.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        // Créer une fenêtre modale pour afficher les tables
                        const modal = document.createElement('div');
                        modal.style.position = 'fixed';
                        modal.style.top = '0';
                        modal.style.left = '0';
                        modal.style.width = '100%';
                        modal.style.height = '100%';
                        modal.style.backgroundColor = 'rgba(0,0,0,0.7)';
                        modal.style.zIndex = '1000';
                        modal.style.display = 'flex';
                        modal.style.justifyContent = 'center';
                        modal.style.alignItems = 'center';

                        const modalContent = document.createElement('div');
                        modalContent.style.backgroundColor = 'white';
                        modalContent.style.padding = '20px';
                        modalContent.style.borderRadius = '5px';
                        modalContent.style.maxWidth = '80%';
                        modalContent.style.maxHeight = '80%';
                        modalContent.style.overflow = 'auto';

                        const closeBtn = document.createElement('button');
                        closeBtn.textContent = 'Fermer';
                        closeBtn.style.marginBottom = '10px';
                        closeBtn.addEventListener('click', () => document.body.removeChild(modal));

                        modalContent.appendChild(closeBtn);

                        if (data.length === 0) {
                            modalContent.appendChild(document.createTextNode('Aucune table indisponible'));
                        } else {
                            const table = document.createElement('table');
                            table.style.width = '100%';
                            table.style.borderCollapse = 'collapse';
                            
                            // En-tête du tableau
                            const thead = document.createElement('thead');
                            const headerRow = document.createElement('tr');
                            ['ID', 'Numéro', 'Capacité', 'Description', 'Statut', 'Action'].forEach(text => {
                                const th = document.createElement('th');
                                th.textContent = text;
                                th.style.padding = '8px';
                                th.style.border = '1px solid #ddd';
                                th.style.textAlign = 'left';
                                headerRow.appendChild(th);
                            });
                            thead.appendChild(headerRow);
                            table.appendChild(thead);

                            // Corps du tableau
                            const tbody = document.createElement('tbody');
                            data.forEach(tableData => {
                                const row = document.createElement('tr');
                                
                                // ID
                                const idCell = document.createElement('td');
                                idCell.textContent = tableData.table_id;
                                row.appendChild(idCell);

                                // Numéro
                                const numCell = document.createElement('td');
                                numCell.textContent = tableData.numero;
                                row.appendChild(numCell);

                                // Capacité
                                const capCell = document.createElement('td');
                                capCell.textContent = tableData.capacite;
                                row.appendChild(capCell);

                                // Description
                                const descCell = document.createElement('td');
                                descCell.textContent = tableData.description || '';
                                row.appendChild(descCell);

                                // Statut
                                const statutCell = document.createElement('td');
                                statutCell.textContent = tableData.statut;
                                row.appendChild(statutCell);

                                // Action
                                const actionCell = document.createElement('td');
                                const makeAvailableBtn = document.createElement('button');
                                makeAvailableBtn.textContent = 'Rendre disponible';
                                makeAvailableBtn.style.backgroundColor = '#2ecc71';
                                makeAvailableBtn.style.color = 'white';
                                makeAvailableBtn.style.border = 'none';
                                makeAvailableBtn.style.padding = '5px 10px';
                                makeAvailableBtn.style.borderRadius = '3px';
                                makeAvailableBtn.style.cursor = 'pointer';
                                makeAvailableBtn.addEventListener('click', () => {
                                    fetch('make_table_available.php', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/json'},
                                        body: JSON.stringify({table_id: tableData.table_id})
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            alert('Table marquée comme disponible');
                                            document.body.removeChild(modal);
                                        } else {
                                            alert('Erreur: ' + result.message);
                                        }
                                    });
                                });
                                actionCell.appendChild(makeAvailableBtn);
                                row.appendChild(actionCell);

                                tbody.appendChild(row);
                            });
                            table.appendChild(tbody);
                            modalContent.appendChild(table);
                        }

                        modal.appendChild(modalContent);
                        document.body.appendChild(modal);
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Erreur lors du chargement des tables');
                    });
            });
        });
    </script>
</body>
</html>