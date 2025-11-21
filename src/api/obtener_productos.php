<?php
require_once '../config/conexionDB.php';

$data = [];

try {
    // Obtenemos todos los productos disponibles
    $stmt = $pdo->query("SELECT * FROM Producto WHERE disponible = 1 ORDER BY categoria, nombre");
    $productos = $stmt->fetchAll();

    // Agrupamos los productos por categoría para facilitar el uso en el JS
    $agrupados = [];
    foreach ($productos as $prod) {
        $agrupados[$prod->categoria][] = $prod;
    }

    echo json_encode(['success' => true, 'productos' => $agrupados]);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>