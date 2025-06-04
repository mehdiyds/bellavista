<?php
header('Content-Type: application/json');
session_start();

// Activer le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit;
}

$livreurId = $_POST['livreurId'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($livreurId) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM livreurs WHERE livreur_id = :livreur_id");
    $stmt->execute([':livreur_id' => $livreurId]);
    $livreur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livreur && $password === $livreur['mdp']) {
        $_SESSION['livreur'] = [
            'id' => $livreur['livreur_id'],
            'nom' => $livreur['nom'],
            'prenom' => $livreur['prenom'],
            'telephone' => $livreur['telephone'],
            'statut' => $livreur['statut']
        ];
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Identifiant ou mot de passe incorrect',
            'debug' => [
                'livreur_trouve' => $livreur ? true : false,
                'password_match' => $livreur && ($password === $livreur['mdp'])
            ]
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur lors de la vérification des identifiants',
        'error' => $e->getMessage()
    ]);
}