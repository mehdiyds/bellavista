<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if this is an AJAX request for reservations count
if (isset($_GET['check_reservations'])) {
    header('Content-Type: application/json');
    
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        die(json_encode(['error' => 'Connection failed']));
    }

    $result = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE statut = 'en attente'");
    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'count' => (int)$row['count'],
            'timestamp' => date('H:i:s')
        ]);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
    
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Gestion des Commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
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
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
            transition: all 0.3s;
            white-space: nowrap;
        }
        button:hover, .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .validate-btn { background-color: #e74c3c; }
        .stat-btn { background-color: #3498db; }
        .sup-btn { background-color: #ff4757; }
        .assign-btn { background-color: #2ecc71; }
        .add-btn { background-color: #9b59b6; }
        .category-btn { background-color: #e67e22; }
        .product-btn { background-color: #1abc9c; }
        .admin-btn { background-color: #d35400; }
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
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .commandes-table th {
            background-color: #3498db;
            color: white;
        }
        .commandes-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .commandes-table tr:hover {
            background-color: #e6f7ff;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-delivered {
            color: #27ae60;
            font-weight: bold;
        }
        .notification-badge {
            background-color: #ff0000;
            color: white;
            border-radius: 10px;
            padding: 3px 8px;
            font-size: 12px;
            margin-left: 8px;
            display: inline-block;
            min-width: 20px;
            text-align: center;
            line-height: 1;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .last-check {
            font-size: 11px;
            color: #7f8c8d;
            margin-left: 10px;
            font-style: italic;
        }
        .refresh-btn {
            background: none;
            border: none;
            color: #3498db;
            cursor: pointer;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes - Espace Admin</h1>
        
        <?php
        // Database connection for main page
        $conn = new mysqli('localhost', 'root', '', 'bellavista');
        if ($conn->connect_error) {
            die("<div class='alert alert-error'>Connection failed: " . $conn->connect_error . "</div>");
        }
        $conn->set_charset("utf8mb4");
        
        // Get initial reservations count
        $reservation_count = 0;
        $reservation_query = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE statut = 'en attente'");
        if ($reservation_query && $reservation_result = $reservation_query->fetch_assoc()) {
            $reservation_count = (int)$reservation_result['count'];
        }
        
        // Get orders for display
        $orders = [];
        $order_query = "SELECT c.*, 
                       (SELECT nom FROM clients WHERE client_id = c.client_id) AS client,
                       (SELECT telephone FROM clients WHERE client_id = c.client_id) AS telephone,
                       (SELECT adresse FROM clients WHERE client_id = c.client_id) AS adresse
                       FROM commandes c
                       ORDER BY c.date_commande DESC";
        $order_result = $conn->query($order_query);
        
        if ($order_result) {
            $orders = $order_result->fetch_all(MYSQLI_ASSOC);
        }
        ?>

        <form method="post">
            <div class="toolbar">
                <!-- Réservations Section with Real-Time Notification -->
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Réservations:</span>
                    <a href="gestion_reservations2.php">
                        <button type="button" class="category-btn" id="reservations-btn">
                            Nouvelles Réservations
                            <?php if ($reservation_count > 0): ?>
                                <span id="reservation-badge" class="notification-badge"><?= $reservation_count ?></span>
                            <?php else: ?>
                                <span id="reservation-badge" class="notification-badge" style="display: none;"></span>
                            <?php endif; ?>
                        </button>
                    </a>
                    <span id="last-check" class="last-check">Dernière vérif: <?= date('H:i:s') ?></span>
                    <button type="button" id="refresh-btn" class="refresh-btn" title="Actualiser maintenant">⟳</button>
                </div>
                
                <!-- [Rest of your toolbar sections] -->
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
                    <button type="button" id="assign-btn" class="assign-btn">Assigner</button>
                    <button type="submit" name="validate_delivery" class="validate-btn">Valider livraison</button>
                    <a href="ajout_semi_admin.php"><button type="button" class="admin-btn">Ajouter un semi-administrateur</button></a>
                </div>
                
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Livreurs:</span>
                    <a href="liste_livreurs.php"><button type="button" class="stat-btn">Liste</button></a>
                    <a href="ajout_livreur.php"><button type="button" class="add-btn">Ajouter</button></a>
                </div>
                
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Produits:</span>
                    <a href="ajout_produit.php"><button type="button" class="product-btn">Ajouter</button></a>
                    <a href="modifier_produit.php"><button type="button" class="product-btn">Modifier</button></a>
                    <a href="supprimer_produit.php"><button type="button" class="sup-btn">Supprimer</button></a>
                </div>
                
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Catégories:</span>
                    <a href="ajout_cat.php"><button type="button" class="category-btn">Ajouter</button></a>
                    <a href="modifier_categorie.php"><button type="button" class="category-btn">Modifier</button></a>
                    <a href="supprimer_categorie.php"><button type="button" class="sup-btn">Supprimer</button></a>
                </div>
                
                <div class="toolbar-section">
                    <span class="toolbar-section-title">Tables:</span>
                    <a href="gestion_tables.php"><button type="button" class="stat-btn">Gérer</button></a>
                    <button type="button" id="show-unavailable-tables" class="category-btn">Tables indisponibles</button>
                </div>
                
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
                    <?php if (!empty($orders)): ?>
                        <?php foreach($orders as $row): ?>
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
                        <?php endforeach; ?>
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
        
        <?php $conn->close(); ?>
    </div>

    <script>
        // Real-time reservation checker
        function checkNewReservations() {
            fetch('?check_reservations=1')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }
                    
                    const badge = document.getElementById('reservation-badge');
                    const lastCheck = document.getElementById('last-check');
                    
                    if (data.count > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = data.count;
                        // Add shake animation if count increased
                        if (parseInt(badge.textContent) < data.count) {
                            badge.style.animation = 'none';
                            void badge.offsetWidth; // Trigger reflow
                            badge.style.animation = 'pulse 1.5s infinite, shake 0.5s';
                        }
                    } else {
                        badge.style.display = 'none';
                    }
                    
                    // Update last check time
                    lastCheck.textContent = 'Dernière vérif: ' + data.timestamp;
                })
                .catch(error => {
                    console.error('Error checking reservations:', error);
                    document.getElementById('last-check').textContent = 'Erreur de connexion';
                });
        }

        // Check immediately on page load
        checkNewReservations();
        
        // Then check every 30 seconds
        const interval = setInterval(checkNewReservations, 30000);
        
        // Manual refresh button
        document.getElementById('refresh-btn').addEventListener('click', function() {
            clearInterval(interval);
            checkNewReservations();
            setInterval(checkNewReservations, 30000);
            
            // Show loading feedback
            const lastCheck = document.getElementById('last-check');
            lastCheck.textContent = 'Actualisation...';
        });

        // [Rest of your existing JavaScript]
        document.addEventListener('DOMContentLoaded', function() {
            // Assignation livreur
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
            
            // Validation livraison
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

                            const tbody = document.createElement('tbody');
                            data.forEach(tableData => {
                                const row = document.createElement('tr');
                                
                                const idCell = document.createElement('td');
                                idCell.textContent = tableData.table_id;
                                row.appendChild(idCell);

                                const numCell = document.createElement('td');
                                numCell.textContent = tableData.numero;
                                row.appendChild(numCell);

                                const capCell = document.createElement('td');
                                capCell.textContent = tableData.capacite;
                                row.appendChild(capCell);

                                const descCell = document.createElement('td');
                                descCell.textContent = tableData.description || '';
                                row.appendChild(descCell);

                                const statutCell = document.createElement('td');
                                statutCell.textContent = tableData.statut;
                                row.appendChild(statutCell);

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