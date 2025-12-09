<?php

require_once '../app/Models/SalaModel.php'; 
require_once '../app/Controllers/AdminSalaController.php'; 


require_once '../security/admin_check.php';
require_once '../config/conexionDB.php'; 


use App\Controllers\AdminSalaController;

ob_start();
ob_end_clean();
header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];
$input = [];
if ($method === 'DELETE' || $method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
}

$controller = new AdminSalaController($pdo);

$response = $controller->handleRequest(
    $method,
    $input,             
    $_GET,              
    $_POST,             
    $_FILES             
);

echo json_encode($response);
?>