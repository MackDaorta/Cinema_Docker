<?php
require_once '../security/admin_check.php';
require_once '../public/header.php';
?>
<link rel="stylesheet" href="css/admin_productos.css">

<div class="container mx-auto p-4" style="max-width: 1000px;">
    <h1 class="text-3xl font-bold mb-6" style="color: #dc2626;">Gestión de Productos</h1>
    <div style="background:#fef2f2; padding:20px; border-radius:8px; margin-bottom:20px;">
        <form id="form-producto" enctype="multipart/form-data">
            <input type="hidden" name="id" id="prod_id">
            <input type="hidden" name="imagen_actual" id="prod_imagen_actual">
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre" id="prod_nombre" style="width:100%; padding:8px;" required>
                </div>
                <div>
                    <label>Precio:</label>
                    <input type="number" step="0.01" name="precio" id="prod_precio" style="width:100%; padding:8px;" required>
                </div>
                <div>
                    <label>Categoría:</label>
                    <select name="categoria" id="prod_categoria" style="width:100%; padding:8px;">
                        <option value="COMBO">Combo</option>
                        <option value="POPCORN">Popcorn</option>
                        <option value="BEBIDA">Bebida</option>
                        <option value="SNACK">Snack</option>
                        <option value="COLECCIONABLES">Coleccionables</option>
                    </select>
                </div>
                <div>
                    <label>Imagen:</label>
                    <input type="file" name="imagen" style="width:100%; padding:8px;">
                </div>
            </div>
            
            <div style="margin-top:15px;">
                <label>Descripción:</label>
                <textarea name="descripcion" id="prod_descripcion" style="width:100%; padding:8px;"></textarea>
            </div>

            <!-- CAMBIO: Checkbox para la columna 'disponible' -->
            <div style="margin-top:15px;">
                <label style="display:inline-flex; align-items:center;">
                    <input type="checkbox" name="disponible" id="prod_disponible" style="width:20px; height:20px; margin-right:10px;" checked>
                    Disponible para la venta
                </label>
            </div>

            <div style="margin-top:15px;">
                <button type="submit" style="background:#dc2626; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer;">Guardar</button>
                <button type="button" onclick="limpiarFormProducto()" style="background:#666; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer;">Cancelar</button>
            </div>
        </form>
    </div>
    <table style="width:100%; border-collapse:collapse;">
        <thead style="background:#333; color:white;">
            <tr>
                <th style="padding:10px;">Nombre</th>
                <th style="padding:10px;">Precio</th>
                <th style="padding:10px;">Estado</th>
                <th style="padding:10px;">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-productos-body"></tbody>
    </table>
</div>
<script src="/js/app.js"></script>
<script>document.addEventListener('DOMContentLoaded', initAdminProductos);</script>
</body>
</html>