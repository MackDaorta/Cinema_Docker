<?php
// Especifica que la respuesta será JSON
header('Content-Type: application/json');

// 1. Configuración de la base de datos (Usamos el nombre del servicio 'db')
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

$data = []; // Contenedor final de datos

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // ===============================================
    // PASO 1: Obtener la lista principal de películas
    // ===============================================
    $sql_peliculas = "SELECT id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno 
                      FROM Pelicula 
                      WHERE fecha_estreno <= CURDATE() 
                      ORDER BY fecha_estreno DESC";
    $stmt_peliculas = $pdo->query($sql_peliculas);
    
    // CORRECCIÓN DE SINTAXIS: Uso correcto del array asociativo
    $peliculas = $stmt_peliculas->fetchAll();
    
    // Si no hay películas, terminamos aquí
    if (empty($peliculas)) {
        echo json_encode(['peliculas' => []]);
        exit;
    }

    // ===============================================
    // PASO 2: Obtener datos de Salas y Géneros relacionados
    // ===============================================
    
    // 2a. Obtener todos los Géneros por Película
    $sql_generos = "SELECT pg.pelicula_id, g.nombre AS genero_nombre
                    FROM Pelicula_generos pg
                    JOIN Genero g ON pg.genero_id = g.id";
    $stmt_generos = $pdo->query($sql_generos);
    $generos_relacionados = $stmt_generos->fetchAll();

    // 2b. Obtener todas las Salas por Película
    $sql_salas = "SELECT ps.pelicula_id, s.nombre AS sala_nombre
                  FROM Pelicula_salas ps
                  JOIN Sala s ON ps.sala_id = s.id";
    $stmt_salas = $pdo->query($sql_salas);
    $salas_relacionadas = $stmt_salas->fetchAll();

    // ===============================================
    // PASO 3: Estructurar los datos (Unir M2M a Películas)
    // ===============================================
    
    foreach ($peliculas as &$pelicula) {
        $pelicula['generos'] = [];
        $pelicula['salas'] = [];

        // Agregar géneros a la película actual
        foreach ($generos_relacionados as $relacion_genero) {
            if ($relacion_genero['pelicula_id'] === $pelicula['id']) {
                $pelicula['generos'][] = $relacion_genero['genero_nombre'];
            }
        }

        // Agregar salas a la película actual
        foreach ($salas_relacionadas as $relacion_sala) {
            if ($relacion_sala['pelicula_id'] === $pelicula['id']) {
                $pelicula['salas'][] = $relacion_sala['sala_nombre'];
            }
        }
    }
    unset($pelicula); // Romper la referencia al último elemento (&)

    $data['peliculas'] = $peliculas;

} catch (\PDOException $e) {
    // Manejo de errores
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
    exit;
}

// 4. Devolver los datos codificados en JSON
echo json_encode($data);
?>