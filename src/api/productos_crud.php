<?php
require_once '../security/admin_check.php';
require_once '../config/conexionDB.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// NOTA: Para subir archivos con AJAX, usamos POST incluso para editar.
// Diferenciaremos Crear vs Editar por la presencia de 'id'.

try {
    // --- ELIMINAR PRODUCTO (DELETE) ---
    if ($method === 'DELETE') {
        // Leer el body raw para DELETE
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        
        if (!$id) throw new Exception("ID requerido para eliminar");

        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Producto eliminado']);
        exit;
    }

    // --- CREAR O EDITAR (POST) ---
    if ($method === 'POST') {
        $id = $_POST['id'] ?? ''; // Si viene ID, es editar. Si no, crear.
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? 0;
        $categoria = $_POST['categoria'] ?? 'OTRO';
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        // Manejo de Imagen
        $imagenNombre = $_POST['imagen_actual'] ?? ''; // Mantener la anterior si no se sube nueva
        
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('prod_') . '.' . $ext;
            // Asegúrate de crear la carpeta: src/uploads/productos
            $destino = __DIR__ . '/../uploads/productos/' . $nuevoNombre;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $imagenNombre = $nuevoNombre;
            }
        }

        if ($id) {
            // ACTUALIZAR
            $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, imagen=?, categoria=?, disponible=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $imagenNombre, $categoria, $disponible, $id]);
            $mensaje = "Producto actualizado";
        } else {
            // CREAR
            $sql = "INSERT INTO Producto (id, nombre, descripcion, precio, imagen, categoria, disponible) VALUES (UUID(), ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $imagenNombre, $categoria, $disponible]);
            $mensaje = "Producto creado";
        }

        echo json_encode(['success' => true, 'message' => $mensaje]);
        exit;
    }

    // --- LEER UN PRODUCTO (GET para edición) ---
    if ($method === 'GET' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM Producto WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $producto = $stmt->fetch();
        echo json_encode(['success' => true, 'producto' => $producto]);
        exit;
    }
    
    // --- LEER TODOS (GET para tabla admin) ---
    if ($method === 'GET') {
         $stmt = $pdo->query("SELECT * FROM Producto ORDER BY categoria, nombre");
         $productos = $stmt->fetchAll();
         echo json_encode(['success' => true, 'productos' => $productos]);
         exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>