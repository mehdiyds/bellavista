<?php
// archive_commande.php

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=bellavista;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer l'ID de la commande à annuler
    $commande_id = $_POST['commande_id'] ?? null;
    $raison = $_POST['raison'] ?? null;
    
    if ($commande_id) {
        // Démarrer une transaction
        $db->beginTransaction();
        
        // 1. Récupérer les données de la commande
        $stmt = $db->prepare("SELECT c.*, cl.nom as nom_client, cl.telephone, cl.adresse 
                             FROM commandes c
                             JOIN clients cl ON c.client_id = cl.client_id
                             WHERE c.commande_id = ?");
        $stmt->execute([$commande_id]);
        $commande = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($commande) {
            // 2. Insérer dans l'historique
            $insert = $db->prepare("INSERT INTO historique 
                                   (commande_id, client_id, nom_client, telephone, adresse, commande, 
                                    montant_total, montant_paye, reste, date_commande, 
                                    statut_avant_annulation, raison_annulation)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $insert->execute([
                $commande['commande_id'],
                $commande['client_id'],
                $commande['nom_client'],
                $commande['telephone'],
                $commande['adresse'],
                $commande['commande'],
                $commande['montant_total'],
                $commande['montant_paye'],
                $commande['reste'],
                $commande['date_commande'],
                $commande['statut'],
                $raison
            ]);
            
            // 3. Supprimer la commande (ou la marquer comme annulée)
            // Option 1: Supprimer complètement
            // $db->prepare("DELETE FROM commandes WHERE commande_id = ?")->execute([$commande_id]);
            
            // Option 2: Juste changer le statut (recommandé)
            $db->prepare("UPDATE commandes SET statut = 'annulée' WHERE commande_id = ?")
               ->execute([$commande_id]);
            
            // Valider la transaction
            $db->commit();
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Commande non trouvée']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de commande manquant']);
    }
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}