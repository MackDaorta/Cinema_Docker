<?php
require_once('../config/conexionDB.php');

$data = []; // Lista de todos las peliculas

try {
    //Consulta todas las peliculas por fecha de estreno

    $sql_peliculas = "SELECT id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno 
                      FROM Pelicula 
                      WHERE fecha_estreno <= CURDATE() 
                      ORDER BY fecha_estreno DESC";
    $stmt_peliculas = $pdo->query($sql_peliculas);

    $peliculas = $stmt_peliculas->fetchAll();
    
    // Condicional si no encuentra peliculas
    if (empty($peliculas)) {
        echo json_encode(['peliculas' => []]);
        exit;
    }

   
    
    // SQL para obtener todos los generos asignados a una pelicula
    $sql_generos = "SELECT pg.pelicula_id, g.nombre AS genero_nombre
                    FROM Pelicula_generos pg
                    JOIN Genero g ON pg.genero_id = g.id";
    $stmt_generos = $pdo->query($sql_generos);
    $generos_relacionados = $stmt_generos->fetchAll();

    // SQL para obtener todas las salas de una pelicula
    $sql_salas = "SELECT ps.pelicula_id, s.nombre AS sala_nombre
                  FROM Pelicula_salas ps
                  JOIN Sala s ON ps.sala_id = s.id";
    $stmt_salas = $pdo->query($sql_salas);
    $salas_relacionadas = $stmt_salas->fetchAll();

    //Unioon de M2M
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

    $data['peliculas'] = $peliculas;

} 
//Si algo falla se mostrara esto
catch (\PDOException $e) {
    
    http_response_code(500);
    echo json_encode(['error' => 'Error en la cosulta : ' . $e->getMessage()]);
    exit;
}

// Devuelve los datos en formato JSON
echo json_encode($data);
?>