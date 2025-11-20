<?php
require_once '../security/admin_check.php';
require_once __DIR__ . '/../config/conexionDB.php';

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

$anuncio = null;
$tipoVal = '';
$vigenciaVal = '';
$imagenUrl = '';
$error = '';

$id = $_GET['id'] ?? null;

if (!$id) {
    $error = 'ID de anuncio no proporcionado';
} else {
    try {
        // Obtener anuncio
        $stmt = $pdo->prepare("SELECT id, nombre, tipo, imagen, link, vigencia FROM Anuncio WHERE id = ?");
        $stmt->execute([$id]);
        $anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$anuncio) {
            $error = 'Anuncio no encontrado';
        } else {
            $tipoVal = $anuncio['tipo'] ?? '';
            $imagenUrl = $anuncio['imagen'] ?? '';
            
            // Formatear vigencia
            if (!empty($anuncio['vigencia'])) {
                $vigenciaVal = date('Y-m-d', strtotime($anuncio['vigencia']));
            }
        }
    } catch (Exception $e) {
        $error = 'Error al cargar anuncio: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Edición de Anuncio - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/style.css">
  <link rel="stylesheet" href="/static/administrator/admin.css">
  <link rel="stylesheet" href="/static/administrator/anuncio.css">
</head>
<body>
<main class="main-content">
    <div class="form-container">
        <h2>Edición de Anuncio</h2>

        <?php if (!empty($error)): ?>
            <div style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <p><?php echo h($error); ?></p>
                <a href="admin_anuncios.php" class="btn-action">Volver al listado</a>
            </div>
        <?php elseif (!empty($anuncio)): ?>

            <form id="form-promocion" method="POST" enctype="multipart/form-data" action="admin_anuncios_editar_procesar.php">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="id" value="<?php echo h($anuncio['id']); ?>">

                <div class="form-group">
                    <label for="nombre">Título del Anuncio:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese título" value="<?php echo h($anuncio['nombre']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de Anuncio (Ubicación en Home)</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Seleccione dónde se mostrará</option>
                        <option value="SLIDER" <?php echo $tipoVal === 'SLIDER' ? 'selected' : ''; ?>>Slider (Arriba en el Home)</option>
                        <option value="PROMOCION" <?php echo $tipoVal === 'PROMOCION' ? 'selected' : ''; ?>>Promoción (Abajo en el Home)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagen-file">Imagen Actual:</label>
                    <?php if (!empty($imagenUrl)): ?>
                        <div style="margin-bottom: 8px;">
                            <img src="<?php echo h($imagenUrl); ?>" alt="<?php echo h($anuncio['nombre']); ?>" height="100" style="border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                    <label for="imagen-file">Cambiar Imagen (opcional):</label>
                    <input type="file" id="imagen-file" name="imagen" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="link">Link:</label>
                    <input type="url" id="link" name="link" value="<?php echo h($anuncio['link'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="vigencia">Fecha de Vigencia (opcional):</label>
                    <input type="date" id="vigencia" name="vigencia" value="<?php echo h($vigenciaVal); ?>">
                </div>

                <button type="submit" class="btn-action">Guardar cambios</button>
                <a href="admin_anuncios.php" class="btn-cancel">Cancelar</a>
            </form>

        <?php endif; ?>

    </div>
</main>
</body>
</html>
