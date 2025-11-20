<?php
// Asegura que la fuente de la fuente 'Work Sans' esté disponible si se usa en el CSS.
// Si no está ya enlazada globalmente en tu <head>, deberías incluirla aquí.

// Incluir el archivo de conexión a la base de datos
// ATENCIÓN: Esta línea se asume que funciona y proporciona la variable $db_connection o similar.
require_once __DIR__ . '/../config/conexionDB.php';

// ----------------------------------------------------------------------
// SIMULACIÓN DE DATOS OBTENIDOS DE LA BASE DE DATOS
// ----------------------------------------------------------------------

// En un escenario real, harías una consulta SQL:
// $sql = "SELECT nombre, descripcion, precio, imagen_url, categoria FROM productos_confiteria ORDER BY categoria, orden";
// $resultado = $db_connection->query($sql);
// $productos_db = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Por ahora, usamos datos simulados para mostrar la estructura HTML/PHP:
$productos_db = [
    [
        'nombre' => 'Canchita mediana',
        'precio' => '17.40',
        'imagen_url' => 'palomitas_medianas.jpg', // Este nombre se usaría para buscar la imagen
        'categoria' => 'Canchita'
    ],
    [
        'nombre' => 'Canchita grande',
        'precio' => '19.90',
        'imagen_url' => 'palomitas_grandes.jpg',
        'categoria' => 'Canchita'
    ],
    [
        'nombre' => 'Canchita gigante',
        'precio' => '24.90',
        'imagen_url' => 'palomitas_gigantes.jpg',
        'categoria' => 'Canchita'
    ],
    [
        'nombre' => 'Gaseosa pequeña',
        'precio' => '8.00',
        'imagen_url' => 'gaseosa_pequena.jpg',
        'categoria' => 'Bebidas'
    ],
    [
        'nombre' => 'Gaseosa mediana',
        'precio' => '11.40',
        'imagen_url' => 'gaseosa_mediana.jpg',
        'categoria' => 'Bebidas'
    ],
    [
        'nombre' => 'Gaseosa grande',
        'precio' => '13.10',
        'imagen_url' => 'gaseosa_grande.jpg',
        'categoria' => 'Bebidas'
    ],
    // Puedes añadir más categorías y productos aquí
    [
        'nombre' => 'Combo Popcorn + Gaseosa',
        'precio' => '25.00',
        'imagen_url' => 'combo_clasico.jpg',
        'categoria' => 'Combos'
    ],
];

// Agrupar los productos por categoría
$productos_por_categoria = [];
foreach ($productos_db as $producto) {
    $categoria = $producto['categoria'];
    if (!isset($productos_por_categoria[$categoria])) {
        $productos_por_categoria[$categoria] = [];
    }
    $productos_por_categoria[$categoria][] = $producto;
}

// ----------------------------------------------------------------------
// RENDERIZADO HTML
// ----------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Confitería - CINEMARK</title>
    <link rel="stylesheet" href="styles.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <header>
        <div class="logo">
            <img src="C_logo.png" alt="CINEMARK Logo" style="height: 30px;">
        </div>
        <nav>
            <a href="#">Inicio</a>
            <a href="#">Cartelera</a>
            <a href="#">Salas</a>
            <a href="#" class="activo">Confitería</a>
            <a href="#">Conócenos</a>
            <a href="#" class="principal">Ubícanos</a>
        </nav>
    </header>

    <main class="contenido-confiteria">
        <h1 class="titulo">Menú de Confitería</h1>

        <?php 
        // Iterar sobre cada categoría
        foreach ($productos_por_categoria as $categoria => $productos): 
        ?>
            <section class="categoria">
                <h3><?php echo htmlspecialchars($categoria); ?></h3>
                
                <div class="productos">
                    <?php 
                    // Iterar sobre cada producto dentro de la categoría
                    foreach ($productos as $producto): 
                    ?>
                        <div class="item">
                            <div class="imagen-placeholder">
                                <img 
                                    src="<?php echo 'img/confiteria/' . htmlspecialchars($producto['imagen_url']); ?>" 
                                    alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                >
                            </div>

                            <p class="nombre-producto"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                            <span class="precio">Precio: S/ <?php echo htmlspecialchars($producto['precio']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> CINEMARK. Todos los derechos reservados.</p>
        <h3>Síguenos en redes</h3>
    </footer>

</body>
</html>