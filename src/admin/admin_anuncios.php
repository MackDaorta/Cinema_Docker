<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<style>
    .main-content { max-width: 1000px; margin: 20px auto; padding: 20px; }
    .form-container { background: #f8f9fa; padding: 25px; border-radius: 8px; margin-bottom: 30px; }
    .form-group { margin-bottom: 15px; }
    .form-group input, .form-group select { width: 100%; padding: 10px; }
    .promotion-item { display: flex; justify-content: space-between; padding: 15px; border-bottom: 1px solid #eee; background: white; }
</style>
<main class="main-content">
    <div class="form-container">
        <h2 class="text-2xl font-bold mb-4" id="form-title">Gestión de Anuncios</h2>
        <form id="form-promocion" enctype="multipart/form-data">
            <input type="hidden" name="id" id="anuncio_id">
            <input type="hidden" name="imagen_actual" id="anuncio_imagen_actual">
            <div class="form-group"><label>Título:</label><input type="text" id="titulo" name="nombre" required></div>
            <div class="form-group"><label>Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="SLIDER">Slider</option>
                    <option value="PROMOCION">Promoción</option>
                </select>
            </div>
            <div class="form-group"><label>Imagen:</label><input type="file" name="imagen" accept="image/*"></div>
            <div class="form-group"><label>Link:</label><input type="url" id="link" name="link"></div>
            <div class="form-group"><label>Vigencia:</label><input type="date" id="vigencia" name="vigencia"></div>
            <button type="submit" style="background:#007bff; color:white; padding:10px;">Guardar</button>
            <button type="button" onclick="limpiarFormAnuncio()" style="padding:10px;">Cancelar</button>
        </form>
    </div>
    <div id="lista-anuncios-container"></div>
</main>
<?php require_once '../public/footer.php'; ?>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminAnuncios);</script>
</body>
</html>