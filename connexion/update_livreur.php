<?php
header('Content-Type: application/json');
session_start();

// Vérifier si le livreur est connecté
if (!isset($_SESSION['livreur'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mettre à jour le statut du livreur
    $stmt = $db->prepare("UPDATE livreurs SET statut = :statut WHERE livreur_id = :livreur_id");
    $stmt->execute([
        ':statut' => $data['statut'],
        ':livreur_id' => $data['livreur_id']
    ]);
    
    // Mettre à jour aussi la session
    $_SESSION['livreur']['statut'] = $data['statut'];
    
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}