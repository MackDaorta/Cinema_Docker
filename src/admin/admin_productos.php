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

// Cargar productos desde la BD
$productos = [];
$error = '';

try {
    $productos = $pdo->query("SELECT id, nombre, descripcion, precio, imagen, categoria FROM Producto ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error al cargar los productos: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestión de Productos - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/admin_productos.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Gestión de Productos</h2>
  
  <div class="form-container">
    <h3>Agregar Nuevo Producto</h3>
    <form id="productForm" method="POST" enctype="multipart/form-data" action="admin_productos_procesar.php">
      <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
      
      <div class="form-group">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" required>
      </div>
      
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
      </div>
      
      <div class="form-group">
        <label for="precio">Precio (Ej: 15.50):</label>
        <input type="number" step="0.01" id="precio" name="precio" required>
      </div>
      
      <div class="form-group">
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
      </div>
      
      <div class="form-group">
        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria" required>
          <option value="">Seleccione la categoría</option>
          <option value="COMBO">Combo</option>
          <option value="POPCORN">Popcorn</option>
          <option value="BEBIDA">Bebida</option>
          <option value="SNACK">Snack</option>
          <option value="COLECCIONABLES">Coleccionables</option>
          <option value="OTRO">Otro</option>
        </select>
      </div>
      
      <button type="submit" class="btn-action">Agregar Producto</button>
      
      <?php if (!empty($error)): ?>
        <p style="color: red; margin-top: 10px;"><?php echo h($error); ?></p>
      <?php endif; ?>
    </form>
  </div>

  <div class="products-list">
    <h2>Lista de Productos</h2>
    <div id="productsContainer">
      <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
          <?php
            $img = $producto['imagen'] ?? '';
            $imgUrl = '';
            
            if (is_object($img)) {
              $imgUrl = getVal($img, 'url') ?? ($img->url ?? '');
            } elseif (is_array($img)) {
              $imgUrl = $img['url'] ?? '';
            } else {
              $imgUrl = $img ?? '';
            }
            
            $nombre = $producto['nombre'] ?? '';
            $precio = $producto['precio'] ?? '';
            $categoria = $producto['categoria'] ?? '';
            $id = $producto['id'] ?? '';
          ?>
          <div class="product-item">
            <div class="product-content">
              <?php if (!empty($imgUrl)): ?>
                <img src="<?php echo h($imgUrl); ?>" alt="<?php echo h($nombre); ?>" class="product-image">
              <?php endif; ?>
              <div class="product-info">
                <h4><?php echo h($nombre); ?></h4>
                <p><strong>Categoría:</strong> <?php echo h($categoria); ?></p>
                <p><strong>Precio:</strong> S/. <?php echo h(number_format((float)$precio, 2, '.', '')); ?></p>
              </div>
            </div>
            <div class="actions">
              <a href="admin_productos_editar.php?id=<?php echo h($id); ?>" class="btn-edit">Editar</a>
              <form action="admin_productos_eliminar.php" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="id" value="<?php echo h($id); ?>">
                <button type="submit" class="btn-delete">Eliminar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay productos registrados todavía.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>