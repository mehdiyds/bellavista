<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    $conn->set_charset("utf8mb4");

    $sql = "SELECT * FROM tables WHERE statut != 'disponible'";
    $result = $conn->query($sql);

    $tables = [];
    while ($row = $result->fetch_assoc()) {
        $tables[] = $row;
    }

    echo json_encode($tables);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>