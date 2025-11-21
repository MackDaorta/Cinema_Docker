<?php
ob_start();
require_once '../security/admin_check.php';
require_once '../config/conexionDB.php';
ob_end_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM Producto WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(['success' => true, 'producto' => $stmt->fetch()]);
        } else {
            $stmt = $pdo->query("SELECT * FROM Producto ORDER BY categoria, nombre");
            echo json_encode(['success' => true, 'productos' => $stmt->fetchAll()]);
        }
        exit;
    }

    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];
        
        // Lógica para el checkbox disponible
        // Si el checkbox está marcado, $_POST['disponible'] existe. Si no, no.
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        $imagen = $_POST['imagen_actual'] ?? '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('prod_') . '.' . $ext;
            // Asegura la ruta correcta
            move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../uploads/productos/' . $nombreArchivo);
            $imagen = $nombreArchivo;
        }

        if ($id) {
            $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, imagen=?, categoria=?, disponible=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria, $disponible, $id]);
        } else {
            $sql = "INSERT INTO Producto (id, nombre, descripcion, precio, imagen, categoria, disponible) VALUES (UUID(), ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria, $disponible]);
        }
        echo json_encode(['success' => true]);
        exit;
    }

} catch (Exception $e) {
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>