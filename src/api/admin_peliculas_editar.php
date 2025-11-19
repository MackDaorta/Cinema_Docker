<?php
require_once './admin/seguridad/admin_check.php';
require_once('../config/conexionDB.php');

$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true);

// Función validar datos correctos
function sanitizeData($data) {
    $data['nombre'] = trim($data['nombre'] ?? '');
    $data['duracion_minutos'] = filter_var($data['duracion_minutos'], FILTER_VALIDATE_INT);
    return $data;
}

try {
    switch ($method) {
        
        // -------------------------------------------
        // GET: Obtener una película específica (para editar)
        // -------------------------------------------
        case 'GET':
            $pelicula_id = $_GET['id'] ?? null;
            if (!$pelicula_id) {
                // Si no se proporciona ID, devolver todas las películas (uso interno para la tabla admin)
                $stmt = $pdo->query("SELECT id, nombre, restriccion, duracion_minutos, fecha_estreno FROM Pelicula ORDER BY nombre ASC");
                $peliculas = $stmt->fetchAll();
                
                // Aquí se podría enriquecer la respuesta con las salas y géneros relacionados (similar a obtener_peliculas.php)
                
                echo json_encode(['success' => true, 'peliculas' => $peliculas]);
                break;
            }

            // LECTURA DE UN REGISTRO ESPECÍFICO
            $stmt = $pdo->prepare("SELECT * FROM Pelicula WHERE id = ?");
            $stmt->execute([$pelicula_id]);
            $pelicula = $stmt->fetch();

            if (!$pelicula) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pelicula not found']);
                exit;
            }

            // En una implementación real, aquí se añadirían las salas/géneros relacionados.
            echo json_encode(['success' => true, 'pelicula' => $pelicula]);
            break;

        // -------------------------------------------
        // POST: Crear nuevo registro
        // -------------------------------------------
        case 'POST':
            $data = sanitizeData($input);

            // Validación mínima (ej. el nombre es obligatorio)
            if (empty($data['nombre'])) {
                throw new Exception("Nombre de la película es obligatorio.");
            }

            // Sentencia para crear la Película
            $stmt = $pdo->prepare("INSERT INTO Pelicula (id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno) 
                                   VALUES (UUID(), ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['nombre'],
                $data['sinopsis'] ?? '',
                $data['imagen'] ?? 'default.jpg', // Se recomienda manejar la subida de archivos aparte
                $data['restriccion'],
                $data['duracion_minutos'],
                $data['fecha_estreno']
            ]);
            
            // Aquí se recuperaría el UUID (o el último ID insertado si fuera INT) si se necesitara para M2M
            // Dado que usamos UUID, es mejor recuperarlo de la base de datos si es necesario para el cliente.
            
            // Implementación de Lógica M2M (Ejemplo: Salas) 
            // if (isset($data['salas']) && is_array($data['salas'])) {
            //     $stmt_m2m = $pdo->prepare("INSERT INTO Pelicula_salas (pelicula_id, sala_id) VALUES (?, ?)");
            //     foreach ($data['salas'] as $sala_id) {
            //         $stmt_m2m->execute([$new_id, $sala_id]);
            //     }
            // }

            http_response_code(201); // Created
            echo json_encode(['success' => true, 'message' => 'Pelicula creada exitosamente.']);
            break;
            
        //Metodo PUT para actualizar una pelicula existente
        case 'PUT':
            $data = sanitizeData($input);
            $pelicula_id = $data['id'] ?? null;
            //Valda que tenga ID y nombre antes de actualizar 
            if (!$pelicula_id || empty($data['nombre'])) {
                throw new Exception("ID y Nombre son obligatorios para actualizar.");
            }
            $stmt = $pdo->prepare("UPDATE Pelicula SET nombre = ?, sinopsis = ?, imagen = ?, restriccion = ?, duracion_minutos = ?, fecha_estreno = ?
                                   WHERE id = ?");
            $stmt->execute([
                $data['nombre'],
                $data['sinopsis'] ?? '',
                $data['imagen'] ?? 'default.jpg',
                $data['restriccion'],
                $data['duracion_minutos'],
                $data['fecha_estreno'],
                $pelicula_id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Pelicula actualizada exitosamente.']);
            break;

        //Eliminar 
        case 'DELETE':
            $pelicula_id = $input['id'] ?? null;
            if (!$pelicula_id) {
                throw new Exception("Se requiere ID para eliminar.");
            }

            // Sentencia para eliminar la Película
            $stmt = $pdo->prepare("DELETE FROM Pelicula WHERE id = ?");
            $stmt->execute([$pelicula_id]);

            echo json_encode(['success' => true, 'message' => 'Pelicula eliminada exitosamente.']);
            break;

        //Manejo de otros metodos sin reconocer
        default:
            http_response_code(405); 
            echo json_encode(['success' => false, 'error' => 'Method not permitido.']);
            break;
    }
} catch (Exception $e) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>