<?php
// Définir la base URL comme dans index.php
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";

// Connexion à la base de données
$host = 'localhost';
$dbname = 'bellavista';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traitement de la suppression
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
        $id_cat = $_POST['id_cat'];
        
        // Récupérer la catégorie
        $stmt = $pdo->prepare("SELECT id_cat, image FROM categories WHERE id_cat = ?");
        $stmt->execute([$id_cat]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            // Supprimer la catégorie
            $pdo->prepare("DELETE FROM categories WHERE id_cat = ?")->execute([$id_cat]);
            
            // Supprimer l'image si elle existe
            if (!empty($category['image'])) {
                // Convertir le chemin relatif en chemin absolu
                $relative_path = str_replace($base_url, '', $category['image']);
                $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/bellavista/' . $relative_path;
                
                if (file_exists($absolute_path)) {
                    unlink($absolute_path);
                }
            }
        }
        
        header("Location: supprimer_categorie.php?success=1");
        exit();
    }

    // Récupérer les catégories avec le bon format d'image
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les chemins d'images comme dans index.php
    foreach ($categories as &$category) {
        if (!empty($category['image'])) {
            $category['image'] = $base_url . $category['image'];
        }
    }
    unset($category);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une catégorie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .category-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
            transition: transform 0.3s;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-image {
            height: 200px;
            overflow: hidden;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .category-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .category-info {
            padding: 15px;
            text-align: center;
        }
        .category-info h3 {
            margin: 0 0 10px;
            color: #333;
        }
        .category-info p {
            color: #666;
            margin: 0 0 15px;
            font-size: 14px;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .success-message {
            background-color: #2ecc71;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 20px;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .no-image {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-btn">Retour à l'admin</a>
        <h1>Supprimer une catégorie</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                Catégorie supprimée avec succès!
            </div>
        <?php endif; ?>

        <div class="categories-grid">
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <div class="category-image">
                            <?php if (!empty($category['image']) && file_exists($category['image'])): ?>
                                <img src="<?= $category['image'] ?>" alt="<?= htmlspecialchars($category['nom']) ?>">
                            <?php else: ?>
                                <span class="no-image">Image non disponible</span>
                            <?php endif; ?>
                        </div>
                        <div class="category-info">
                            <h3><?= htmlspecialchars($category['nom']) ?></h3>
                            <p><?= htmlspecialchars($category['description']) ?></p>
                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie? Cette action est irréversible.');">
                                <input type="hidden" name="id_cat" value="<?= $category['id_cat'] ?>">
                                <button type="submit" name="supprimer" class="delete-btn">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune catégorie disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>