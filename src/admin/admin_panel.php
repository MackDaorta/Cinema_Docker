<?php

require_once '../security/admin_check.php';

require_once '../public/header.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Cinemark</title>
    
    
    <link rel="stylesheet" href="/admin/css/admin.css">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

<main>
    <section class="acciones">
        <h2>Gestión de Contenido</h2>
        <div class="botones">
            <a href="/admin/admin_productos.php">Administrar Productos</a>
            <a href="/admin/admin_peliculas.php">Administrar Peliculas</a>
            <a href="/admin/admin_anuncios.php">Administrar Anuncios</a>
            <a href="/admin/admin_salas.php">Gestionar Salas</a>
            <a href="/admin/admin_generos.php">Gestionar Generos</a>
        </div>
    </section>
</main>

<?php 
// 3. FOOTER: Incluimos el pie de página común
require_once '../public/footer.php'; 
?>

</body>
</html>