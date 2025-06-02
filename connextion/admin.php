<?php
session_start();

// Redirection si non admin
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit();
}

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'bellavista');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connexion DB avec gestion d'erreur
try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assignation à un livreur
    if (isset($_POST['assigner'], $_POST['livreur_id'], $_POST['commandes_ids'])) {
        $livreur_id = (int)$_POST['livreur_id'];
        $commandes_ids = array_map('intval', $_POST['commandes_ids']);
        
        if (!empty($commandes_ids)) {
            $placeholders = implode(',', array_fill(0, count($commandes_ids), '?'));
            $sql = "UPDATE commandes SET statut = 'assigned', livreur_id = ? 
                    WHERE id IN ($placeholders) AND statut = 'pending'";
            
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_merge([$livreur_id], $commandes_ids));
                $_SESSION['message'] = "Commandes assignées avec succès";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de l'assignation : " . $e->getMessage();
            }
        }
    }
    
    // Suppression de commandes
    if (isset($_POST['supprimer'], $_POST['commandes_ids'])) {
        $commandes_ids = array_map('intval', $_POST['commandes_ids']);
        
        if (!empty($commandes_ids)) {
            $placeholders = implode(',', array_fill(0, count($commandes_ids), '?'));
            
            try {
                $stmt = $pdo->prepare("DELETE FROM commandes WHERE id IN ($placeholders)");
                $stmt->execute($commandes_ids);
                $_SESSION['message'] = "Commandes supprimées avec succès";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
            }
        }
    }
    
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}

// Récupération des données
try {
    $commandes = $pdo->query("SELECT * FROM commandes WHERE statut = 'pending'")->fetchAll();
    $livreurs = $pdo->query("SELECT * FROM livreurs")->fetchAll();
} catch (PDOException $e) {
    die("Erreur de requête : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Bella Vista</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .actions { display: flex; gap: 15px; margin: 20px 0; align-items: center; }
        select, button { padding: 8px 12px; font-size: 16px; }
        button { cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 4px; }
        button[name="supprimer"] { background-color: #f44336; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background-color: #dff0d8; color: #3c763d; }
        .error { background-color: #f2dede; color: #a94442; }
    </style>
</head>
<body>
    <h1>Gestion des Commandes - Admin</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <form method="post">
        <?php if (!empty($commandes)): ?>
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Détails</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><input type="checkbox" name="commandes_ids[]" value="<?= $commande['id'] ?>"></td>
                        <td><?= htmlspecialchars($commande['id']) ?></td>
                        <td><?= htmlspecialchars($commande['details']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($commande['created_at'])) ?></td>
                        <td><?= htmlspecialchars($commande['statut']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="actions">
                <select name="livreur_id" required>
                    <option value="">-- Choisir un livreur --</option>
                    <?php foreach ($livreurs as $livreur): ?>
                    <option value="<?= $livreur['id'] ?>">
                        <?= htmlspecialchars($livreur['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" name="assigner">Assigner au livreur</button>
                <button type="submit" name="supprimer">Supprimer la sélection</button>
            </div>
        <?php else: ?>
            <p>Aucune commande en attente pour le moment.</p>
        <?php endif; ?>
    </form>

    <script>
        // Sélection/désélection de toutes les cases
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="commandes_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>
</html>