<?php
session_start();
$usuario= $_SESSION["user"]?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinemark</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="/js/app.js"></script>
</head>
<body id="pagina-home">
<?php
    require_once __DIR__ . '/public/header.php';
?>

<div id="sliders-container" class="sliders">
    </div>

<br>
<br>

<div class = "promociones">
    <h2>Promociones</h2>
    <div id="promociones-fotos" class="fotos">
        </div>
</div>
<?php
    require_once __DIR__ . '/public/footer.php';
?>

</body>