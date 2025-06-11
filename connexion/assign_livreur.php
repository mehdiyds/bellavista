<?php
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['livreur_id']) || !isset($data['commande_ids'])) {
        throw new Exception('Missing required parameters');
    }
    
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $conn->begin_transaction();
    
    $livreur_id = $conn->real_escape_string($data['livreur_id']);
    $commande_ids = array_map([$conn, 'real_escape_string'], $data['commande_ids']);
    
    // 1. Update livreur status to "en livraison"
    $stmt = $conn->prepare("UPDATE livreurs SET statut = 'en livraison' WHERE livreur_id = ?");
    $stmt->bind_param("i", $livreur_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating livreur status: " . $stmt->error);
    }
    $stmt->close();
    
    // 2. Assign commands to livreur
    foreach ($commande_ids as $commande_id) {
        // Check if livraison already exists
        $check = $conn->query("SELECT * FROM livraisons WHERE commande_id = '$commande_id'");
        if ($check->num_rows > 0) {
            // Update existing livraison
            $stmt = $conn->prepare("UPDATE livraisons SET livreur_id = ?, statut = 'assignée' WHERE commande_id = ?");
            $stmt->bind_param("ii", $livreur_id, $commande_id);
        } else {
            // Create new livraison
            $stmt = $conn->prepare("INSERT INTO livraisons (commande_id, livreur_id, statut) VALUES (?, ?, 'assignée')");
            $stmt->bind_param("ii", $commande_id, $livreur_id);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error assigning command: " . $stmt->error);
        }
        $stmt->close();
        
        // Update command status to "assignée"
        $stmt = $conn->prepare("UPDATE commandes SET statut = 'assignée' WHERE commande_id = ?");
        $stmt->bind_param("i", $commande_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating command status: " . $stmt->error);
        }
        $stmt->close();
    }
    
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Commands assigned successfully']);
    
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}