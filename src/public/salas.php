<?php
// Asumimos que la conexión a la base de datos está disponible aquí:
// require_once __DIR__ . '/../config/conexionDB.php'; 

// ----------------------------------------------------------------------
// SIMULACIÓN DE DATOS OBTENIDOS DE LA BASE DE DATOS
// ----------------------------------------------------------------------

// Datos de las Salas de Cine
$salas = [
    [
        'nombre' => 'Sala XD (Extreme Digital)',
        'titulo_corto' => 'XD',
        'descripcion' => 'Pantalla gigante, sonido inmersivo y potente. La experiencia más envolvente.',
        'ideal_para' => 'Películas de acción y blockbusters.',
        'imagen_url' => 'sala_xd.jpg' 
    ],
    [
        'nombre' => 'Sala DBOX',
        'titulo_corto' => 'DBOX',
        'descripcion' => 'Asientos de movimiento que se sincronizan con la acción de la película.',
        'ideal_para' => 'Sentir la película, desde persecuciones hasta explosiones.',
        'imagen_url' => 'sala_dbox.jpg'
    ],
    [
        'nombre' => 'Sala 3D',
        'titulo_corto' => '3D',
        'descripcion' => 'Proyección estereoscópica para una profundidad de imagen realista.',
        'ideal_para' => 'Experiencias Visuales que saltan de la pantalla.',
        'imagen_url' => 'sala_3d.jpg'
    ],
    [
        'nombre' => 'Sala 2D',
        'titulo_corto' => '2D',
        'descripcion' => 'Proyección digital de alta calidad con el audio y comodidad clásica.',
        'ideal_para' => 'Todos los estrenos y disfrutar del cine tradicional.',
        'imagen_url' => 'sala_2d.jpg'
    ],
];

// Datos de la Cartelera Destacada
$cartelera = [
    [
        'titulo' => '¿Donde están las rubias?', // Ejemplo de película
        'link_horarios' => '#', // Enlace a la página de horarios
    ],
    // Aquí irían más películas destacadas de la DB
];

// ----------------------------------------------------------------------
// RENDERIZADO HTML
// ----------------------------------------------------------------------
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

    <header>
        <div class="logo-cinemark">C</div>
        <nav>
            <a href="#">Inicio</a>
            <a href="#">Salas</a>
            <a href="#">Confitería</a>
            <a href="#">Conócenos</a>
            <a href="#" class="principal">Ubícanos</a>
        </nav>
    </header>

    <main class="main-content">

        <h2 class="section-title">Nuestras Salas</h2>
        <div class="salas-grid">
            
            <?php foreach ($salas as $sala): ?>
                <div class="sala-card">
                    <div class="sala-header">
                        <h3><?php echo htmlspecialchars($sala['nombre']); ?></h3>
                    </div>
                    
                    <div class="sala-body">
                        <div class="sala-image-container">
                            <img 
                                src="<?php echo 'img/salas/' . htmlspecialchars($sala['imagen_url']); ?>" 
                                alt="Imagen de <?php echo htmlspecialchars($sala['nombre']); ?>"
                                class="sala-image-placeholder"
                            >
                        </div>
                        
                        <div class="sala-info">
                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($sala['descripcion']); ?></p>
                            <p><strong>Ideal para:</strong> <?php echo htmlspecialchars($sala['ideal_para']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        
        <h2 class="section-title">Cartelera Destacada</h2>
        <div class="cartelera-list">
            
            <?php foreach ($cartelera as $pelicula): ?>
                <div class="movie-item">
                    <h4><?php echo htmlspecialchars($pelicula['titulo']); ?></h4>
                    <a href="<?php echo htmlspecialchars($pelicula['link_horarios']); ?>" class="movie-button">Ver Horarios</a>
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
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> CINEMARK. Todos los derechos reservados.</p>
        <h3>Contacto | Trabaja con Nosotros</h3>
    </footer>
    
</body>
</html>