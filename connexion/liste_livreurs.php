<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Livreurs</title>
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
            background-color: blue;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-available {
            color: green;
            font-weight: bold;
        }
        .status-delivery {
            color: orange;
            font-weight: bold;
        }
        .status-unavailable {
            color: red;
            font-weight: bold;
        }
        .back-btn {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h1>Liste des Livreurs</h1>
    
    <?php
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Récupération des livreurs
    $sql = "SELECT livreur_id, nom, prenom, telephone, statut FROM livreurs ORDER BY nom";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo '<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
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
                case 'en livraison':
                    $status_class = 'status-delivery';
                    break;
                case 'indisponible':
                    $status_class = 'status-unavailable';
                    break;
            }
            
            echo '<tr>
                    <td>'.$row['livreur_id'].'</td>
                    <td>'.htmlspecialchars($row['nom']).'</td>
                    <td>'.htmlspecialchars($row['prenom']).'</td>
                    <td>'.htmlspecialchars($row['telephone']).'</td>
                    <td class="'.$status_class.'">'.$row['statut'].'</td>
                  </tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<p>Aucun livreur trouvé.</p>';
    }
    
    $conn->close();
    ?>
    
    <a href="admin.php" class="back-btn">Retour à l'administration</a>
</body>
</html>