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

// Cargar géneros desde la BD
$generos = [];
$error = '';

try {
    $generos = $pdo->query("SELECT id, nombre, descripcion FROM Genero ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error al cargar los géneros: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestión de géneros - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/admin_generos.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Gestión de géneros</h2>
  
  <div class="form-container">
    <h3>Agregar Nuevo Género</h3>
    <form id="productForm" method="POST" enctype="multipart/form-data" action="admin_generos_procesar.php">
      <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
      
      <div class="form-group">
        <label for="nombre">Nombre del género:</label>
        <input type="text" id="nombre" name="nombre" required />
      </div>
      
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"></textarea>
      </div>

      <button type="submit" class="btn-action">Agregar género</button>
      
      <?php if (!empty($error)): ?>
        <p style="color: red; margin-top: 10px"><?php echo h($error); ?></p>
      <?php endif; ?>
    </form>
  </div>
  
  <div class="generos-list">
    <h2>Lista de géneros</h2>
    <div id="generosContainer">
      <?php if (!empty($generos)): ?>
        <?php foreach ($generos as $genero): ?>
          <div class="genero-item">
            <div class="genero-info">
              <h4><?php echo h($genero['nombre']); ?></h4>
              <?php if (!empty($genero['descripcion'])): ?>
                <p><?php echo h($genero['descripcion']); ?></p>
              <?php endif; ?>
            </div>
            <div class="actions">
              <a href="admin_generos_editar.php?id=<?php echo h($genero['id']); ?>" class="btn-edit">Editar</a>
              <form action="admin_generos_eliminar.php" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este género?');">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="id" value="<?php echo h($genero['id']); ?>">
                <button type="submit" class="btn-delete">Eliminar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay géneros registrados todavía.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
