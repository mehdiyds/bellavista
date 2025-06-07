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
            background-color:blue;
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
        .debug-info {
            background-color: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            font-family: monospace;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-delivered {
            color: #27ae60;
            font-weight: bold;
        }
        .stat-btn {
            background-color: #2196F3;
        }
        .stat-btn:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Essayez de récupérer le livreur sélectionné avant le rechargement
    const savedLivreurId = sessionStorage.getItem('selectedLivreur');
    if (savedLivreurId) {
        document.getElementById('livreur-select').value = savedLivreurId;
        sessionStorage.removeItem('selectedLivreur');
    }

    // Assign to delivery person functionality
    document.getElementById('assign-btn').addEventListener('click', function() {
        const livreurId = document.getElementById('livreur-select').value;
        
        if (!livreurId) {
            alert('Veuillez sélectionner un livreur');
            return;
        }
        
        // Sauvegarder le livreur sélectionné avant le rechargement
        sessionStorage.setItem('selectedLivreur', livreurId);
        
        // Here you would typically make an AJAX call to assign orders
        fetch('assign_livreur.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                livreur_id: livreurId,
                commande_ids: Array.from(document.querySelectorAll('.commande-checkbox:checked'))
                    .map(checkbox => checkbox.dataset.id)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Commandes assignées avec succès');
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
                sessionStorage.removeItem('selectedLivreur');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
            sessionStorage.removeItem('selectedLivreur');
        });
    });
    
});
    </script>
    <div class="container">
        <h1>Gestion des Commandes - Espace Admin</h1>
        
        <div id="alertSuccess" class="alert alert-success" style="display: none;"></div>
        <div id="alertError" class="alert alert-error" style="display: none;"></div>
        
        <?php
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Database connection with proper error handling
        try {
            $conn = new mysqli('localhost', 'root', '', 'bellavista');
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            // Set charset to utf8
            $conn->set_charset("utf8mb4");
            
            // Query to get orders with client information
            $sql = "SELECT 
                        c.commande_id,
                        cl.nom AS client,
                        cl.telephone,
                        cl.adresse,
                        c.commande,
                        c.montant_total,
                        c.reste,
                        DATE_FORMAT(c.date_commande, '%Y-%m-%d %H:%i') AS date_commande,
                        c.statut
                    FROM commandes c
                    JOIN clients cl ON c.client_id = cl.client_id
                    ORDER BY c.date_commande DESC";
            
            $result = $conn->query($sql);
            
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
        ?>
        
        <table class="commandes-table">
    <thead>
        <tr>
            <th>Sélection</th>
            <th>ID</th>
            <th>Client</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Commande</th>
            <th>Montant Total</th>
            <th>Reste à Payer</th>
            <th>Date</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody id="commandes-list">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Determine status class for styling
                $statusClass = '';
                if ($row["statut"] == 'livrée') {
                    $statusClass = 'status-delivered';
                } elseif ($row["statut"] == 'en attente') {
                    $statusClass = 'status-pending';
                }
                
                echo "<tr>
                        <td><input type='checkbox' class='commande-checkbox' data-id='".htmlspecialchars($row["commande_id"])."'></td>
                        <td>".htmlspecialchars($row["commande_id"])."</td>
                        <td>".htmlspecialchars($row["client"])."</td>
                        <td>".htmlspecialchars($row["telephone"])."</td>
                        <td>".htmlspecialchars($row["adresse"])."</td>
                        <td>".htmlspecialchars($row["commande"] ?? '')."</td>
                        <td>".number_format($row["montant_total"], 2, '.', '')." DT</td>
                        <td>".number_format(abs($row["reste"]), 2, '.', '')." DT</td>
                        <td>".htmlspecialchars($row["date_commande"])."</td>
                        <td class='$statusClass'>".htmlspecialchars($row["statut"])."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='10' style='text-align: center; color: red; font-weight: bold;'>Aucune commande trouvée dans la base de données</td></tr>";
        }
        ?>
    </tbody>
</table>
        
        <div class="action-panel">
            <?php
// Get available delivery persons
$livreurs = $conn->query("SELECT livreur_id, nom 
             FROM livreurs 
             WHERE statut = 'disponible'
             ORDER BY nom");
            
if (!$livreurs) {
    throw new Exception("Livreurs query failed: " . $conn->error);
}
?>

<select id="livreur-select" required>
    <option value="">-- Sélectionner un livreur --</option>
    <?php
    if ($livreurs->num_rows > 0) {
        while($livreur = $livreurs->fetch_assoc()) {
            echo "<option value='".htmlspecialchars($livreur["livreur_id"])."'>".htmlspecialchars($livreur["nom"])."</option>";
        }
    } else {
        echo "<option value='' disabled>Aucun livreur disponible</option>";
    }
    ?>
</select>
            
            <button type="button" id="assign-btn" class="assign-btn">Assigner au livreur</button>
            <button type="button" id="delete-btn" class="delete-btn">Valider la livraison</button>
            <a href="ajout_livreur.php">
                <button type="button" id="ajout-btn" class="ajout-btn">Ajouter un livreur</button>
            </a>
            <a href="statistique.php">
                <button type="button" id="stat-btn" class="stat-btn">Statistique</button>
            </a>
        </div>
        
        <?php
            $conn->close();
        } catch (Exception $e) {
            echo '<div class="alert alert-error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>

    <script>
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Assign to delivery person functionality
            document.getElementById('assign-btn').addEventListener('click', function() {
                const livreurId = document.getElementById('livreur-select').value;
                
                if (!livreurId) {
                    alert('Veuillez sélectionner un livreur');
                    return;
                }
                
                // Here you would typically make an AJAX call to assign orders
                fetch('assign_livreur.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        livreur_id: livreurId,
                        commande_ids: Array.from(document.querySelectorAll('.commande-checkbox:checked'))
                            .map(checkbox => checkbox.dataset.id)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Commandes assignées avec succès');
                        location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue');
                });
            });
            
            // Delete selected functionality
            document.getElementById('delete-btn').addEventListener('click', function() {
                const selectedOrders = Array.from(document.querySelectorAll('.commande-checkbox:checked'))
                    .map(checkbox => checkbox.dataset.id);
                
                if (selectedOrders.length === 0) {
                    alert('Veuillez sélectionner au moins une commande');
                    return;
                }
                
                if (confirm('Êtes-vous sûr de vouloir supprimer les commandes sélectionnées ?')) {
                    fetch('delete_commandes.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            commande_ids: selectedOrders
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Commandes supprimées avec succès');
                            location.reload();
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Une erreur est survenue');
                    });
                }
            });
        });
    </script>
</body>
>>>>>>> 3daac0d816709d283ced63bc0f1ab3ae157bc9c0
</html>