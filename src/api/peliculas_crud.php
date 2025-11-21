<?php
ob_start();
require_once '../security/admin_check.php';
require_once '../config/conexionDB.php';
ob_end_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'options') {
        $stmtSalas = $pdo->query("SELECT id, nombre FROM Sala ORDER BY nombre");
        $stmtGeneros = $pdo->query("SELECT id, nombre FROM Genero ORDER BY nombre");
        echo json_encode(['success' => true, 'salas' => $stmtSalas->fetchAll(), 'generos' => $stmtGeneros->fetchAll()]);
        exit;
    }

    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("DELETE FROM Pelicula WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $sinopsis = $_POST['sinopsis'];
        $duracion = $_POST['duracion_minutos'];
        $fecha = $_POST['fecha_estreno'];
        $restriccion = $_POST['restriccion'];
        $salas = $_POST['salas'] ?? [];
        $generos = $_POST['generos'] ?? [];

        $imagen = $_POST['imagen_actual'] ?? '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('peli_') . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../uploads/peliculas/' . $nombreArchivo);
            $imagen = $nombreArchivo;
        }

        if ($id) {
            $stmt = $pdo->prepare("UPDATE Pelicula SET nombre=?, sinopsis=?, imagen=?, restriccion=?, duracion_minutos=?, fecha_estreno=? WHERE id=?");
            $stmt->execute([$nombre, $sinopsis, $imagen, $restriccion, $duracion, $fecha, $id]);
            $pdo->prepare("DELETE FROM Pelicula_salas WHERE pelicula_id=?")->execute([$id]);
            $pdo->prepare("DELETE FROM Pelicula_generos WHERE pelicula_id=?")->execute([$id]);
        } else {
            $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
            $stmt = $pdo->prepare("INSERT INTO Pelicula (id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $nombre, $sinopsis, $imagen, $restriccion, $duracion, $fecha]);
        }

        $stmtS = $pdo->prepare("INSERT INTO Pelicula_salas (pelicula_id, sala_id) VALUES (?, ?)");
        foreach ($salas as $s) $stmtS->execute([$id, $s]);

        $stmtG = $pdo->prepare("INSERT INTO Pelicula_generos (pelicula_id, genero_id) VALUES (?, ?)");
        foreach ($generos as $g) $stmtG->execute([$id, $g]);

        echo json_encode(['success' => true]);
        exit;
    }

    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM Pelicula WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $pelicula = $stmt->fetch();
            $salasIds = $pdo->query("SELECT sala_id FROM Pelicula_salas WHERE pelicula_id = '{$_GET['id']}'")->fetchAll(PDO::FETCH_COLUMN);
            $generosIds = $pdo->query("SELECT genero_id FROM Pelicula_generos WHERE pelicula_id = '{$_GET['id']}'")->fetchAll(PDO::FETCH_COLUMN);
            echo json_encode(['success' => true, 'pelicula' => $pelicula, 'salas_ids' => $salasIds, 'generos_ids' => $generosIds]);
        } else {
            $stmt = $pdo->query("SELECT id, nombre, fecha_estreno FROM Pelicula ORDER BY fecha_estreno DESC");
            echo json_encode(['success' => true, 'peliculas' => $stmt->fetchAll()]);
        }
        exit;
    }
} catch (Exception $e) {
    if(!headers_sent()) http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>