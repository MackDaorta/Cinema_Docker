<?php
<?php
require_once '../security/admin_check.php';
require_once __DIR__ . '/../config/conexionDB.php';

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

// Cargar anuncios desde la BD
$anuncios = [];
$error = '';

try {
    $anuncios = $pdo->query("SELECT id, nombre, tipo, imagen, link, vigencia FROM Anuncio ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Error al cargar los anuncios: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestión de Anuncios y Promociones - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/anuncio.css">
</head>
<body>
<main class="main-content">
    <div class="form-container">
        <h2>Creación y Edición de Anuncios</h2>

        <form id="form-promocion" method="POST" enctype="multipart/form-data" action="admin_anuncios_procesar.php">
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
            
            <div class="form-group">
                <label for="nombre">Título del Anuncio (Ej: 2x1 en Confitería)</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingrese título" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Anuncio (Ubicación en Home)</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione dónde se mostrará</option>
                    <option value="SLIDER">Slider (Arriba en el Home)</option>
                    <option value="PROMOCION">Promoción (Abajo en el Home)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen">Subir Archivo de Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>
            
            <div class="form-group">
                <label for="link">Link</label>
                <input type="url" id="link" name="link">
            </div>

            <div class="form-group">
                <label for="vigencia">Fecha de Vigencia (opcional)</label>
                <input type="date" id="vigencia" name="vigencia">
            </div>
            
            <button type="submit" class="btn-action">Guardar Anuncio</button>
            
            <?php if (!empty($error)): ?>
                <p style="color: red; margin-top: 10px"><?php echo h($error); ?></p>
            <?php endif; ?>
        </form>

        <div class="promotions-list">
            <h2>Anuncios Activos</h2>
            
            <?php if (!empty($anuncios)): ?>
                <?php foreach ($anuncios as $anuncio): ?>
                    <div class="promotion-item">
                        <div class="anuncio-content">
                            <?php if (!empty($anuncio['imagen'])): ?>
                                <img src="<?php echo h($anuncio['imagen']); ?>" alt="<?php echo h($anuncio['nombre']); ?>" class="anuncio-image">
                            <?php endif; ?>
                            <div class="anuncio-info">
                                <h4><?php echo h($anuncio['nombre']); ?></h4>
                                <p><strong>Tipo:</strong> <?php echo h($anuncio['tipo']); ?></p>
                                <?php if (!empty($anuncio['vigencia'])): ?>
                                    <p><strong>Vigencia:</strong> <?php echo h(date('d/m/Y', strtotime($anuncio['vigencia']))); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($anuncio['link'])): ?>
                                    <p><strong>Link:</strong> <a href="<?php echo h($anuncio['link']); ?>" target="_blank"><?php echo h($anuncio['link']); ?></a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="admin_anuncios_editar.php?id=<?php echo h($anuncio['id']); ?>" class="btn-edit">Editar</a>
                            <form action="admin_anuncios_eliminar.php" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este anuncio?');">
                                <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
                                <input type="hidden" name="id" value="<?php echo h($anuncio['id']); ?>">
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay anuncios creados todavía.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>
