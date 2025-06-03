<?php
header('Content-Type: application/json');

// Démarrer la session
session_start();

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit;
}

// Récupération des données du formulaire
$livreurId = $_POST['livreurId'] ?? '';
$password = $_POST['password'] ?? '';

// Vérification des champs vides
if (empty($livreurId) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
    exit;
}

try {
    // Recherche du livreur dans la base de données
    $stmt = $db->prepare("SELECT * FROM livreurs WHERE livreur_id = :livreur_id");
    $stmt->execute([':livreur_id' => $livreurId]);
    $livreur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si le livreur existe et que le mot de passe correspond
    if ($livreur && $password === $livreur['mdp']) {
        // Authentification réussie
        $_SESSION['livreur_id'] = $livreur['livreur_id'];
        $_SESSION['livreur_nom'] = $livreur['nom'];
        $_SESSION['livreur_prenom'] = $livreur['prenom'];
        
        echo json_encode(['success' => true]);
    } else {
        // Authentification échouée
        echo json_encode(['success' => false, 'message' => 'Identifiant ou mot de passe incorrect']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la vérification des identifiants']);
}