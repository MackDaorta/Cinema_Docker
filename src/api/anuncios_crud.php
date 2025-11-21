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
            $stmt = $pdo->prepare("SELECT * FROM Anuncio WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(['success' => true, 'anuncio' => $stmt->fetch()]);
        } else {
            $stmt = $pdo->query("SELECT * FROM Anuncio ORDER BY vigencia DESC");
            echo json_encode(['success' => true, 'anuncios' => $stmt->fetchAll()]);
        }
        exit;
    }

    // --- ELIMINAR (DELETE) ---
    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id'])) throw new Exception("ID requerido");

        $stmt = $pdo->prepare("DELETE FROM Anuncio WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    // --- CREAR O EDITAR (POST) ---
    if ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $link = $_POST['link'] ?? '';
        $vigencia = !empty($_POST['vigencia']) ? $_POST['vigencia'] : null; // Permitir nulos
        
        $imagenNombre = $_POST['imagen_actual'] ?? '';
        
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('ads_') . '.' . $ext;
            // Ruta de guardado
            $destino = __DIR__ . '/../uploads/anuncios/' . $nuevoNombre;
            
            // Crear carpeta si no existe (opcional, pero recomendado)
            if (!is_dir(dirname($destino))) mkdir(dirname($destino), 0777, true);

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $imagenNombre = $nuevoNombre;
            }
        }

        if ($id) {
            $sql = "UPDATE Anuncio SET nombre=?, tipo=?, link=?, vigencia=?, imagen=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $tipo, $link, $vigencia, $imagenNombre, $id]);
        } else {
            $sql = "INSERT INTO Anuncio (id, nombre, tipo, link, vigencia, imagen) VALUES (UUID(), ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $tipo, $link, $vigencia, $imagenNombre]);
        }

        echo json_encode(['success' => true]);
        exit;
    }

} catch (Exception $e) {
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>