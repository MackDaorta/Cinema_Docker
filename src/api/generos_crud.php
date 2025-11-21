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
            $stmt = $pdo->prepare("SELECT * FROM Genero WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(['success' => true, 'genero' => $stmt->fetch()]);
        } else {
            $stmt = $pdo->query("SELECT * FROM Genero ORDER BY nombre ASC");
            echo json_encode(['success' => true, 'generos' => $stmt->fetchAll()]);
        }
        exit;
    }

    // --- ELIMINAR (DELETE) ---
    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id'])) throw new Exception("ID requerido");

        $stmt = $pdo->prepare("DELETE FROM Genero WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    // --- CREAR O EDITAR (POST) ---
    if ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $nombre = trim($_POST['nombre']);
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($nombre)) throw new Exception("El nombre es obligatorio");

        if ($id) {
            $stmt = $pdo->prepare("UPDATE Genero SET nombre=?, descripcion=? WHERE id=?");
            $stmt->execute([$nombre, $descripcion, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO Genero (id, nombre, descripcion) VALUES (UUID(), ?, ?)");
            $stmt->execute([$nombre, $descripcion]);
        }

        echo json_encode(['success' => true]);
        exit;
    }

} catch (Exception $e) {
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>