<?php
// Suponiendo que ya tienes cargadas las variables:
// $pelicula, $generos (array), $salas (array), $error (string), y que la lógica de POST/CSRF está gestionada en tu controlador/php principal.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pelicula - <?= htmlspecialchars($pelicula['nombre']) ?></title>
    <link rel="stylesheet" href="/css/administrator/admin_peliculas.css">
</head>
<body>
<div class="centro">
    <h2 class="titulo">Editar Pelicula: <?= htmlspecialchars($pelicula['nombre']) ?></h2>
    <div class="form-container">
        <form id="productForm" method="POST" enctype="multipart/form-data">
            <!-- Aquí se recomienda poner el token de CSRF si tu framework PHP lo requiere -->
            <div class="form-group">
                <label for="nombre">Nombre de la pelicula:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($pelicula['nombre']) ?>" required />
            </div>
            <div class="form-group">
                <label for="sinopsis">Sinopsis:</label>
                <textarea id="sinopsis" name="sinopsis" required><?= htmlspecialchars($pelicula['sinopsis']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="duracion">Duracion (minutos):</label>
                <input type="text" id="duracion" name="duracion" value="<?= htmlspecialchars($pelicula['duracion']) ?>" required />
            </div>
            <div class="form-group">
                <label>Imagen Actual:</label>
                <?php if (!empty($pelicula['imagen'])): ?>
                    <img src="<?= htmlspecialchars($pelicula['imagen']) ?>" alt="<?= htmlspecialchars($pelicula['nombre']) ?>" height="100" style="margin-bottom: 10px; display: block; border-radius: 5px;">
                <?php else: ?>
                    <p style="color: #aaa;">Sin imagen</p>
                <?php endif; ?>
                <label for="imagen_nueva">Cambiar Imagen (opcional):</label>
                <input type="file" id="imagen_nueva" name="imagen" accept="image/*">
            </div>

            <div class="form-group">
                <label for="fecha_estreno">Fecha de estreno:</label>
                <input type="date" id="fecha_estreno" name="fecha_estreno" value="<?= htmlspecialchars($pelicula['fecha_estreno']) ?>" required />
            </div>
            <div class="form-group">
                <label for="restriccion">Restriccion:</label>
                <select id="restriccion" name="restriccion" required>
                    <option value="">Seleccione la restriccion</option>
                    <option value="APT" <?= $pelicula['restriccion'] == 'APT' ? 'selected' : '' ?>>APT</option>
                    <option value="+14" <?= $pelicula['restriccion'] == '+14' ? 'selected' : '' ?>>+14</option>
                    <option value="+18" <?= $pelicula['restriccion'] == '+18' ? 'selected' : '' ?>>+18</option>
                </select>
            </div>
            <div class="form-group">
                <label for="generos">Genero:</label>
                <select id="generos" name="generos[]" multiple required>
                    <option value="">Seleccione el genero</option>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?= $genero['id'] ?>"
                                <?php if (in_array($genero['id'], $pelicula['generos'])) echo 'selected'; ?>>
                            <?= htmlspecialchars($genero['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small> Mantén CTRL para seleccionar mas de un genero</small>
            </div>
            <div class="form-group">
                <label for="salas">Sala:</label>
                <select id="salas" name="salas[]" multiple required>
                    <option value="">Seleccione la sala</option>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id'] ?>"
                                <?php if (in_array($sala['id'], $pelicula['salas'])) echo 'selected'; ?>>
                            <?= htmlspecialchars($sala['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-action">Editar Pelicula</button>
            <?php if (!empty($error)): ?>
                <p style="color: red; margin-top: 10px"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>
</body>
</html>
