<?php
// Connexion à la base de données pour récupérer les tables disponibles
$conn = new mysqli('localhost', 'root', '', 'bellavista');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les tables disponibles
$tables_disponibles = $conn->query("SELECT * FROM tables WHERE statut = 'disponible'");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            background-color: #8e44ad;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .table-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
        }
        .table-image {
            max-width: 150px;
            max-height: 150px;
        }
        .table-info {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <h1>Réservation de Table</h1>
    
    <div class="form-container">
        <h2>Tables Disponibles</h2>
        
        <?php if ($tables_disponibles->num_rows > 0): ?>
            <?php while($table = $tables_disponibles->fetch_assoc()): ?>
                <div class="table-card">
                    <?php if ($table['image']): ?>
                        <img src="<?= $table['image'] ?>" alt="Table <?= $table['numero'] ?>" class="table-image">
                    <?php endif; ?>
                    <div class="table-info">
                        <h3>Table <?= $table['numero'] ?></h3>
                        <p>Capacité: <?= $table['capacite'] ?> personnes</p>
                        <p><?= htmlspecialchars($table['description']) ?></p>
                        <p><strong>Caractéristiques:</strong> <?= htmlspecialchars($table['caracteristiques']) ?></p>
                        <button onclick="reserverTable(<?= $table['table_id'] ?>)">Réserver cette table</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucune table disponible pour le moment.</p>
        <?php endif; ?>
    </div>
    
    <!-- Formulaire de réservation (caché au début) -->
    <div id="reservationForm" class="form-container" style="display: none;">
        <h2>Formulaire de Réservation</h2>
        <form action="traitement_reservation_client.php" method="post">
            <input type="hidden" name="table_id" id="reservation_table_id">
            
            <div class="form-group">
                <label for="nom">Nom complet:</label>
                <input type="text" name="nom" id="nom" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="tel" name="telephone" id="telephone" required>
            </div>
            
            <div class="form-group">
                <label for="date_reservation">Date de réservation:</label>
                <input type="date" name="date_reservation" id="date_reservation" required>
            </div>
            
            <div class="form-group">
                <label for="heure_reservation">Heure de réservation:</label>
                <input type="time" name="heure_reservation" id="heure_reservation" required>
            </div>
            
            <div class="form-group">
                <label for="nombre_personnes">Nombre de personnes:</label>
                <input type="number" name="nombre_personnes" id="nombre_personnes" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="notes">Demandes spéciales (optionnel):</label>
                <textarea name="notes" id="notes" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn">Confirmer la réservation</button>
            <button type="button" class="btn" onclick="annulerReservation()">Annuler</button>
        </form>
    </div>
    
    <script>
        function reserverTable(tableId) {
            document.getElementById('reservation_table_id').value = tableId;
            document.getElementById('reservationForm').style.display = 'block';
            window.scrollTo(0, document.body.scrollHeight);
        }
        
        function annulerReservation() {
            document.getElementById('reservationForm').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>