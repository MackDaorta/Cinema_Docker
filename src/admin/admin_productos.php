<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-red-600">Gestión de Productos</h1>
    <div class="bg-gray-100 p-6 rounded shadow mb-8">
        <form id="form-producto" enctype="multipart/form-data">
            <input type="hidden" name="id" id="prod_id">
            <input type="hidden" name="imagen_actual" id="prod_imagen_actual">
            <div class="grid grid-cols-2 gap-4">
                <div><label>Nombre:</label><input type="text" name="nombre" id="prod_nombre" class="w-full p-2 border" required></div>
                <div><label>Precio:</label><input type="number" step="0.01" name="precio" id="prod_precio" class="w-full p-2 border" required></div>
                <div><label>Categoría:</label><select name="categoria" id="prod_categoria" class="w-full p-2 border"><option value="COMBO">Combo</option><option value="POPCORN">Popcorn</option><option value="BEBIDA">Bebida</option></select></div>
                <div><label>Imagen:</label><input type="file" name="imagen" class="w-full p-2 border"></div>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Guardar</button>
            <button type="button" onclick="limpiarFormProducto()" class="bg-gray-500 text-white px-4 py-2 rounded mt-4">Cancelar</button>
        </form>
    </div>
    <table class="min-w-full bg-white"><tbody id="tabla-productos-body"></tbody></table>
</div>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminProductos);</script>
</body>
</html>