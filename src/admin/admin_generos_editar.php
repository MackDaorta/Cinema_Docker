<?php
require_once '../security/admin_check.php';
require_once __DIR__ . '/../config/conexionDB.php';

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function getVal($obj, $key){
    if (is_object($obj) && isset($obj->$key)) return $obj->$key;
    if (is_array($obj) && isset($obj[$key])) return $obj[$key];
    return null;
}

$genero = null;
$nombreVal = '';
$descripcionVal = '';
$error = '';

$id = $_GET['id'] ?? null;

if (!$id) {
    $error = 'ID de género no proporcionado';
} else {
    try {
        // Obtener género
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM Genero WHERE id = ?");
        $stmt->execute([$id]);
        $genero = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$genero) {
            $error = 'Género no encontrado';
        } else {
            $nombreVal = $genero['nombre'] ?? '';
            $descripcionVal = $genero['descripcion'] ?? '';
        }
    } catch (Exception $e) {
        $error = 'Error al cargar género: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Género - <?php echo !empty($genero) ? h($nombreVal) : 'Cinemark'; ?></title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/admin_generos.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Editar Género</h2>

  <?php if (!empty($error)): ?>
    <div style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
      <p><?php echo h($error); ?></p>
      <a href="admin_generos.php" class="btn-action">Volver al listado</a>
    </div>
  <?php elseif (!empty($genero)): ?>
    <div class="form-container">
      <form method="POST" enctype="multipart/form-data" action="admin_generos_editar_procesar.php">
        <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
        <input type="hidden" name="id" value="<?php echo h($genero['id']); ?>">

        <div class="form-group">
          <label for="nombre">Nombre del género:</label>
          <input type="text" id="nombre" name="nombre" value="<?php echo h($nombreVal); ?>" required>
        </div>

        <div class="form-group">
          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" name="descripcion"><?php echo h($descripcionVal); ?></textarea>
        </div>

        <button type="submit" class="btn-action">Guardar Cambios</button>
        <a href="admin_generos.php" class="btn-cancel">Cancelar</a>
      </form>
    </div>
  <?php endif; ?>
</div>
</body>
</html>