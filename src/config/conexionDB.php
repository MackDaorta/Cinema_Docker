<?php

if (!defined('MODO_HTML')) {
    header('Content-Type: application/json');
}

$host = 'db'; 
$db   = 'cine_db'; 
$user = 'admin'; 
$pass = '123'; 

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, 
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (\PDOException $e) {
    if (defined('MODO_HTML')) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>