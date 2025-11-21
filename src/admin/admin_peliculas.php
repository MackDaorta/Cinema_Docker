<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<link rel="stylesheet" href="/css/admin_peliculas.css">
<style>
    .centro { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .form-container { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    .btn-action { background: #e50914; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    .pelicula-item { display: flex; justify-content: space-between; background: white; padding: 15px; border: 1px solid #eee; margin-bottom: 10px; }
</style>

<div class="centro">
  <h2 class="titulo text-2xl font-bold mb-4">Gestión de Películas</h2>
  <div class="form-container">
    <form id="productForm" enctype="multipart/form-data">
      <input type="hidden" id="peli_id" name="id">
      <input type="hidden" id="peli_imagen_actual" name="imagen_actual">

      <div class="form-group">
        <label>Nombre:</label>
        <input type="text" id="nombre" name="nombre" required />
      </div>
      <div class="form-group">
        <label>Sinopsis:</label>
        <textarea id="sinopsis" name="sinopsis" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label>Duración (min):</label>
        <input type="number" id="duracion" name="duracion_minutos" required />
      </div>
      <div class="form-group">
        <label>Imagen:</label>
        <input type="file" id="imagen" name="imagen" />
        <small id="preview-txt"></small>
      </div>
      <div class="form-group">
        <label>Estreno:</label>
        <input type="date" id="fecha_estreno" name="fecha_estreno" required />
      </div>
      <div class="form-group">
        <label>Restricción</label>
        <select id="restriccion" name="restriccion" required>
          <option value="APT">APT</option>
          <option value="+14">+14</option>
          <option value="+18">+18</option>
        </select>
      </div>
      <div class="form-group">
        <label>Géneros (Ctrl+Click):</label>
        <select id="generos" name="generos[]" multiple required class="h-32"></select>
      </div>
      <div class="form-group">
        <label>Salas (Ctrl+Click):</label>
        <select id="salas" name="salas[]" multiple required class="h-32"></select>
      </div>

      <button type="submit" class="btn-action" id="btn-guardar">Agregar Película</button>
      <button type="button" onclick="limpiarForm()" style="padding:10px;">Cancelar</button>
    </form>
  </div>

  <div class="peliculas-list">
    <h2 class="text-xl font-bold mb-4">Lista de Películas</h2>
    <div id="peliculasContainer"><p>Cargando...</p></div>
  </div>
</div>
<?php require_once '../public/footer.php'; ?>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminPeliculas);</script>
</body>
</html>