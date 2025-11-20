<?php
require_once '../security/admin_check.php';
session_start();

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Administración - Cinemark</title>
  <link rel="stylesheet" href="/static/administrator/admin.css">
</head>
<body>
  <main>
    <section class="acciones">
      <h2>Gestión de Contenido</h2>
      <div class="botones">
        <a href="admin_peliculas.php" class="btn-admin">Administrar Películas</a>
        <a href="admin_generos.php" class="btn-admin">Gestionar Géneros</a>
        <a href="admin_salas.php" class="btn-admin">Gestionar Salas</a>
        <a href="admin_anuncios.php" class="btn-admin">Administrar Anuncios</a>
      </div>
    </section>
  </main>
</body>
</html>