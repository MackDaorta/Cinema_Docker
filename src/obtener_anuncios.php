<?php
header('Content-Type: application/json');

// 1. Configuración de la base de datos
$host = 'db';
$db   = 'cine_db'; 
$user = 'admin';             
$pass = '123';         

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$data = []; // Array para almacenar los datos 

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 2. Obtener Sliders
    $stmt_sliders = $pdo->query("SELECT nombre, imagen, link FROM Anuncio WHERE tipo = 'SLIDER' AND vigencia >= CURDATE()");
    $data['sliders'] = $stmt_sliders->fetchAll();

    // 3. Obtener Promociones
    $stmt_promos = $pdo->query("SELECT nombre, imagen, link FROM Anuncio WHERE tipo = 'PROMOCION' AND vigencia >= CURDATE()");
    $data['promociones'] = $stmt_promos->fetchAll();

} catch (\PDOException $e) {
    // Manejo de errores
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
    exit;
}

// 4. Devolver los datos codificados en JSON
echo json_encode($data);
?>