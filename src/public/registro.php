<?php
session_start();

// Si el usuario ya está logueado, redirigir al inicio
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Definimos esta constante para que conexionDB.php NO envíe headers JSON
define('MODO_HTML', true);
require_once __DIR__ . '/../config/conexionDB.php';

$error = null;
$mensaje_exito = null;

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['password2'] ?? ''; // Note el name="password2" en tu HTML

    // 1. VALIDACIONES BÁSICAS
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Por favor complete todos los campos obligatorios.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // 2. LÓGICA DE REGISTRO EN BASE DE DATOS
        try {
            // A) Verificar si el usuario o email ya existen
            // NOTA: Usamos 'nombre_usuario' según tu nueva estructura BD
            $stmtCheck = $pdo->prepare("SELECT id FROM Usuario WHERE nombre_usuario = ? OR email = ?");
            $stmtCheck->execute([$username, $email]);
            
            if ($stmtCheck->rowCount() > 0) {
                $error = "El nombre de usuario o email ya está registrado.";
            } else {
                // B) Insertar Nuevo Usuario
                // Encriptamos la contraseña
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertamos con es_admin = 0 (FALSE) por defecto y usamos UUID() para el ID
                $sql = "INSERT INTO Usuario (id, nombre_usuario, email, contrasena, es_admin) VALUES (UUID(), ?, ?, ?, 0)";
                $stmtInsert = $pdo->prepare($sql);
                
                if ($stmtInsert->execute([$username, $email, $passwordHash])) {
                    $mensaje_exito = "¡Cuenta creada con éxito! Ahora puedes iniciar sesión.";
                } else {
                    $error = "Hubo un error interno al registrar el usuario.";
                }
            }
        } catch (PDOException $e) {
            // En producción no deberías mostrar el mensaje técnico exacto
            $error = "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
  <!-- Ruta absoluta al CSS -->
  <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<div class="contenedor-login">
    <h2>Registro de Usuario</h2>
    
    <!-- Mensaje de éxito (si se registró correctamente) -->
    <?php if ($mensaje_exito): ?>
        <div style="color: green; text-align: center; margin-bottom: 15px; background: #d4edda; padding: 10px; border-radius: 5px;">
            <?php echo $mensaje_exito; ?>
            <br><br>
            <a href="login.php" style="color: #155724; font-weight: bold; text-decoration: underline;">Ir a Iniciar Sesión</a>
        </div>
    
    <!-- Si no hay éxito, mostramos el formulario -->
    <?php else: ?>
        <form method="POST" action="registro.php">
            
            <input type="text" id="username" name="username" placeholder="Nombre de Usuario" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
 
            <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            
            <input type="password" id="password2" name="password2" placeholder="Confirmar Contraseña" required>

            <button type="submit" class="btn-action">Registrarse</button>
            
            <!-- Mostrar errores de validación -->
            <?php if ($error): ?>
                <p style="color: red; margin-top: 15px; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            
            <p style="text-align: center; margin-top: 10px;">
                <a href="login.php" style="text-decoration: none; color: #666;">¿Ya tienes cuenta? Inicia sesión</a>
            </p>
        </form>
    <?php endif; ?>
</div>

</body>
</html>