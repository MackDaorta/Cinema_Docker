<?php
require_once('../config/conexionDB.php');

$data = []; // Lista que almacena la informacion de la consulta

try {
    

    // SQL para ver todos los Sliders
    $stmt_sliders = $pdo->query("SELECT nombre, imagen, link FROM Anuncio WHERE tipo = 'SLIDER'");
    $data['sliders'] = $stmt_sliders->fetchAll();

    // SQL para ver todas las promociones
    $stmt_promos = $pdo->query("SELECT nombre, imagen, link FROM Anuncio WHERE tipo = 'PROMOCION' ");
    $data['promociones'] = $stmt_promos->fetchAll();

} 
//Si algo falla salda esto
catch (\PDOException $e) {
    
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    exit;
}

// retornar JSON
echo json_encode($data);
?>