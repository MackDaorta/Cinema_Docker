<?php
require_once '../security/admin_check.php';
require_once '../public/header.php'; // Reutilizamos el header público
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-red-600">Gestión de Productos (Confitería)</h1>

    
    <div class="bg-gray-100 p-6 rounded shadow mb-8">
        <h2 class="text-xl font-bold mb-4" id="form-title">Agregar Nuevo Producto</h2>
        <form id="form-producto" enctype="multipart/form-data">
            <input type="hidden" name="id" id="prod_id">
            <input type="hidden" name="imagen_actual" id="prod_imagen_actual">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Nombre:</label>
                    <input type="text" name="nombre" id="prod_nombre" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Precio:</label>
                    <input type="number" step="0.01" name="precio" id="prod_precio" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Categoría:</label>
                    <select name="categoria" id="prod_categoria" class="w-full p-2 border rounded">
                        <option value="COMBO">Combo</option>
                        <option value="POPCORN">Popcorn</option>
                        <option value="BEBIDA">Bebida</option>
                        <option value="SNACK">Snack</option>
                        <option value="COLECCIONABLES">Coleccionables</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Imagen:</label>
                    <input type="file" name="imagen" id="prod_imagen" class="w-full p-2 border rounded">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-gray-700">Descripción:</label>
                <textarea name="descripcion" id="prod_descripcion" class="w-full p-2 border rounded"></textarea>
            </div>
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="disponible" id="prod_disponible" checked class="form-checkbox h-5 w-5 text-red-600">
                    <span class="ml-2 text-gray-700">Disponible para la venta</span>
                </label>
            </div>
            
            <div class="mt-6 flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar Producto</button>
                <button type="button" onclick="limpiarFormulario()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar / Limpiar</button>
            </div>
        </form>
    </div>

    <!-- Tabla de Productos -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4">Imagen</th>
                    <th class="py-2 px-4">Nombre</th>
                    <th class="py-2 px-4">Categoría</th>
                    <th class="py-2 px-4">Precio</th>
                    <th class="py-2 px-4">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-productos-body">
                
            </tbody>
        </table>
    </div>
</div>

<script src="/js/app.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        initAdminProductos(); 
    });
</script>
</body>
</html>