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

$producto = null;
$nombreVal = '';
$descripcionVal = '';
$precioVal = '';
$precioFmt = '';
$categoriaVal = '';
$imgUrl = '';
$error = '';

$id = $_GET['id'] ?? null;

if (!$id) {
    $error = 'ID de producto no proporcionado';
} else {
    try {
        // Obtener producto
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, precio, imagen, categoria FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            $error = 'Producto no encontrado';
        } else {
            $nombreVal = $producto['nombre'] ?? '';
            $descripcionVal = $producto['descripcion'] ?? '';
            $precioVal = $producto['precio'] ?? '';
            $precioFmt = $precioVal !== '' ? number_format((float)$precioVal, 2, '.', '') : '';
            $categoriaVal = $producto['categoria'] ?? '';
            
            // imagen puede ser string, array o objeto con url
            $img = $producto['imagen'] ?? null;
            if (is_object($img)) {
                $imgUrl = getVal($img, 'url') ?? ($img->url ?? '');
            } elseif (is_array($img)) {
                $imgUrl = $img['url'] ?? '';
            } else {
                $imgUrl = $img ?? '';
            }
        }
    } catch (Exception $e) {
        $error = 'Error al cargar producto: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Producto - <?php echo !empty($producto) ? h($nombreVal) : 'Cinemark'; ?></title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/admin_productos.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Editar Producto</h2>

  <?php if (!empty($error)): ?>
    <div style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
      <p><?php echo h($error); ?></p>
      <a href="admin_productos.php" class="btn-action">Volver al listado</a>
    </div>
  <?php elseif (!empty($producto)): ?>
    <div class="form-container">
      <form method="POST" enctype="multipart/form-data" action="admin_productos_editar_procesar.php">
        <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
        <input type="hidden" name="id" value="<?php echo h($producto['id']); ?>">

        <div class="form-group">
          <label for="nombre">Nombre del Producto:</label>
          <input type="text" id="nombre" name="nombre" value="<?php echo h($nombreVal); ?>" required>
        </div>

        <div class="form-group">
          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" name="descripcion" required><?php echo h($descripcionVal); ?></textarea>
        </div>

        <div class="form-group">
          <label for="precio">Precio (Ej: 15.50):</label>
          <input type="number" step="0.01" id="precio" name="precio" value="<?php echo h($precioFmt); ?>" required>
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

        <div class="form-group">
          <label for="categoria">Categoría:</label>
          <select id="categoria" name="categoria" required>
            <option value="">Seleccione la categoría</option>
            <option value="COMBO" <?php echo $categoriaVal === 'COMBO' ? 'selected' : ''; ?>>Combo</option>
            <option value="POPCORN" <?php echo $categoriaVal === 'POPCORN' ? 'selected' : ''; ?>>Popcorn</option>
            <option value="BEBIDA" <?php echo $categoriaVal === 'BEBIDA' ? 'selected' : ''; ?>>Bebida</option>
            <option value="SNACK" <?php echo $categoriaVal === 'SNACK' ? 'selected' : ''; ?>>Snack</option>
            <option value="COLECCIONABLES" <?php echo $categoriaVal === 'COLECCIONABLES' ? 'selected' : ''; ?>>Coleccionables</option>
            <option value="OTRO" <?php echo $categoriaVal === 'OTRO' ? 'selected' : ''; ?>>Otro</option>
          </select>
        </div>

        <button type="submit" class="btn-action">Guardar Cambios</button>
        <a href="admin_productos.php" class="btn-cancel">Cancelar</a>

        <?php if (!empty($error)): ?>
          <p style="color: red; margin-top: 10px;"><?php echo h($error); ?></p>
        <?php endif; ?>
      </form>
    </div>
  <?php endif; ?>
</div>
</body>
</html>