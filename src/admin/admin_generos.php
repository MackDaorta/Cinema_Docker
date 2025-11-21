<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-green-600">Gestión de Géneros</h1>
    <div class="bg-gray-100 p-6 rounded shadow mb-8">
        <form id="form-genero">
            <input type="hidden" name="id" id="genero_id">
            <div class="mb-4"><label>Nombre:</label><input type="text" name="nombre" id="genero_nombre" class="w-full p-2 border" required></div>
            <div class="mb-4"><label>Descripción:</label><textarea name="descripcion" id="genero_descripcion" class="w-full p-2 border"></textarea></div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
            <button type="button" onclick="limpiarFormGenero()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
        </form>
    </div>
    <table class="min-w-full bg-white"><tbody id="tabla-generos-body"></tbody></table>
</div>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminGeneros);</script>
</body>
</html>