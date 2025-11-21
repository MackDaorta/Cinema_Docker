<?php 
session_start();
$usuario = $_SESSION["user"] ?? null;
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera - Cinemark</title>
    
    <!-- Estilos Globales -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Estilos Específicos de Cartelera (Asegúrate de copiar tu CSS aquí) -->
    <link rel="stylesheet" href="/css/cartelera.css">
</head>
<body id="pagina-cartelera"> <!-- ID vital para que JS sepa dónde está -->

<?php require_once __DIR__ . '/header.php'; ?>

    <main>
        <h2 class="titulo">Películas en Cartelera</h2>
        
        <!-- Contenedor VACÍO donde JS inyectará las tarjetas -->
        <!-- Mantiene la clase original 'cartelera-grid' para tus estilos -->
        <div id="peliculas-contenido" class="cartelera-grid">
            <p style="text-align: center; grid-column: 1/-1;">Cargando cartelera...</p>
        </div>
    </main>

<?php require_once __DIR__ . '/footer.php'; ?>

<!-- Script JS -->
<script src="/js/app.js"></script> 

</body>
</html>