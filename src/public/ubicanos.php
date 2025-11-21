<?php 
session_start();
$usuario = $_SESSION["user"] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubícanos - Cinemark</title>
    
    <!-- Estilos Globales -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Estilos Específicos de Ubicanos -->
    <link rel="stylesheet" href="/css/ubicanos.css">
    
    <!-- Estilos inline de respaldo para el mapa por si falta el CSS -->
    <style>
        .map-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .map-container iframe {
            width: 100%;
            max-width: 1000px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .address-info {
            text-align: center;
            background-color: #f4f4f4;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto 40px auto;
            color: #333;
        }
        .address-info h2 { color: #d32f2f; margin-bottom: 15px; }
        .titulo-principal { text-align: center; color: white; margin-top: 30px; }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/header.php'; ?>

    <main class="container mx-auto p-4">
      <h1 class="titulo-principal text-3xl font-bold">Encuéntranos</h1>
      
      <div class="map-container">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3902.7244458929695!2d-77.06503392493971!3d-11.993558988239164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105ce55cae81b99%3A0xaa735d80156e0881!2sCinemark!5e0!3m2!1ses-419!2spe!4v1760682510006!5m2!1ses-419!2spe"
          width="800"
          height="450"
          style="border: 0"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
      </div>

    </main>

<?php require_once __DIR__ . '/footer.php'; ?>

<!-- Script JS -->
<script src="/js/app.js"></script> 

</body>
</html>