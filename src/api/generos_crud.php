<?php

require_once '../app/Models/GeneroModel.php'; 
require_once '../app/Controllers/AdminGeneroController.php'; 

require_once '../security/admin_check.php';
require_once '../config/conexionDB.php'; 


use App\Controllers\AdminGeneroController;


ob_start();
ob_end_clean();
header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];

$input = [];
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
}


$controller = new AdminGeneroController($pdo);

$response = $controller->handleRequest(
    $method,
    $input,             
    $_GET,             
    $_POST             
);


echo json_encode($response);
?>