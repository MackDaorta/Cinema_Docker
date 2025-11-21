<?php
// 1. Iniciar buffer para atrapar errores invisibles o espacios
ob_start();

require_once '../config/conexionDB.php';

// 2. Limpiar cualquier texto previo (warnings, espacios)
ob_end_clean();
header('Content-Type: application/json');

$data = [];

try {
    // --- PASO A: Obtener Películas Vigentes ---
    // Filtramos por fecha (que no sean viejas, opcional según tu lógica) o traemos todas
    $sql_peliculas = "SELECT id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno 
                      FROM Pelicula 
                      ORDER BY fecha_estreno DESC"; 
    $stmt = $pdo->query($sql_peliculas);
    $peliculas = $stmt->fetchAll(); // Retorna objetos (PDO::FETCH_OBJ)
    
    if (empty($peliculas)) {
        // Respondemos vacío pero con éxito
        echo json_encode(['success' => true, 'peliculas' => []]);
        exit;
    }

    // --- PASO B: Obtener Datos Relacionados (Salas y Géneros) ---
    
    // Géneros
    $sql_generos = "SELECT pg.pelicula_id, g.nombre AS genero_nombre
                    FROM Pelicula_generos pg
                    JOIN Genero g ON pg.genero_id = g.id";
    $generos_relacionados = $pdo->query($sql_generos)->fetchAll();

    // Salas
    $sql_salas = "SELECT ps.pelicula_id, s.nombre AS sala_nombre
                  FROM Pelicula_salas ps
                  JOIN Sala s ON ps.sala_id = s.id";
    $salas_relacionadas = $pdo->query($sql_salas)->fetchAll();

    // --- PASO C: Unir Datos ---
    
    foreach ($peliculas as $peli) {
        // CORRECCIÓN: Usamos -> para asignar propiedades al objeto
        $peli->generos = [];
        $peli->salas = [];

        // Unir Géneros
        foreach ($generos_relacionados as $rel_g) {
            // CORRECCIÓN: Usamos -> para acceder a propiedades
            if ($rel_g->pelicula_id === $peli->id) {
                $peli->generos[] = $rel_g->genero_nombre;
            }
        }

        // Unir Salas
        foreach ($salas_relacionadas as $rel_s) {
            // CORRECCIÓN: Usamos -> para acceder a propiedades
            if ($rel_s->pelicula_id === $peli->id) {
                $peli->salas[] = $rel_s->sala_nombre;
            }
        }
    }

    // --- PASO D: Respuesta Final con 'success' ---
    echo json_encode(['success' => true, 'peliculas' => $peliculas]);

} catch (Exception $e) {
    // Enviar error JSON válido si algo falla
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en servidor: ' . $e->getMessage()]);
}
?>