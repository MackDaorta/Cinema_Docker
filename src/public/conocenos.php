<?php
session_start();
$usuario = $_SESSION["user"] ?? null;
$historia_img = 'img/conocenos/sala_roja.jpg';
$historia_titulo = 'Nuestra Historia';
$historia_texto = 'Desde nuestros inicios hemos buscado ofrecer experiencias inolvidables en cada función. Inspirados en la pasión por el séptimo arte, hemos crecido como un espacio donde el entretenimiento y la comodidad se unen para crear recuerdos únicos con cada película proyectada.';

$trabajo_img = 'img/conocenos/equipo.jpg';
$trabajo_titulo = 'Trabaja con Nosotros';
$trabajo_texto = '¿Te apasiona el cine y el trabajo en equipo? Únete a nuestra familia y sé parte de una experiencia que transforma cada función en un momento especial para miles de personas.';
$trabajo_boton_texto = 'Enviar Solicitud';
$trabajo_boton_link = '#';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conócenos - CINEMARK</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/conocenos.css">
</head>
<body>
<?php require_once __DIR__ . '/header.php'; ?>
    <main class="contentConocenos">
        <div class="container">
            <section class="section">
                <div class="section-image">
                    <img src="<?php echo htmlspecialchars($historia_img); ?>" alt="Butacas de Cine" style="width: 100%; height: auto; max-width: 500px;">
                </div>
                <div class="section-content">
                    <h2><?php echo htmlspecialchars($historia_titulo); ?></h2>
                    <p><?php echo htmlspecialchars($historia_texto); ?></p>
                </div>
            </section>
            <section class="section">
                <div class="section-image">
                    <img src="<?php echo htmlspecialchars($trabajo_img); ?>" alt="Equipo de Cinemark" style="width: 100%; height: auto; max-width: 500px;">
                </div>
                <div class="section-content">
                    <h2><?php echo htmlspecialchars($trabajo_titulo); ?></h2>
                    <p><?php echo htmlspecialchars($trabajo_texto); ?></p>
                    <a href="<?php echo htmlspecialchars($trabajo_boton_link); ?>" class="btn"><?php echo htmlspecialchars($trabajo_boton_texto); ?></a>
                </div>
            </section>
        </div>
    </main>
<?php require_once __DIR__ . '/footer.php'; ?>
</body>
</html>
