<?php
header('Content-Type: application/json');

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit;
}

// Récupération des données envoyées
$data = json_decode(file_get_contents('php://input'), true);

try {
    // Préparation de la requête d'insertion
    $stmt = $db->prepare("INSERT INTO livreurs (livreur_id, nom, prenom, telephone, statut, mdp) 
                         VALUES (:id, :nom, :prenom, :telephone, :statut, :mdp)");
    
    // Exécution de la requête avec les données
    $stmt->execute([
        ':id' => $data['id'],
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':telephone' => $data['telephone'],
        ':statut' => $data['statut'],
        ':mdp' => $data['password']
    ]);
    
    // Réponse de succès
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    // Gestion des erreurs
    $message = "Erreur lors de l'ajout du livreur";
    if ($e->getCode() == 23000) { // Erreur de duplication (clé primaire)
        $message = "Un livreur avec cet ID existe déjà";
    }
    
    echo json_encode(['success' => false, 'message' => $message]);
}