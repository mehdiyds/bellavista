<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Récupération des données du formulaire
    $table_id = $_POST['table_id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $date_reservation = $_POST['date_reservation'];
    $heure_reservation = $_POST['heure_reservation'];
    $nombre_personnes = $_POST['nombre_personnes'];
    $notes = !empty($_POST['notes']) ? $_POST['notes'] : null;
    
    // 1. Vérifier que la table est toujours disponible
    $stmt = $conn->prepare("SELECT statut FROM tables WHERE table_id = ?");
    $stmt->bind_param("i", $table_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $table = $result->fetch_assoc();
    $stmt->close();
    
    if (!$table || $table['statut'] !== 'disponible') {
        die("Désolé, cette table n'est plus disponible.");
    }
    
    // 2. Enregistrer le client (ou le retrouver s'il existe déjà)
    $stmt = $conn->prepare("INSERT INTO clients (nom, telephone, adresse) VALUES (?, ?, ?)");
    $adresse = "Réservation - " . $email; // Utilisation simplifiée
    $stmt->bind_param("sss", $nom, $telephone, $adresse);
    $stmt->execute();
    $client_id = $stmt->insert_id;
    $stmt->close();
    
    // 3. Créer la réservation
    $stmt = $conn->prepare("INSERT INTO reservations 
                          (client_id, table_id, date_reservation, heure_reservation, 
                           nombre_personnes, statut, notes)
                          VALUES (?, ?, ?, ?, ?, 'confirmée', ?)");
    $stmt->bind_param("iissis", 
        $client_id,
        $table_id,
        $date_reservation,
        $heure_reservation,
        $nombre_personnes,
        $notes
    );
    $stmt->execute();
    $reservation_id = $stmt->insert_id;
    $stmt->close();
    
    // 4. Mettre à jour le statut de la table
    $stmt = $conn->prepare("UPDATE tables SET statut = 'réservée' WHERE table_id = ?");
    $stmt->bind_param("i", $table_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirection avec un message de succès
    header('Location: reservation.php?success=1&reservation_id='.$reservation_id);
    $conn->close();
} else {
    header('Location: reservation.php');
}
?>