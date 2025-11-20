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

$sala = null;
$nombreVal = '';
$descripcionVal = '';
$imgUrl = '';
$error = '';

$id = $_GET['id'] ?? null;

if (!$id) {
    $error = 'ID de sala no proporcionado';
} else {
    try {
        // Obtener sala
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, imagen FROM Sala WHERE id = ?");
        $stmt->execute([$id]);
        $sala = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sala) {
            $error = 'Sala no encontrada';
        } else {
            $nombreVal = $sala['nombre'] ?? '';
            $descripcionVal = $sala['descripcion'] ?? '';
            
            // Imagen puede ser string, array o objeto
            $img = $sala['imagen'] ?? null;
            if (is_object($img)) {
                $imgUrl = getVal($img, 'url') ?? ($img->url ?? '');
            } elseif (is_array($img)) {
                $imgUrl = $img['url'] ?? '';
            } else {
                $imgUrl = $img ?? '';
            }
        }
    } catch (Exception $e) {
        $error = 'Error al cargar sala: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Sala - <?php echo !empty($sala) ? h($nombreVal) : 'Cinemark'; ?></title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/admin_salas.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Editar Sala</h2>

  <?php if (!empty($error)): ?>
    <div style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
      <p><?php echo h($error); ?></p>
      <a href="admin_salas.php" class="btn-action">Volver al listado</a>
    </div>
  <?php elseif (!empty($sala)): ?>
    <div class="form-container">
      <form method="POST" enctype="multipart/form-data" action="admin_salas_editar_procesar.php">
        <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
        <input type="hidden" name="id" value="<?php echo h($sala['id']); ?>">

        <div class="form-group">
          <label for="nombre">Nombre de la Sala:</label>
          <input type="text" id="nombre" name="nombre" value="<?php echo h($nombreVal); ?>" required>
        </div>

        <div class="form-group">
          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" name="descripcion" required><?php echo h($descripcionVal); ?></textarea>
        </div>

        <div class="form-group">
          <label>Imagen Actual:</label>
          <?php if (!empty($imgUrl)): ?>
            <div style="margin-bottom: 8px;">
              <img src="<?php echo h($imgUrl); ?>" alt="<?php echo h($nombreVal); ?>" height="100" style="border-radius: 5px;">
            </div>
          <?php else: ?>
            <p style="color: #aaa;">Sin imagen</p>
          <?php endif; ?>
          <label for="imagen_nueva">Cambiar Imagen (opcional):</label>
          <input type="file" id="imagen_nueva" name="imagen" accept="image/*">
        </div>

        <button type="submit" class="btn-action">Guardar Cambios</button>
        <a href="admin_salas.php" class="btn-cancel">Cancelar</a>
      </form>
    </div>
  <?php endif; ?>
</div>
</body>
</html>