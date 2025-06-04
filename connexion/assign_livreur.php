<?php
header('Content-Type: application/json');
session_start();

// Vérifiez ici les permissions admin si nécessaire

$data = json_decode(file_get_contents('php://input'), true);

try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->beginTransaction();

    foreach ($data['commande_ids'] as $commande_id) {
        // Vérifier si la commande existe et n'est pas déjà assignée
        $stmt = $db->prepare("SELECT statut FROM commandes WHERE commande_id = ?");
        $stmt->execute([$commande_id]);
        $commande = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$commande || $commande['statut'] === 'livrée') {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Commande invalide ou déjà livrée']);
            exit;
        }

        // Créer la livraison
        $stmt = $db->prepare("
            INSERT INTO livraisons (commande_id, livreur_id, statut)
            VALUES (?, ?, 'assignée')
        ");
        $stmt->execute([$commande_id, $data['livreur_id']]);

        // Mettre à jour le statut de la commande
        $stmt = $db->prepare("UPDATE commandes SET statut = 'en livraison' WHERE commande_id = ?");
        $stmt->execute([$commande_id]);
    }

    // Mettre à jour le statut du livreur
    $stmt = $db->prepare("UPDATE livreurs SET statut = 'en livraison' WHERE livreur_id = ?");
    $stmt->execute([$data['livreur_id']]);

    $db->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}