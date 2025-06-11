<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Catégorie - Espace Admin</title>
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
        }
        .category-info h3 {
            margin: 0 0 10px;
            color: #333;
        }
        .category-info p {
            color: #666;
            margin: 0 0 10px;
            font-size: 14px;
        }
        .category-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
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
        <h1>Modifier une Catégorie</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $conn = new mysqli('localhost', 'root', '', 'bellavista');
        
        // Base URL pour les images
        $base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";
        
        // Traitement du formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_categorie'])) {
            try {
                $id_cat = $_POST['id_cat'];
                $nom = $_POST['nom'];
                $description = $_POST['description'];
                
                // Gestion de l'upload d'image
                $image_path = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    // Chemin absolu du dossier uploads
                    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bellavista/uploads/categories/';
                    
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
                            $image_path = 'uploads/categories/' . $filename; // Chemin relatif pour la BDD
                            
                            // Supprimer l'ancienne image si elle existe
                            $old_image = $conn->query("SELECT image FROM categories WHERE id_cat = $id_cat")->fetch_assoc()['image'];
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
                    $stmt = $conn->prepare("UPDATE categories SET nom = ?, description = ?, image = ? WHERE id_cat = ?");
                    $stmt->bind_param("sssi", $nom, $description, $image_path, $id_cat);
                } else {
                    $stmt = $conn->prepare("UPDATE categories SET nom = ?, description = ? WHERE id_cat = ?");
                    $stmt->bind_param("ssi", $nom, $description, $id_cat);
                }
                
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success">Catégorie modifiée avec succès!</div>';
                    // Rafraîchir les données après modification
                    $categorie_details = $conn->query("SELECT * FROM categories WHERE id_cat = " . intval($id_cat))->fetch_assoc();
                } else {
                    throw new Exception("Erreur lors de la modification: " . $stmt->error);
                }
                
                $stmt->close();
            } catch (Exception $e) {
                echo '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        
        // Récupérer les catégories avec possibilité de recherche
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $id_cat = isset($_GET['id_cat']) ? intval($_GET['id_cat']) : null;
        
        $sql = "SELECT * FROM categories";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE nom LIKE ? OR description LIKE ?";
            $searchTerm = '%' . $search . '%';
            $params = [$searchTerm, $searchTerm];
        }
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        $categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Formater les chemins d'images pour l'affichage
        foreach ($categories as &$categorie) {
            if (!empty($categorie['image'])) {
                $categorie['image_display'] = $base_url . $categorie['image'];
            }
        }
        unset($categorie);
        
        // Si une catégorie spécifique est sélectionnée, récupérer ses détails
        $categorie_details = null;
        if ($id_cat) {
            $categorie_details = $conn->query("SELECT * FROM categories WHERE id_cat = $id_cat")->fetch_assoc();
            if ($categorie_details && !empty($categorie_details['image'])) {
                $categorie_details['image_display'] = $base_url . $categorie_details['image'];
            }
        }
        ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Catégorie modifiée avec succès!
            </div>
        <?php endif; ?>

        <div class="search-container">
            <input type="text" name="search" class="search-input" 
                   placeholder="Rechercher par nom ou description" 
                   value="<?= htmlspecialchars($search) ?>">
            <?php if (!empty($search)): ?>
                <a href="modifier_categorie.php" class="reset-search">Réinitialiser</a>
            <?php endif; ?>
        </div>

        <div class="categories-grid">
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $categorie): ?>
                    <div class="category-card">
                        <div class="category-image">
                            <?php if (!empty($categorie['image'])): ?>
                                <img src="<?= $categorie['image_display'] ?? '' ?>" alt="<?= htmlspecialchars($categorie['nom']) ?>">
                            <?php else: ?>
                                <span class="no-image">Image non disponible</span>
                            <?php endif; ?>
                        </div>
                        <div class="category-info">
                            <h3><?= htmlspecialchars($categorie['nom']) ?></h3>
                            <p><?= htmlspecialchars($categorie['description']) ?></p>
                            <div class="category-actions">
                                <a href="?id_cat=<?= $categorie['id_cat'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="edit-btn">Modifier</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">
                    <?= empty($search) ? 'Aucune catégorie disponible.' : 'Aucun résultat trouvé pour "' . htmlspecialchars($search) . '"' ?>
                </p>
            <?php endif; ?>
        </div>
        
        <?php if ($categorie_details): ?>
            <div class="edit-form">
                <h2>Modifier: <?= htmlspecialchars($categorie_details['nom']) ?></h2>
                
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_cat" value="<?= $categorie_details['id_cat'] ?>">
                    
                    <div class="form-group">
                        <label for="nom">Nom de la catégorie:</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($categorie_details['nom']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required><?= htmlspecialchars($categorie_details['description']) ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image de la catégorie:</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if (!empty($categorie_details['image'])): ?>
                            <div>
                                <p>Image actuelle:</p>
                                <img src="<?= $categorie_details['image_display'] ?>" class="preview-image">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="modifier_categorie" class="submit-btn">Enregistrer les modifications</button>
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
            
            // Supprimer l'id_cat si on fait une nouvelle recherche
            currentUrl.searchParams.delete('id_cat');
            
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