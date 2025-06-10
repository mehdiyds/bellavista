<?php
// Vérifier si l'ID de la table est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_tables.php?error=ID de table non spécifié");
    exit();
}

$table_id = $_GET['id'];

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'bellavista');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données de la table
$sql = "SELECT * FROM tables WHERE table_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $table_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: gestion_tables.php?error=Table non trouvée");
    exit();
}

$table = $result->fetch_assoc();
$stmt->close();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];
    $statut = $_POST['statut'];
    $description = $_POST['description'];
    $caracteristiques = $_POST['caracteristiques'];
    
    // Gestion de l'upload d'image
    $image = $table['image']; // Conserver l'image actuelle par défaut
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/tables/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Supprimer l'ancienne image si elle existe
            if (!empty($table['image']) && file_exists($table['image'])) {
                unlink($table['image']);
            }
            $image = $targetPath;
        }
    }
    
    // Mise à jour dans la base de données
    $sql = "UPDATE tables SET 
            numero = ?, 
            capacite = ?, 
            statut = ?, 
            description = ?, 
            caracteristiques = ?, 
            image = ?
            WHERE table_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssi", $numero, $capacite, $statut, $description, $caracteristiques, $image, $table_id);
    
    if ($stmt->execute()) {
        header("Location: gestion_tables.php?success=La table a été modifiée avec succès");
        exit();
    } else {
        $error = "Erreur lors de la modification : " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Table <?= $table['numero'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #8e44ad;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #8e44ad;
        }
        .btn-secondary {
            background-color: #3498db;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: #e74c3c;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier Table <?= htmlspecialchars($table['numero']) ?></h1>
        
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form action="modifier_table.php?id=<?= $table_id ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="numero">Numéro de table:</label>
                <input type="number" id="numero" name="numero" value="<?= htmlspecialchars($table['numero']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="capacite">Capacité:</label>
                <input type="number" id="capacite" name="capacite" value="<?= htmlspecialchars($table['capacite']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="statut">Statut:</label>
                <select id="statut" name="statut" required>
                    <option value="disponible" <?= $table['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="occupée" <?= $table['statut'] === 'occupée' ? 'selected' : '' ?>>Occupée</option>
                    <option value="réservée" <?= $table['statut'] === 'réservée' ? 'selected' : '' ?>>Réservée</option>
                    <option value="maintenance" <?= $table['statut'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($table['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="caracteristiques">Caractéristiques:</label>
                <textarea id="caracteristiques" name="caracteristiques"><?= htmlspecialchars($table['caracteristiques']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if (!empty($table['image'])): ?>
                    <p>Image actuelle:</p>
                    <img src="<?= htmlspecialchars($table['image']) ?>" class="image-preview">
                    <p><small><?= htmlspecialchars(basename($table['image'])) ?></small></p>
                <?php else: ?>
                    <p>Aucune image actuellement</p>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="gestion_tables.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>