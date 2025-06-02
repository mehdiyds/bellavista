<?php
session_start();
if (!isset($_SESSION['livreur_id'])) {
    header('Location: login_livreur.php');
    exit();
}

$livreur_id = $_SESSION['livreur_id'];

// Connexion DB
$pdo = new PDO('mysql:host=localhost;dbname=votre_db', 'user', 'password');

// Marquer comme livré
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['livrer'])) {
    $commande_id = (int)$_POST['commande_id'];
    $stmt = $pdo->prepare("UPDATE commandes SET statut = 'livré' WHERE id = ? AND livreur_id = ?");
    $stmt->execute([$commande_id, $livreur_id]);
}

// Récupération des commandes
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE livreur_id = ? AND statut = 'assigned'");
$stmt->execute([$livreur_id]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Livreur</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Commandes à Livrer</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Détails</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= $commande['id'] ?></td>
                <td><?= htmlspecialchars($commande['details']) ?></td>
                <td><?= $commande['created_at'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                        <button type="submit" name="livrer">Marquer comme livré</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>