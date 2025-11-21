<?php 
session_start();
$usuario = $_SESSION["user"] ?? null;
require_once __DIR__ . '/../config/conexionDB.php';

$sql = "SELECT nombre, descripcion, precio, imagen, categoria
        FROM Producto
        WHERE disponible = TRUE
        ORDER BY categoria, nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar productos por categoría
$productos_por_categoria = [];
foreach ($productos as $producto) {
    $cat = $producto["categoria"];
    if (!isset($productos_por_categoria[$cat])) {
        $productos_por_categoria[$cat] = [];
    }
    $productos_por_categoria[$cat][] = $producto;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confitería - Cinemark</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/confiteria.css">
</head>
<body>
<?php require_once __DIR__ . '/public/header.php'; ?>
<main>
    <h2 class="titulo">Menú de Confitería</h2>
    <div class="confiteria-grid">
        <?php foreach ($productos_por_categoria as $categoria => $productos): ?>
            <section class="categoria">
                <h3><?= htmlspecialchars($categoria) ?></h3>
                <div class="productos">
                    <?php foreach ($productos as $producto): ?>
                        <div class="item">
                            <div class="imagen-placeholder">
                                <img 
                                    src="img/confiteria/<?= htmlspecialchars($producto['imagen']) ?>" 
                                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                >
                            </div>
                            <p class="nombre-producto"><?= htmlspecialchars($producto['nombre']) ?></p>
                            <span class="precio">Precio: S/ <?= htmlspecialchars($producto['precio']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</main>
<?php require_once __DIR__ . '/public/footer.php'; ?>
</body>
</html>
