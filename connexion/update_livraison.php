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
    
    // Mettre à jour le statut de la livraison
    $stmt = $db->prepare("UPDATE livraisons SET statut = :statut WHERE livraison_id = :livraison_id");
    $stmt->execute([
        ':statut' => $data['statut'],
        ':livraison_id' => $data['livraison_id']
    ]);
    
    // Mettre à jour aussi le statut de la commande associée
    $stmt = $db->prepare("
        UPDATE commandes c
        JOIN livraisons l ON c.commande_id = l.commande_id
        SET c.statut = 'livrée'
        WHERE l.livraison_id = :livraison_id
    ");
    $stmt->execute([':livraison_id' => $data['livraison_id']]);
    
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}