<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tables</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #8e44ad;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-available {
            color: green;
            font-weight: bold;
        }
        .status-occupied {
            color: red;
            font-weight: bold;
        }
        .status-reserved {
            color: orange;
            font-weight: bold;
        }
        .status-maintenance {
            color: #3498db;
            font-weight: bold;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #8e44ad;
        }
        .btn-secondary {
            background-color: #3498db;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .image-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 5px;
        }
        /* Styles pour les messages d'alerte */
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
    </style>
</head>
<body>
    <h1>Gestion des Tables</h1>
    
    <!-- Affichage des messages de succès/erreur -->
    <?php
    if (isset($_GET['success'])) {
        echo '<div class="alert alert-success">'.htmlspecialchars($_GET['success']).'</div>';
    }
    if (isset($_GET['error'])) {
        echo '<div class="alert alert-error">'.htmlspecialchars($_GET['error']).'</div>';
    }
    ?>
    
    <div class="action-buttons">
        <a href="admin.php" class="btn btn-secondary">Retour à l'administration</a>
        <a href="ajout_table.php" class="btn btn-primary">Ajouter une Table</a>
    </div>
    
    <?php
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Récupération des tables
    $sql = "SELECT * FROM tables ORDER BY numero";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo '<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Numéro</th>
                        <th>Capacité</th>
                        <th>Statut</th>
                        <th>Description</th>
                        <th>Caractéristiques</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        while($row = $result->fetch_assoc()) {
            // Déterminer la classe CSS en fonction du statut
            $status_class = '';
            switch($row['statut']) {
                case 'disponible':
                    $status_class = 'status-available';
                    break;
                case 'occupée':
                    $status_class = 'status-occupied';
                    break;
                case 'réservée':
                    $status_class = 'status-reserved';
                    break;
                case 'maintenance':
                    $status_class = 'status-maintenance';
                    break;
            }
            
            echo '<tr>
                    <td>'.$row['table_id'].'</td>
                    <td>'.$row['numero'].'</td>
                    <td>'.$row['capacite'].'</td>
                    <td class="'.$status_class.'">'.$row['statut'].'</td>
                    <td>'.htmlspecialchars($row['description']).'</td>
                    <td>'.htmlspecialchars($row['caracteristiques']).'</td>
                    <td>';
            
            if ($row['image']) {
                echo '<img src="'.$row['image'].'" alt="Table '.$row['numero'].'" class="image-preview">';
            } else {
                echo 'Aucune image';
            }
            
            echo '</td>
                    <td>
                        
                        <a href="supprimer_table.php?id='.$row['table_id'].'" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette table ?\')">Supprimer</a>
                    </td>
                  </tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<p>Aucune table trouvée.</p>';
    }
    
    $conn->close();
    ?>
</body>
</html>