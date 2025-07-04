<?php 
include 'C:\xampp\htdocs\bellavista\includes\header.php'; 
?>
<?php
// Configuration de la base URL
$baseurl = 'http://' . $_SERVER['HTTP_HOST'] . '/bellavista/connexion/';
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'bellavista');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $date_reservation = $conn->real_escape_string($_POST['date_reservation']);
    $heure_reservation = $conn->real_escape_string($_POST['heure_reservation']);
    $nombre_personnes = intval($_POST['nombre_personnes']);
    $table_id = intval($_POST['table_id']);
    $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';

    // Vérifier si le client existe déjà
    $client_query = "SELECT client_id FROM clients WHERE telephone = '$telephone' LIMIT 1";
    $client_result = $conn->query($client_query);
    
    if ($client_result->num_rows > 0) {
        // Client existe déjà
        $client_row = $client_result->fetch_assoc();
        $client_id = $client_row['client_id'];
    } else {
        // Créer un nouveau client
        $insert_client = "INSERT INTO clients (nom, telephone, adresse) VALUES ('$nom', '$telephone', '')";
        if ($conn->query($insert_client)) {
            $client_id = $conn->insert_id;
        } else {
            header("Location: reservation1.php?error=" . urlencode("Erreur lors de la création du client: " . $conn->error));
            exit();
        }
    }

    // Insérer la réservation
    $insert_reservation = "INSERT INTO reservations (
        client_id, 
        date_reservation, 
        heure_reservation, 
        nombre_personnes, 
        numero_table, 
        statut, 
        notes
    ) VALUES (
        $client_id, 
        '$date_reservation', 
        '$heure_reservation', 
        $nombre_personnes, 
        $table_id, 
        'confirmée', 
        '$notes'
    )";

    if ($conn->query($insert_reservation)) {
        // Mettre à jour le statut de la table
        $update_table = "UPDATE tables SET statut = 'réservée' WHERE table_id = $table_id";
        $conn->query($update_table);
        
        // Redirection avec message de succès
        header("Location: reservation1.php?success=1");
        exit();
    } else {
        header("Location: reservation1.php?error=" . urlencode("Erreur lors de l'enregistrement de la réservation: " . $conn->error));
        exit();
    }
}

// Récupérer les tables disponibles
$tables_disponibles = $conn->query("SELECT * FROM tables WHERE statut = 'disponible'");

// Vérifier s'il y a une erreur dans la requête
if ($tables_disponibles === false) {
    die("Erreur lors de la récupération des tables: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Table - Bella Vista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        
        h1, h2, h3 {
            margin-top: 0;
        }
        
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }
        
        
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .table-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .table-image-container {
            height: 200px;
            overflow: hidden;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .table-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .table-card:hover .table-image {
            transform: scale(1.05);
        }
        
        .table-info {
            padding: 20px;
        }
        
        .table-capacity {
            display: inline-block;
            padding: 4px 8px;
            background-color: var(--light-color);
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .table-features {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .hidden {
            display: none;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .tables-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    
    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Votre réservation a été enregistrée avec succès!
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> Une erreur est survenue: <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <h2><i class="fas fa-search"></i> Tables Disponibles</h2>
            
            <?php if ($tables_disponibles->num_rows > 0): ?>
                <div class="tables-grid">
                    <?php while($table = $tables_disponibles->fetch_assoc()): ?>
                        <div class="table-card">
                            <div class="table-image-container">
    <?php if (!empty($table['image'])): ?>
        <img src="<?= $baseurl .  htmlspecialchars($table['image']) ?>" 
             alt="Table <?= htmlspecialchars($table['numero']) ?>" 
             class="table-image"
             onerror="this.src='<?= $baseurl ?>uploads/default.jpg'">
    <?php else: ?>
        <img src="<?= $baseurl ?>uploads/default.jpg" 
             alt="Table <?= htmlspecialchars($table['numero']) ?>" 
             class="table-image">
    <?php endif; ?>
</div>
                            <div class="table-info">
                                <h3>Table <?= htmlspecialchars($table['numero']) ?></h3>
                                <span class="table-capacity"><i class="fas fa-users"></i> <?= htmlspecialchars($table['capacite']) ?> personnes max</span>
                                <p><?= htmlspecialchars($table['description']) ?></p>
                                <p class="table-features"><strong><i class="fas fa-info-circle"></i> Caractéristiques:</strong> <?= htmlspecialchars($table['caracteristiques']) ?></p>
                                <button class="btn btn-primary" onclick="reserverTable(<?= htmlspecialchars($table['table_id']) ?>, <?= htmlspecialchars($table['capacite']) ?>)">
                                    <i class="fas fa-calendar-check"></i> Réserver
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Aucune table disponible pour le moment.
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Formulaire de réservation -->
        <div id="reservationForm" class="form-container hidden">
            <h2><i class="fas fa-edit"></i> Formulaire de Réservation</h2>
            <div id="formErrors" class="alert alert-danger hidden"></div>
            
            <form id="reservationFormElement" action="reservation1.php" method="post">
                <input type="hidden" name="table_id" id="reservation_table_id">
                <input type="hidden" id="table_capacity">
                
                <div class="form-group">
                    <label for="nom"><i class="fas fa-user"></i> Nom complet:</label>
                    <input type="text" name="nom" id="nom" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="telephone"><i class="fas fa-phone"></i> Téléphone:</label>
                    <input type="tel" name="telephone" id="telephone" required>
                </div>
                
                <div class="form-group">
                    <label for="date_reservation"><i class="fas fa-calendar-day"></i> Date de réservation:</label>
                    <input type="date" name="date_reservation" id="date_reservation" required min="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label for="heure_reservation"><i class="fas fa-clock"></i> Heure de réservation:</label>
                    <input type="time" name="heure_reservation" id="heure_reservation" required min="11:00" max="22:00">
                </div>
                
                <div class="form-group">
                    <label for="nombre_personnes"><i class="fas fa-user-friends"></i> Nombre de personnes:</label>
                    <input type="number" name="nombre_personnes" id="nombre_personnes" min="1" required>
                    <small id="capacityWarning" class="hidden" style="color: var(--danger-color); font-weight: bold;"></small>
                </div>
                
                <div class="form-group">
                    <label for="notes"><i class="fas fa-sticky-note"></i> Demandes spéciales (optionnel):</label>
                    <textarea name="notes" id="notes" rows="3"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Confirmer la réservation
                    </button>
                    <button type="button" class="btn btn-danger" onclick="annulerReservation()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Fonction pour afficher le formulaire de réservation
        function reserverTable(tableId, capacity) {
            document.getElementById('reservation_table_id').value = tableId;
            document.getElementById('table_capacity').value = capacity;
            document.getElementById('reservationForm').classList.remove('hidden');
            document.getElementById('formErrors').classList.add('hidden');
            
            // Scroll vers le formulaire
            document.getElementById('reservationForm').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Fonction pour annuler la réservation
        function annulerReservation() {
            document.getElementById('reservationForm').classList.add('hidden');
            resetForm();
        }
        
        // Réinitialiser le formulaire
        function resetForm() {
            document.getElementById('reservationFormElement').reset();
            document.getElementById('capacityWarning').classList.add('hidden');
        }
        
        // Validation du nombre de personnes
        document.getElementById('nombre_personnes').addEventListener('input', function() {
            const nbPersonnes = parseInt(this.value);
            const capacity = parseInt(document.getElementById('table_capacity').value);
            const warningElement = document.getElementById('capacityWarning');
            
            if (nbPersonnes > capacity) {
                warningElement.textContent = `Attention : Cette table ne peut accueillir que ${capacity} personnes maximum.`;
                warningElement.classList.remove('hidden');
            } else {
                warningElement.classList.add('hidden');
            }
        });
        
        // Validation du formulaire avant soumission
        document.getElementById('reservationFormElement').addEventListener('submit', function(e) {
            const nbPersonnes = parseInt(document.getElementById('nombre_personnes').value);
            const capacity = parseInt(document.getElementById('table_capacity').value);
            const errors = [];
            
            // Vérifier le nombre de personnes
            if (nbPersonnes > capacity) {
                errors.push(`Cette table ne peut accueillir que ${capacity} personnes maximum.`);
            }
            
            // Vérifier la date
            const reservationDate = new Date(document.getElementById('date_reservation').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (reservationDate < today) {
                errors.push("La date de réservation ne peut pas être dans le passé.");
            }
            
            // Vérifier l'heure
            const heureReservation = document.getElementById('heure_reservation').value;
            const [hours, minutes] = heureReservation.split(':').map(Number);
            
            if (hours < 11 || hours > 22 || (hours === 22 && minutes > 0)) {
                errors.push("Les réservations sont possibles entre 11h et 22h.");
            }
            
            // Afficher les erreurs s'il y en a
            if (errors.length > 0) {
                e.preventDefault();
                const errorElement = document.getElementById('formErrors');
                errorElement.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Erreurs dans le formulaire:</strong>
                    <ul>${errors.map(error => `<li>${error}</li>`).join('')}</ul>
                `;
                errorElement.classList.remove('hidden');
                window.scrollTo(0, 0);
            }
        });
        
        // Définir la date minimale pour le champ date
        document.getElementById('date_reservation').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>