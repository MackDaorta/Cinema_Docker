<?php

require_once '../app/Models/SalaModel.php'; 
require_once '../app/Controllers/SalaController.php'; 


require_once '../config/conexionDB.php'; 


use App\Controllers\SalaController;

header('Content-Type: application/json');

try {
    
    $controller = new SalaController($pdo);
    $salas = $controller->obtenerSalas(); 

    
    echo json_encode(['success' => true, 'salas' => $salas]);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>