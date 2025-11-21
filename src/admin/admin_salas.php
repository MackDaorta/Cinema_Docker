<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-purple-700">Gestión de Salas</h1>
    <div class="bg-gray-100 p-6 rounded shadow mb-8">
        <form id="form-sala" enctype="multipart/form-data">
            <input type="hidden" name="id" id="sala_id">
            <input type="hidden" name="imagen_actual" id="sala_imagen_actual">
            <div class="mb-4"><label>Nombre:</label><input type="text" name="nombre" id="sala_nombre" class="w-full p-2 border" required></div>
            <div class="mb-4"><label>Imagen:</label><input type="file" name="imagen" class="w-full p-2 border"></div>
            <div class="mb-4"><label>Descripción:</label><textarea name="descripcion" id="sala_descripcion" class="w-full p-2 border"></textarea></div>
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Guardar</button>
            <button type="button" onclick="limpiarFormSala()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
        </form>
    </div>
    <table class="min-w-full bg-white"><tbody id="tabla-salas-body"></tbody></table>
</div>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminSalas);</script>
</body>
</html>