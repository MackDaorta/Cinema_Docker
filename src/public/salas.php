<?php
require_once __DIR__ . '/../config/conexionDB.php';

// ----------------------------------------------------------------------
// OBTENER SALAS DESDE LA BASE DE DATOS
// ----------------------------------------------------------------------

$sql_salas = "SELECT nombre, descripcion, imagen FROM Sala ORDER BY nombre";
$stmt = $db_connection->prepare($sql_salas);
$stmt->execute();
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------------------------------------------------------------------
// OBTENER PELÍCULAS PARA LA CARTELERA DESTACADA
// (Las 4 películas más próximas a estrenarse)
// ----------------------------------------------------------------------

$sql_cartelera = "SELECT id, nombre FROM Pelicula ORDER BY fecha_estreno ASC LIMIT 4";
$stmt2 = $db_connection->prepare($sql_cartelera);
$stmt2->execute();
$cartelera = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestras Salas - CINEMARK</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

    <main class="main-content">

        <h2 class="section-title">Nuestras Salas</h2>

        <div class="salas-grid">

            <?php foreach ($salas as $sala): ?>
                <div class="sala-card">
                    <div class="sala-header">
                        <h3><?= htmlspecialchars($sala['nombre']) ?></h3>
                    </div>

                    <div class="sala-body">
                        <div class="sala-image-container">
                            <img 
                                src="img/salas/<?= htmlspecialchars($sala['imagen']) ?>" 
                                alt="Imagen de <?= htmlspecialchars($sala['nombre']) ?>"
                                class="sala-image-placeholder"
                            >
                        </div>

                        <div class="sala-info">
                            <p><strong>Descripción:</strong> <?= htmlspecialchars($sala['descripcion']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <h2 class="section-title">Cartelera Destacada</h2>

        <div class="cartelera-list">
            <?php foreach ($cartelera as $pelicula): ?>
                <div class="movie-item">
                    <h4><?= htmlspecialchars($pelicula['nombre']) ?></h4>
                    <a href="pelicula.php?id=<?= htmlspecialchars($pelicula['id']) ?>" class="movie-button">
                        Ver Horarios
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </main>

    <div class="siguenos">
        <h5>Síguenos en:</h5>
        <div class="iconos">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>

</body>
</html>
