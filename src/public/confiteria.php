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
    <style>
        /* Estilos rápidos para la grilla de productos */
        .categoria-section { margin-bottom: 40px; }
        .categoria-title { font-size: 2rem; color: #d32f2f; margin-bottom: 20px; border-bottom: 2px solid #eee; }
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .producto-card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; text-align: center; padding-bottom: 15px; }
        .producto-card img { width: 100%; height: 200px; object-fit: contain; background: #f9f9f9; }
        .producto-card h3 { font-size: 1.2rem; margin: 10px 0; }
        .producto-card .precio { font-weight: bold; color: #28a745; font-size: 1.1rem; }
    </style>
</head>
<body id="pagina-confiteria"> <!-- ID para que el JS sepa dónde está -->

<?php require_once __DIR__ . '/header.php'; ?>

<main class="container mx-auto p-4">
    <h1 class="text-center text-4xl font-bold my-8">Nuestra Confitería</h1>
    
    <!-- Contenedor principal donde JS inyectará las categorías -->
    <div id="confiteria-contenido">
        <p class="text-center">Cargando deliciosos productos...</p>
    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
<script src="/js/app.js"></script>
</body>
</html>