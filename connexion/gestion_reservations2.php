<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'bellavista');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation'])) {
    $reservation_id = intval($_POST['reservation_id']);
    
    $stmt = $conn->prepare("DELETE FROM reservations2 WHERE id = ?");
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        $success_message = "Reservation deleted successfully";
    } else {
        $error_message = "Error deleting reservation: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch all reservations from reservations2
$sql = "SELECT r.id, r.full_name, r.phone, r.seats, r.reservation_date, 
               r.special_requests, r.created_at
        FROM reservations2 r
        ORDER BY r.reservation_date DESC";
$result = $conn->query($sql);

$reservations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations (Table reservations2)</title>
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
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
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
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .btn-delete {
            background-color: #e74c3c;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .no-reservations {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Réservations (Table reservations2)</h1>
        
        <a href="admin.php" class="btn">Retour à l'admin</a>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($reservations)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Téléphone</th>
                        <th>Date</th>
                        <th>Nombre de Places</th>
                        <th>Demandes Spéciales</th>
                        <th>Date de Création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['id']; ?></td>
                        <td><?php echo htmlspecialchars($reservation['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                        <td><?php echo $reservation['seats']; ?></td>
                        <td><?php echo htmlspecialchars($reservation['special_requests'] ?? '-'); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($reservation['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                <button type="submit" name="delete_reservation" class="btn btn-delete" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-reservations">Aucune réservation trouvée dans la table reservations2</div>
        <?php endif; ?>
    </div>
</body>
</html>