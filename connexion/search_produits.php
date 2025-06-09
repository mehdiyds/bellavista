<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set base URL
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";

// Database connection
$host = 'localhost';
$dbname = 'bellavista';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get search term from POST
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
    // Build SQL query
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
    
    // Format image paths
    foreach ($produits as &$produit) {
        if (!empty($produit['image'])) {
            $produit['image'] = $base_url . $produit['image'];
        }
    }
    unset($produit);

    // Output HTML
    if (count($produits) > 0) {
        foreach ($produits as $produit) {
            echo '<div class="product-card">';
            echo '<div class="product-image">';
            if (!empty($produit['image'])) {
                echo '<img src="' . $produit['image'] . '" alt="' . htmlspecialchars($produit['nom']) . '">';
            } else {
                echo '<span class="no-image">Image non disponible</span>';
            }
            echo '</div>';
            echo '<div class="product-info">';
            echo '<span class="category-tag">' . htmlspecialchars($produit['categorie_nom']) . '</span>';
            echo '<h3>' . htmlspecialchars($produit['nom']) . '</h3>';
            echo '<p>' . htmlspecialchars($produit['description']) . '</p>';
            echo '<div class="product-details">';
            echo '<span class="price">' . number_format($produit['prix'], 2) . ' DH</span>';
            echo '<form method="POST" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ce produit? Cette action est irréversible.\');">';
            echo '<input type="hidden" name="produit_id" value="' . $produit['produit_id'] . '">';
            echo '<button type="submit" name="supprimer" class="delete-btn">Supprimer</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p class="no-results">';
        echo empty($search) ? 'Aucun produit disponible.' : 'Aucun résultat trouvé pour "' . htmlspecialchars($search) . '"';
        echo '</p>';
    }

} catch (PDOException $e) {
    echo '<p class="no-results">Erreur de base de données: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>