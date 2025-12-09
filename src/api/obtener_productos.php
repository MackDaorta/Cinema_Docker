<?php

require_once '../app/Models/ProductoModel.php'; 
require_once '../app/Controllers/ProductoController.php'; 


require_once '../config/conexionDB.php'; 


use App\Controllers\ProductoController;

header('Content-Type: application/json');

try {
    
    $controller = new ProductoController($pdo);
    $productos_agrupados = $controller->obtenerYAgruparProductos();

    
    echo json_encode(['success' => true, 'productos' => $productos_agrupados]);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>