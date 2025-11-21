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
<div class="address-info">
        <h2>Nuestra Ubicación</h2>
        <p>Cinemark Megaplaza</p>
        <p>Av. Alfredo Mendiola 3698, Independencia 15311</p>
        <p>Teléfono: 992 919 132</p>
        <p>Horario: Lunes a Domingo, 10:00 AM - 11:00 PM</p>
      </div>
    </main>

<?php require_once __DIR__ . '/footer.php'; ?>

<!-- Script JS -->
<script src="/js/app.js"></script> 

</body>
</html>