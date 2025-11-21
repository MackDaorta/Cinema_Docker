<?php
// Usamos la conexión centralizada
require_once '../config/conexionDB.php';

$data = [];

try {
    // Consultamos todas las salas
    $stmt = $pdo->query("SELECT * FROM Sala ORDER BY nombre ASC");
    $salas = $stmt->fetchAll();

    echo json_encode(['success' => true, 'salas' => $salas]);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>