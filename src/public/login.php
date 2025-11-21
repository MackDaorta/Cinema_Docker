<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
<div class="contenedor-login">
    <form id="formulario-login" method="POST" action="login.php">
      <h2>Iniciar Sesión</h2>
      <input type="text" id="usuario" name="username" placeholder="Usuario" required>
      <input type="password" id="clave" name="password" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
      <a class="btn-action" href="registro.php">Registrarse</a>
    </form>
    <?php if (!empty($error)): ?>
    <p style="color: red; text-align: center; margin-top: 15px;">
        <?= htmlspecialchars($error) ?>
    </p>
    <?php endif; ?>
</div>
</body>
</html>
