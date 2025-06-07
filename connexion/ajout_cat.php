<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'bellavista');
if ($conn->connect_error) {
    die("<div class='alert-error'>Échec de connexion à la base de données: " . $conn->connect_error . "</div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage des entrées
    $nom = trim($conn->real_escape_string($_POST['nom']));
    $description = trim($conn->real_escape_string($_POST['description']));
    
    // Validation des champs obligatoires
    if (empty($nom)) {
        $error = "Le nom de la catégorie est obligatoire";
    } elseif (strlen($nom) > 50) {
        $error = "Le nom ne doit pas dépasser 50 caractères";
    } else {
        // Traitement du fichier image
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/bellavista/uploads/categories/';
            
            if (!file_exists($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    $error = "Impossible de créer le dossier de destination";
                }
            }
            
            if (!isset($error)) {
                $filename = uniqid() . '_' . preg_replace('/[^\w\.]/', '_', $_FILES['image']['name']);
                $target_file = $target_dir . $filename;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($imageFileType, $allowed_types)) {
                    $error = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés";
                } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $error = "Erreur lors de l'enregistrement du fichier. Vérifiez les permissions";
                } else {
                    $image = 'uploads/categories/' . $filename;
                }
            }
        } else {
            $error = "Une image est obligatoire pour la catégorie";
        }
        
        // Insertion en base si aucune erreur
        if (!isset($error)) {
            try {
                $sql = "INSERT INTO categories (nom, description, image) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("sss", $nom, $description, $image);
                    if ($stmt->execute()) {
                        $success = "Catégorie ajoutée avec succès!";
                        $_POST = array();
                    }
                    $stmt->close();
                } else {
                    $error = "Erreur de préparation de la requête: " . $conn->error;
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error = "Erreur: Une catégorie avec le nom '$nom' existe déjà";
                } else {
                    $error = "Erreur lors de l'insertion: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Catégorie</title>
    <link rel="stylesheet" href="style.css">
    <style>
      .description{
        width: 100%;
      }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter une catégorie</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom de la catégorie *</label>
                <input type="text" id="nom" name="nom" required 
                       value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>"
                       maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Image *</label>
                <input type="file" id="image" name="image" required accept="image/*">
                <small>Formats acceptés: JPG, PNG, GIF (Max 2MB)</small>
            </div>
            
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
</html>