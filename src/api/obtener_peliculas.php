<?php

require_once '../app/Models/PeliculaModel.php'; 
require_once '../app/Controllers/PeliculaController.php'; 

require_once '../config/conexionDB.php'; 


use App\Controllers\PeliculaController;


ob_start();
ob_end_clean();
header('Content-Type: application/json');

try {
    
    $controller = new PeliculaController($pdo);
    $peliculas_enriquecidas = $controller->obtenerPeliculasEnriquecidas();

    
    echo json_encode(['success' => true, 'peliculas' => $peliculas_enriquecidas]);

} catch (Exception $e) {
    
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en servidor: ' . $e->getMessage()]);
}
?>