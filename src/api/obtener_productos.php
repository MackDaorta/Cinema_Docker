<?php
// Usamos la conexión centralizada
require_once '../config/conexionDB.php';

$data = [];

try {
    // 1. Obtener solo productos disponibles
    $stmt = $pdo->query("SELECT * FROM Producto WHERE disponible = 1 ORDER BY categoria ASC, nombre ASC");
    $productos = $stmt->fetchAll();

    // 2. Agrupar por Categoría
    // El resultado será: { "COMBO": [prod1, prod2], "BEBIDA": [prod3], ... }
    $agrupados = [];
    foreach ($productos as $prod) {
        $categoria = $prod->categoria;
        // Inicializar array si es la primera vez que vemos esta categoría
        if (!isset($agrupados[$categoria])) {
            $agrupados[$categoria] = [];
        }
        $agrupados[$categoria][] = $prod;
    }

    echo json_encode(['success' => true, 'productos' => $agrupados]);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>