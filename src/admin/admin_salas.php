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

// Cargar salas desde la BD
$salas = [];
$error = '';

try {
    $salas = $pdo->query("SELECT id, nombre, descripcion, imagen FROM Sala ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error al cargar las salas: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestión de Salas - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/admin_salas.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Gestión de salas</h2>
  
  <div class="form-container">
    <h3>Agregar Nueva Sala</h3>
    <form id="productForm" method="POST" enctype="multipart/form-data" action="admin_salas_procesar.php">
      <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
      
      <div class="form-group">
        <label for="nombre">Nombre de la sala:</label>
        <input type="text" id="nombre" name="nombre" required />
      </div>
      
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
      </div>
      
      <div class="form-group">
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required />
      </div>

      <button type="submit" class="btn-action">Agregar Sala</button>
      
      <?php if (!empty($error)): ?>
        <p style="color: red; margin-top: 10px"><?php echo h($error); ?></p>
      <?php endif; ?>
    </form>
  </div>
  
  <div class="salas-list">
    <h2>Lista de Salas</h2>
    <div id="salasContainer">
      <?php if (!empty($salas) && is_array($salas)): ?>
        <?php foreach ($salas as $sala): ?>
          <?php
            $img = $sala['imagen'] ?? '';
            $imgUrl = '';
            
            if (is_object($img)) {
                $imgUrl = getVal($img, 'url') ?? ($img->url ?? '');
            } elseif (is_array($img)) {
                $imgUrl = $img['url'] ?? '';
            } else {
                $imgUrl = $img ?? '';
            }
            
            $nombre = $sala['nombre'] ?? '';
            $descripcion = $sala['descripcion'] ?? '';
            $id = $sala['id'] ?? '';
          ?>
          <div class="sala-item">
            <div class="sala-content">
              <?php if (!empty($imgUrl)): ?>
                <img src="<?php echo h($imgUrl); ?>" alt="<?php echo h($nombre); ?>" class="sala-image" />
              <?php endif; ?>
              <div class="sala-info">
                <h4><?php echo h($nombre); ?></h4>
                <p><?php echo h($descripcion); ?></p>
              </div>
            </div>
            <div class="actions">
              <a href="admin_salas_editar.php?id=<?php echo h($id); ?>" class="btn-edit">Editar</a>
              <form action="admin_salas_eliminar.php" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta sala?');">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="id" value="<?php echo h($id); ?>">
                <button type="submit" class="btn-delete">Eliminar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay salas registradas todavía.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>