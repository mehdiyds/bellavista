<?php
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $table_id = $data['table_id'] ?? null;

    if (!$table_id) {
        throw new Exception('ID de table manquant');
    }

    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    $conn->set_charset("utf8mb4");

    $stmt = $conn->prepare("UPDATE tables SET statut = 'disponible' WHERE table_id = ?");
    $stmt->bind_param("i", $table_id);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Erreur lors de la mise à jour');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>