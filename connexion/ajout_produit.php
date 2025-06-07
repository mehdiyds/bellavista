<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'bellavista');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialiser les variables de message
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valider et nettoyer les entrées
    $nom = $conn->real_escape_string(trim($_POST['nom']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $prix = floatval($_POST['prix']);
    $id_cat = intval($_POST['categorie']);
    $caracteristiques = $conn->real_escape_string(trim($_POST['caracteristiques']));
    
    // Chemin absolu du dossier uploads
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bellavista/uploads/produits/';
    
    // Créer le dossier s'il n'existe pas
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = preg_replace('/\s+/', '_', basename($_FILES['image']['name']));
        $target_file = $target_dir . $filename;
        
        // Vérifier le type de fichier
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = 'uploads/produits/' . $filename; // Chemin relatif pour la BDD
            } else {
                $error = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $error = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        }
    }
    
    if (!isset($error)) {
        // Vérifier que les champs obligatoires sont remplis
        if (empty($nom) || empty($description) || $prix <= 0 || $id_cat <= 0) {
            $error = "Tous les champs obligatoires doivent être remplis correctement.";
        } else {
            $sql = "INSERT INTO produits (nom, description, prix, id_cat, caracteristiques, image) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                $error = "Erreur de préparation de la requête: " . $conn->error;
            } else {
                $stmt->bind_param("ssdiss", $nom, $description, $prix, $id_cat, $caracteristiques, $image);
                
                if ($stmt->execute()) {
                    $success = "Produit ajouté avec succès!";
                } else {
                    $error = "Erreur lors de l'ajout du produit: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Récupérer les catégories
$categories = $conn->query("SELECT id_cat, nom FROM categories");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles spécifiques à la page d'ajout */
        .add-product-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .add-product-title {
            text-align: center;
            margin-bottom: 30px;
            color: #8B4513;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #5D4037;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #8B4513;
            outline: none;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }
        
        .submit-btn {
            background-color: #27ae60;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #219653;
            transform: translateY(-2px);
        }
        
        .cancel-btn {
            background-color: #e74c3c;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .cancel-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .format-hint {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="add-product-container">
        <h1 class="add-product-title">Ajouter un nouveau produit</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="ajout_produit.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom du produit:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (DH):</label>
                <input type="number" id="prix" name="prix" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="categorie">Catégorie:</label>
                <select id="categorie" name="categorie" required>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id_cat']; ?>">
                            <?php echo htmlspecialchars($cat['nom']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="caracteristiques">Caractéristiques (JSON):</label>
                <textarea id="caracteristiques" name="caracteristiques" required placeholder='{"caffeineLevel": "medium", "size": "regular", "temp": "hot", "prepTime": "5 mins"}'></textarea>
                <span class="format-hint">Format JSON avec les clés: caffeineLevel, size, temp, prepTime</span>
            </div>
            
            <div class="form-group">
                <label for="image">Image du produit:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <div class="form-actions">
                <button type="button" class="cancel-btn" id="cancel-btn">Annuler</button>
                <button type="submit" class="submit-btn">Ajouter le produit</button>
            </div>
        </form>
    </div>

    <script>
        // Gestion du bouton Annuler
        document.getElementById('cancel-btn').addEventListener('click', function() {
            window.location.href = 'gestion_produits.php'; // Remplacez par la page appropriée
        });
    </script>
</body>
</html>