<?php
session_start();
$usuario = $_SESSION["user"] ?? null;
require_once __DIR__ . '/../config/conexionDB.php';

// Obtener salas desde la base de datos
$sql_salas = "SELECT nombre, descripcion, imagen FROM Sala ORDER BY nombre";
$stmt = $pdo->prepare($sql_salas);
$stmt->execute();
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener películas para la cartelera destacada
$sql_cartelera = "SELECT id, nombre FROM Pelicula ORDER BY fecha_estreno ASC LIMIT 4";
$stmt2 = $pdo->prepare($sql_cartelera);
$stmt2->execute();
$cartelera = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas - Cinemark</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/salas.css">
</head>
<body>
<?php require_once __DIR__ . '/public/header.php'; ?>
    <main>
        <h2 class="titulo">Nuestras Salas</h2>
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

        <h2 class="titulo">Cartelera Destacada</h2>
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
<?php require_once __DIR__ . '/public/footer.php'; ?>
</body>
</html>
