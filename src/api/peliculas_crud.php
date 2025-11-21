<?php
// Iniciar buffer para capturar errores invisibles
ob_start();

require_once '../security/admin_check.php';
require_once '../config/conexionDB.php';

// Limpiar cualquier texto previo
ob_end_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    // --- 1. GET: DATOS AUXILIARES Y LISTAR ---
    if ($method === 'GET') {
        // Opción para llenar selects (action=options)
        if (isset($_GET['action']) && $_GET['action'] === 'options') {
            $stmtSalas = $pdo->query("SELECT id, nombre FROM Sala ORDER BY nombre");
            $stmtGeneros = $pdo->query("SELECT id, nombre FROM Genero ORDER BY nombre");
            echo json_encode(['success' => true, 'salas' => $stmtSalas->fetchAll(), 'generos' => $stmtGeneros->fetchAll()]);
            exit;
        }
        
        // Obtener una película específica
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM Pelicula WHERE id = ?");
            $stmt->execute([$id]);
            $pelicula = $stmt->fetch();

            // Obtener IDs relacionados
            $salasIds = $pdo->query("SELECT sala_id FROM Pelicula_salas WHERE pelicula_id = '$id'")->fetchAll(PDO::FETCH_COLUMN);
            $generosIds = $pdo->query("SELECT genero_id FROM Pelicula_generos WHERE pelicula_id = '$id'")->fetchAll(PDO::FETCH_COLUMN);

            echo json_encode([
                'success' => true, 
                'pelicula' => $pelicula,
                'salas_ids' => $salasIds,
                'generos_ids' => $generosIds
            ]);
            exit;
        } 
        
        // Listar todas
        else {
            $stmt = $pdo->query("SELECT id, nombre, fecha_estreno FROM Pelicula ORDER BY fecha_estreno DESC");
            echo json_encode(['success' => true, 'peliculas' => $stmt->fetchAll()]);
            exit;
        }
    }

    // --- 2. DELETE: ELIMINAR ---
    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        if (!$id) throw new Exception("ID requerido");

        $stmt = $pdo->prepare("DELETE FROM Pelicula WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    // --- 3. POST: CREAR O EDITAR ---
    if ($method === 'POST') {
        $id = $_POST['id'] ?? ''; 
        $nombre = $_POST['nombre'];
        $sinopsis = $_POST['sinopsis'];
        $duracion = $_POST['duracion_minutos'];
        $fecha = $_POST['fecha_estreno'];
        $restriccion = $_POST['restriccion'];
        
        $salasSeleccionadas = $_POST['salas'] ?? []; 
        $generosSeleccionados = $_POST['generos'] ?? [];

        // Manejo de Imagen
        $imagenNombre = $_POST['imagen_actual'] ?? '';
        
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('peli_') . '.' . $ext;
            
            // --- CORRECCIÓN: RUTA Y CREACIÓN DE CARPETA ---
            $directorioDestino = __DIR__ . '/../uploads/peliculas/';
            
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorioDestino . $nuevoNombre)) {
                $imagenNombre = $nuevoNombre;
            }
        }

        // Iniciar transacción para guardar datos y relaciones
        $pdo->beginTransaction();

        if ($id) {
            // UPDATE
            $sql = "UPDATE Pelicula SET nombre=?, sinopsis=?, imagen=?, restriccion=?, duracion_minutos=?, fecha_estreno=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $sinopsis, $imagenNombre, $restriccion, $duracion, $fecha, $id]);
            
            // Limpiar relaciones anteriores
            $pdo->prepare("DELETE FROM Pelicula_salas WHERE pelicula_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM Pelicula_generos WHERE pelicula_id = ?")->execute([$id]);
        } else {
            // INSERT (Generar UUID manual para usarlo en relaciones)
            // Generador simple de UUID v4 compatible
            $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );

            $sql = "INSERT INTO Pelicula (id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id, $nombre, $sinopsis, $imagenNombre, $restriccion, $duracion, $fecha]);
        }

        // Insertar Salas
        $stmtSala = $pdo->prepare("INSERT INTO Pelicula_salas (pelicula_id, sala_id) VALUES (?, ?)");
        foreach ($salasSeleccionadas as $salaId) {
            $stmtSala->execute([$id, $salaId]);
        }

        // Insertar Géneros
        $stmtGenero = $pdo->prepare("INSERT INTO Pelicula_generos (pelicula_id, genero_id) VALUES (?, ?)");
        foreach ($generosSeleccionados as $generoId) {
            $stmtGenero->execute([$id, $generoId]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Película guardada']);
        exit;
    }

} catch (Exception $e) {
    // Si hay error y la transacción estaba abierta, revertir
    if ($pdo->inTransaction()) $pdo->rollBack();
    
    // Limpiar buffer y enviar error 500 JSON
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>