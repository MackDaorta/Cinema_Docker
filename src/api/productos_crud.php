<?php
ob_start();
require_once '../security/admin_check.php';
require_once '../config/conexionDB.php';
ob_end_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    // --- LISTAR (GET) ---
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

    // --- ELIMINAR (DELETE) ---
    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    // --- CREAR O EDITAR (POST) ---
    if ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];
        
        // Lógica para el checkbox disponible
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        // 1. Recuperar imagen actual (si existe)
        $imagen = $_POST['imagen_actual'] ?? '';

        // 2. Procesar NUEVA imagen si se subió una
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('prod_') . '.' . $ext;
            
            // Definir directorio destino
            $directorioDestino = __DIR__ . '/../uploads/productos/';

            // --- CORRECCIÓN: Crear carpeta si no existe ---
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }

            // Mover archivo
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorioDestino . $nombreArchivo)) {
                $imagen = $nombreArchivo; // Actualizamos la variable para la BD
            }
        }

        if ($id) {
            // UPDATE
            $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, imagen=?, categoria=?, disponible=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria, $disponible, $id]);
        } else {
            // INSERT
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
```

### ¿Qué corregí?
Agregué este bloque dentro de la lógica de subida de imagen:

```php
$directorioDestino = __DIR__ . '/../uploads/productos/';
if (!is_dir($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}