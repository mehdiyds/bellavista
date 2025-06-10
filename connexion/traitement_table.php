<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Récupération des données du formulaire
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];
    $statut = $_POST['statut'];
    $description = !empty($_POST['description']) ? $_POST['description'] : null;
    $caracteristiques = !empty($_POST['caracteristiques']) ? $_POST['caracteristiques'] : null;
    
    // Gestion de l'upload d'image
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/tables/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }
    
    // Insertion dans la base de données
    $stmt = $conn->prepare("INSERT INTO tables 
                          (numero, capacite, statut, description, caracteristiques, image)
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", 
        $numero,
        $capacite,
        $statut,
        $description,
        $caracteristiques,
        $image_path
    );
    
    if ($stmt->execute()) {
        header('Location: gestion_tables.php?success=1');
    } else {
        echo "Erreur: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: ajout_table.php');
}
?>