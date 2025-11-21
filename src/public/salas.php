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
    <link rel="stylesheet" href="/css/salas.css">
</head>
<body id="pagina-salas"> <!-- ID clave para el JS -->

<?php require_once __DIR__ . '/header.php'; ?>

<div class="main-content">
    <h2 class="section-title">Nuestras Salas</h2>
    <div id="salas-contenido" class="salas-grid">
        <p>Cargando información de las salas...</p>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

<script src="/js/app.js"></script>
</body>
</html>