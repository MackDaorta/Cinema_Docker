<?php 
session_start();
$usuario = $_SESSION["user"] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confitería - Cinemark</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/confiteria.css">
</head>
<body id="pagina-confiteria"> <!-- ID para que el JS sepa dónde está -->

<?php
require_once __DIR__ . '/header.php'; 
?>
<main>
    <h1 class="titulo">Menú Confiteria:</h1>
    
    <!-- Contenedor principal donde JS inyectará las categorías -->
     
    <div id="confiteria-contenido">
        <p class="text-center">Cargando deliciosos productos...</p>
    </div>
    
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
<script src="/js/app.js"></script>
</body>
</html>