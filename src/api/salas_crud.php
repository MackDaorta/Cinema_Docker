<?php
ob_start();
require_once '../security/admin_check.php';
require_once '../config/conexionDB.php';
ob_end_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    // --- LISTAR O VER UNO (GET) ---
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM Sala WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(['success' => true, 'sala' => $stmt->fetch()]);
        } else {
            $stmt = $pdo->query("SELECT * FROM Sala ORDER BY nombre ASC");
            echo json_encode(['success' => true, 'salas' => $stmt->fetchAll()]);
        }
        exit;
    }

    // --- ELIMINAR (DELETE) ---
    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id'])) throw new Exception("ID requerido");

        $stmt = $pdo->prepare("DELETE FROM Sala WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    // --- CREAR O EDITAR (POST) ---
    if ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        
        $imagenNombre = $_POST['imagen_actual'] ?? '';
        
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('sala_') . '.' . $ext;
            $destino = __DIR__ . '/../uploads/salas/' . $nuevoNombre;
            
            if (!is_dir(dirname($destino))) mkdir(dirname($destino), 0777, true);

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $imagenNombre = $nuevoNombre;
            }
        }

        if ($id) {
            $stmt = $pdo->prepare("UPDATE Sala SET nombre=?, descripcion=?, imagen=? WHERE id=?");
            $stmt->execute([$nombre, $descripcion, $imagenNombre, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO Sala (id, nombre, descripcion, imagen) VALUES (UUID(), ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $imagenNombre]);
        }

        echo json_encode(['success' => true]);
        exit;
    }

} catch (Exception $e) {
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>