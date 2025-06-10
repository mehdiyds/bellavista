<?php
// supprimer_table.php

// Vérifier si l'ID de la table est passé en paramètre
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer la requête de suppression
    $table_id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM tables WHERE table_id = '$table_id'";

    // Exécuter la requête et vérifier le résultat
    if ($conn->query($sql)) {
        // Redirection vers la page de gestion avec un message de succès
        header("Location: gestion_tables.php?success=La table a été supprimée avec succès");
        exit();
    } else {
        // Redirection avec un message d'erreur
        header("Location: gestion_tables.php?error=Erreur lors de la suppression de la table: ".$conn->error);
        exit();
    }

    $conn->close();
} else {
    // Si aucun ID n'est spécifié, rediriger avec un message d'erreur
    header("Location: gestion_tables.php?error=ID de table non spécifié");
    exit();
}
?>