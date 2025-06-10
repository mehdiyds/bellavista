<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Produit - Espace Admin</title>
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
        .edit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .edit-btn:hover {
            background-color: #2980b9;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
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
        .edit-form {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-btn">Retour à l'admin</a>
        <h1>Modifier un Produit</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $conn = new mysqli('localhost', 'root', '', 'bellavista');
        
        // Base URL pour les images
        $base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";
        
        // Traitement du formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_produit'])) {
            try {
                $produit_id = $_POST['produit_id'];
                $nom = $_POST['nom'];
                $description = $_POST['description'];
                $prix = $_POST['prix'];
                $caracteristiques = $_POST['caracteristiques'];
                
                // Gestion de l'upload d'image
                $image_path = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    // Chemin absolu du dossier uploads
                    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bellavista/uploads/produits/';
                    
                    // Créer le dossier s'il n'existe pas
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    
                    // Nettoyer le nom du fichier
                    $filename = preg_replace('/\s+/', '_', basename($_FILES['image']['name']));
                    $target_file = $target_dir . $filename;
                    
                    // Vérifier le type de fichier
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array($imageFileType, $allowed_types)) {
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                            $image_path = 'uploads/produits/' . $filename; // Chemin relatif pour la BDD
                            
                            // Supprimer l'ancienne image si elle existe
                            $old_image = $conn->query("SELECT image FROM produits WHERE produit_id = $produit_id")->fetch_assoc()['image'];
                            if ($old_image && file_exists($_SERVER['DOCUMENT_ROOT'] . '/bellavista/' . $old_image)) {
                                unlink($_SERVER['DOCUMENT_ROOT'] . '/bellavista/' . $old_image);
                            }
                        } else {
                            throw new Exception("Erreur lors du téléchargement de l'image.");
                        }
                    } else {
                        throw new Exception("Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
                    }
                }
                
                // Préparation de la requête SQL
                if ($image_path) {
                    $stmt = $conn->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, caracteristiques = ?, image = ? WHERE produit_id = ?");
                    $stmt->bind_param("ssdssi", $nom, $description, $prix, $caracteristiques, $image_path, $produit_id);
                } else {
                    $stmt = $conn->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, caracteristiques = ? WHERE produit_id = ?");
                    $stmt->bind_param("ssdsi", $nom, $description, $prix, $caracteristiques, $produit_id);
                }
                
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success">Produit modifié avec succès!</div>';
                    // Rafraîchir les données après modification
                    $produit_details = $conn->query("SELECT * FROM produits WHERE produit_id = " . intval($produit_id))->fetch_assoc();
                } else {
                    throw new Exception("Erreur lors de la modification: " . $stmt->error);
                }
                
                $stmt->close();
            } catch (Exception $e) {
                echo '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        
        // Récupérer les produits avec possibilité de recherche
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $produit_id = isset($_GET['produit_id']) ? intval($_GET['produit_id']) : null;
        
        $sql = "SELECT p.*, c.nom AS categorie_nom 
                FROM produits p 
                JOIN categories c ON p.id_cat = c.id_cat";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE p.nom LIKE ? OR p.description LIKE ? OR c.nom LIKE ?";
            $searchTerm = '%' . $search . '%';
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        $produits = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Formater les chemins d'images pour l'affichage
        foreach ($produits as &$produit) {
            if (!empty($produit['image'])) {
                $produit['image_display'] = $base_url . $produit['image'];
            }
        }
        unset($produit);
        
        // Si un produit spécifique est sélectionné, récupérer ses détails
        $produit_details = null;
        if ($produit_id) {
            $produit_details = $conn->query("SELECT * FROM produits WHERE produit_id = $produit_id")->fetch_assoc();
            if ($produit_details && !empty($produit_details['image'])) {
                $produit_details['image_display'] = $base_url . $produit_details['image'];
            }
        }
        ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Produit modifié avec succès!
            </div>
        <?php endif; ?>

        <div class="search-container">
            <input type="text" name="search" class="search-input" 
                   placeholder="Rechercher par nom, description ou catégorie" 
                   value="<?= htmlspecialchars($search) ?>">
            <?php if (!empty($search)): ?>
                <a href="modifier_produit.php" class="reset-search">Réinitialiser</a>
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
                                <span class="price"><?= number_format($produit['prix'], 2) ?> DT</span>
                                <a href="?produit_id=<?= $produit['produit_id'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="edit-btn">Modifier</a>
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
        
        <?php if ($produit_details): ?>
            <div class="edit-form">
                <h2>Modifier: <?= htmlspecialchars($produit_details['nom']) ?></h2>
                
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="produit_id" value="<?= $produit_details['produit_id'] ?>">
                    
                    <div class="form-group">
                        <label for="nom">Nom du produit:</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($produit_details['nom']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required><?= htmlspecialchars($produit_details['description']) ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="prix">Prix (DT):</label>
                        <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?= htmlspecialchars($produit_details['prix']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="caracteristiques">Caractéristiques:</label>
                        <input type="text" id="caracteristiques" name="caracteristiques" value="<?= htmlspecialchars($produit_details['caracteristiques']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image du produit:</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if (!empty($produit_details['image'])): ?>
                            <div>
                                <p>Image actuelle:</p>
                                <img src="<?= $produit_details['image_display'] ?>" class="preview-image">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="modifier_produit" class="submit-btn">Enregistrer les modifications</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

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
            
            // Supprimer le produit_id si on fait une nouvelle recherche
            currentUrl.searchParams.delete('produit_id');
            
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
</body>
</html>