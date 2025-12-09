<?php

require_once '../app/Models/AnuncioModel.php'; 
require_once '../app/Controllers/AnuncioController.php'; 


require_once('../config/conexionDB.php'); 


use App\Controllers\AnuncioController;

header('Content-Type: application/json');

try {
   
    $controller = new AnuncioController($pdo);
    $data = $controller->obtenerAnunciosPublicos();

    
    echo json_encode(['success' => true] + $data);

} 
catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    exit;
}


?>