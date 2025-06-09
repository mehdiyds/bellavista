<?php
// Définir la base URL
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";

// Connexion à la base de données
$host = 'localhost';
$dbname = 'bellavista';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer le terme de recherche
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
    // Construire la requête SQL
    $sql = "SELECT * FROM categories";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " WHERE nom LIKE ? OR description LIKE ?";
        $searchTerm = '%' . $search . '%';
        $params = [$searchTerm, $searchTerm];
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les chemins d'images
    foreach ($categories as &$category) {
        if (!empty($category['image'])) {
            $category['image'] = $base_url . $category['image'];
        }
    }
    unset($category);

    // Output the HTML
    if (count($categories) > 0) {
        foreach ($categories as $category) {
            echo '<div class="category-card">';
            echo '<div class="category-image">';
            if (!empty($category['image'])) {
                echo '<img src="' . $category['image'] . '" alt="' . htmlspecialchars($category['nom']) . '">';
            } else {
                echo '<span class="no-image">Image non disponible</span>';
            }
            echo '</div>';
            echo '<div class="category-info">';
            echo '<h3>' . htmlspecialchars($category['nom']) . '</h3>';
            echo '<p>' . htmlspecialchars($category['description']) . '</p>';
            echo '<form method="POST" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette catégorie?\');">';
            echo '<input type="hidden" name="id_cat" value="' . $category['id_cat'] . '">';
            echo '<button type="submit" name="supprimer" class="delete-btn">Supprimer</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p class="no-results">';
        echo empty($search) ? 'Aucune catégorie disponible.' : 'Aucun résultat trouvé pour "' . htmlspecialchars($search) . '"';
        echo '</p>';
    }

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>