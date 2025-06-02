<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bellavista', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie!";
    
    // Testez une requête simple
    $tables = $pdo->query("SHOW TABLES")->fetchAll();
    print_r($tables);
} catch (PDOException $e) {
    die("ERREUR DB: " . $e->getMessage());
}
?>