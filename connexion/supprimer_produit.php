<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'bellavista';
$username = 'root';
$password = '';

// Base URL pour les images
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traitement de la suppression
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
        $produit_id = $_POST['produit_id'];
        
        // Récupérer le chemin de l'image avant suppression
        $stmt = $pdo->prepare("SELECT image FROM produits WHERE produit_id = ?");
        $stmt->execute([$produit_id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Suppression du produit
        $stmt = $pdo->prepare("DELETE FROM produits WHERE produit_id = ?");
        $stmt->execute([$produit_id]);
        
        // Supprimer le fichier image s'il existe
        if (!empty($produit['image'])) {
            // Construire le chemin absolu du fichier
            $image_path = $_SERVER['DOCUMENT_ROOT'] . '/bellavista/' . $produit['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        header("Location: supprimer_produit.php?success=1");
        exit();
    }

    // Récupérer les produits avec possibilité de recherche
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    $sql = "SELECT p.*, c.nom AS categorie_nom 
            FROM produits p 
            JOIN categories c ON p.id_cat = c.id_cat";
    
    $params = [];
    
    if (!empty($search)) {
        $sql .= " WHERE p.nom LIKE ? OR p.description LIKE ? OR c.nom LIKE ?";
        $searchTerm = '%' . $search . '%';
        $params = [$searchTerm, $searchTerm, $searchTerm];
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les chemins d'images pour l'affichage
    foreach ($produits as &$produit) {
        if (!empty($produit['image'])) {
            $produit['image_display'] = $base_url . $produit['image'];
        }
    }
    unset($produit);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un produit</title>
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
        .search-container {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .search-input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .reset-search {
            padding: 10px 15px;
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .reset-search:hover {
            background-color: #7f8c8d;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            height: 200px;
            overflow: hidden;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .product-info {
            padding: 15px;
        }
        .product-info h3 {
            margin: 0 0 10px;
            color: #333;
        }
        .product-info p {
            color: #666;
            margin: 0 0 10px;
            font-size: 14px;
        }
        .product-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }
        .price {
            font-weight: bold;
            color: #c8a97e;
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
        .category-tag {
            background-color: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .no-image {
            color: #999;
            font-style: italic;
        }
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
    <script>
        // Fonction pour la recherche dynamique
        function performSearch() {
            const searchInput = document.querySelector('.search-input');
            const searchTerm = searchInput.value.trim();
            const currentUrl = new URL(window.location.href);
            
            if (searchTerm) {
                currentUrl.searchParams.set('search', searchTerm);
            } else {
                currentUrl.searchParams.delete('search');
            }
            
            window.location.href = currentUrl.toString();
        }
        
        // Délai pour éviter des requêtes excessives
        let searchTimer;
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(performSearch, 500); // 500ms de délai
            });
            
            // Permettre la soumission avec Entrée
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimer);
                    performSearch();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-btn">Retour à l'admin</a>
        <h1>Supprimer un produit</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                Produit supprimé avec succès!
            </div>
        <?php endif; ?>

        <div class="search-container">
            <input type="text" name="search" class="search-input" 
                   placeholder="Rechercher par nom, description ou catégorie" 
                   value="<?= htmlspecialchars($search) ?>">
            <?php if (!empty($search)): ?>
                <a href="supprimer_produit.php" class="reset-search">Réinitialiser</a>
            <?php endif; ?>
        </div>

        <div class="products-grid">
            <?php if (count($produits) > 0): ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($produit['image'])): ?>
                                <img src="<?= $produit['image_display'] ?? '' ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                            <?php else: ?>
                                <span class="no-image">Image non disponible</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="category-tag"><?= htmlspecialchars($produit['categorie_nom']) ?></span>
                            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                            <p><?= htmlspecialchars($produit['description']) ?></p>
                            <div class="product-details">
                                <span class="price"><?= number_format($produit['prix'], 2) ?> DH</span>
                                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit? Cette action est irréversible.');">
                                    <input type="hidden" name="produit_id" value="<?= $produit['produit_id'] ?>">
                                    <button type="submit" name="supprimer" class="delete-btn">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">
                    <?= empty($search) ? 'Aucun produit disponible.' : 'Aucun résultat trouvé pour "' . htmlspecialchars($search) . '"' ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>