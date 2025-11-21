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

// Cargar datos necesarios desde la DB para rellenar formularios/listados
try {
    $generos = $pdo->query("SELECT id, nombre FROM Genero ORDER BY nombre ASC")->fetchAll();
} catch (Exception $e) {
    $generos = [];
}
try {
    $salas = $pdo->query("SELECT id, nombre FROM Sala ORDER BY nombre ASC")->fetchAll();
} catch (Exception $e) {
    $salas = [];
}
try {
    // Trae las películas (puedes ampliar para traer relaciones)
    $peliculas = $pdo->query("SELECT id, nombre, restriccion, duracion_minutos AS duracion, fecha_estreno, sinopsis, imagen FROM Pelicula ORDER BY nombre ASC")->fetchAll();
} catch (Exception $e) {
    $peliculas = [];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Inicio - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/admin_peliculas.css">
</head>
<body>
<div class="centro">
  <h2 class="titulo">Gestión de películas</h2>
  <div class="form-container">
    <form id="productForm" method="POST" enctype="multipart/form-data" action="">
      <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
      <div class="form-group">
        <label for="nombre">Nombre de la película:</label>
        <input type="text" id="nombre" name="nombre" required />
      </div>
      <div class="form-group">
        <label for="sinopsis">Sinopsis:</label>
        <textarea id="sinopsis" name="sinopsis" required></textarea>
      </div>
      <div class="form-group">
        <label for="duracion">Duración (minutos):</label>
        <input type="text" id="duracion" name="duracion" required />
      </div>
      <div class="form-group">
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" required />
      </div>
      <div class="form-group">
        <label for="fecha_estreno">Fecha de estreno:</label>
        <input type="date" id="fecha_estreno" name="fecha_estreno" required />
      </div>
      <div class="from-group">
        <label for="restriccion">Restricción</label>
        <select id="restriccion" name="restriccion" required>
          <option value="">Seleccione la restricción</option>
          <option value="APT">APT</option>
          <option value="+14">+14</option>
          <option value="+18">+18</option>
        </select>
      </div>
      <div class="form-group">
        <label for="generos">Género</label>
        <select id="generos" name="generos[]" multiple required>
          <option value="">Seleccione el género</option>
          <?php if (!empty($generos) && is_array($generos)): ?>
            <?php foreach ($generos as $genero): ?>
              <option value="<?php echo h(getVal($genero,'id') ?? $genero['id'] ?? ''); ?>"><?php echo h(getVal($genero,'nombre') ?? $genero['nombre'] ?? ''); ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
        <small> Mantén CTRL para seleccionar más de un género</small>
      </div>
      <div class="form-group">
        <label for="salas">Sala</label>
        <select id="salas" name="salas[]" multiple required>
          <option value="">Seleccione la sala</option>
          <?php if (!empty($salas) && is_array($salas)): ?>
            <?php foreach ($salas as $sala): ?>
              <option value="<?php echo h(getVal($sala,'id') ?? $sala['id'] ?? ''); ?>"><?php echo h(getVal($sala,'nombre') ?? $sala['nombre'] ?? ''); ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <button type="submit" class="btn-action">Agregar Película</button>
      <?php if (!empty($error)): ?>
        <p style="color: red; margin-top: 10px"><?php echo h($error); ?></p>
      <?php endif; ?>
    </form>
  </div>

  <div class="peliculas-list">
    <h2>Lista de Películas</h2>
    <div id="peliculasContainer">
      <?php if (!empty($peliculas) && is_array($peliculas)): ?>
        <?php foreach ($peliculas as $pelicula): ?>
          <?php
            $imgUrl = $pelicula['imagen'] ?? $pelicula->imagen ?? '';
          ?>
          <div class="pelicula-item">
            <span class="peliculas-info">
              <?php if (!empty($imgUrl)): ?>
                <img src="<?php echo h($imgUrl); ?>" alt="<?php echo h($pelicula['nombre'] ?? ''); ?>" class="pelicula-image" />
              <?php endif; ?>
              <?php echo h($pelicula['nombre'] ?? $pelicula->nombre ?? ''); ?> <br>
              <?php echo h($pelicula['duracion'] ?? $pelicula->duracion ?? ''); ?> <br>
              <?php echo h($pelicula['restriccion'] ?? $pelicula->restriccion ?? ''); ?> <br>
              <?php echo h($pelicula['fecha_estreno'] ?? $pelicula->fecha_estreno ?? ''); ?> <br>
              <?php echo h($pelicula['sinopsis'] ?? $pelicula->sinopsis ?? ''); ?> <br>
            </span>
            <div class="actions">
              <!-- Enlace a la página de edición: usa id en la querystring -->
              <a href="admin_peliculas_editar.php?id=<?php echo h($pelicula['id'] ?? $pelicula->id ?? ''); ?>" class="btn-edit">Editar</a>
              <form action="admin_peliculas_eliminar.php" method="POST" style="display: inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta película?');">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="id" value="<?php echo h($pelicula['id'] ?? $pelicula->id ?? ''); ?>">
                <button type="submit" class="btn-delete">Eliminar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay películas registradas todavía.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
