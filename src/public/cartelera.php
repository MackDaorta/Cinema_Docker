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
    
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/cartelera.css">
</head>
<body id="pagina-cartelera"> 

<?php require_once __DIR__ . '/header.php'; ?>

    <main>
        <h2 class="titulo">Películas en Cartelera</h2>
        
       
        <div id="peliculas-contenido" class="cartelera-grid">
            <p style="text-align: center; grid-column: 1/-1;">Cargando cartelera...</p>
        </div>
    </main>

<?php require_once __DIR__ . '/footer.php'; ?>

<!-- Script JS -->
<script src="/js/app.js"></script> 

</body>
</html>