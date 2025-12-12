<?php
// Si no está definido MODO_HTML, se envía la respuesta como JSON
if (!defined('MODO_HTML')) {
    header('Content-Type: application/json');
}

// Datos de conexión a la base de datos
$host = 'db'; 
$db   = 'cine_db'; 
$user = 'admin'; 
$pass = '123'; 

// Configuración del DSN y opciones de PDO
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lanza excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,   // Devuelve resultados como objetos
    PDO::ATTR_EMULATE_PREPARES   => false,   // Usa consultas preparadas reales
];

// Intenta conectarse a la base de datos
try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Crea la conexión PDO
} 
catch (\PDOException $e) {
        // Si hay error, muestra mensaje diferente según modo
    if (defined('MODO_HTML')) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
    http_response_code(500);  // Código HTTP de error interno
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>