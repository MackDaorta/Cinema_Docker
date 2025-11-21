<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<link rel="stylesheet" href="css/admin_generos.css">

<div class="container">
    <h1>Gestión de Géneros</h1>
    <div class="form-container">
        <form id="form-genero">
            <input type="hidden" name="id" id="genero_id">
            <div class="form-group">
                <label for="genero_nombre">Nombre:</label>
                <input type="text" name="nombre" id="genero_nombre" required>
            </div>
            <div class="form-group">
                <label for="genero_descripcion">Descripción:</label>
                <textarea name="descripcion" id="genero_descripcion"></textarea>
            </div>
            <div class="form-btns">
                <button type="submit" class="btn-principal">Guardar</button>
                <button type="button" onclick="limpiarFormGenero()" class="btn-secondary">Cancelar</button>
            </div>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-generos-body"></tbody>
    </table>
</div>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminGeneros);</script>
<?php require_once '../public/footer.php'; ?>