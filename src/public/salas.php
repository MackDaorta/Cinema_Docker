<?php 
session_start();
$usuario = $_SESSION["user"] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas - Cinemark</title>
    
    <!-- Estilos Globales -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Estilos Específicos de Sala (Asegúrate de tener este archivo) -->
    <link rel="stylesheet" href="/css/Sala.css">
</head>
<body id="pagina-salas"> <!-- ID clave para el JS -->

<?php require_once __DIR__ . '/header.php'; ?>

<div class="main-content container mx-auto p-4 min-h-screen">
    <h2 class="section-title text-3xl font-bold text-center my-8 text-blue-800 border-b pb-4">Nuestras Salas</h2>
    
    <!-- Contenedor donde JS inyectará las tarjetas -->
    <div id="salas-contenido" class="salas-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <p class="text-center col-span-full text-gray-500 text-xl">Cargando información de las salas...</p>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

<script src="/js/app.js"></script>
</body>
</html>