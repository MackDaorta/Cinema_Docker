<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

define('MODO_HTML', true);
require_once __DIR__ . '/../config/conexionDB.php';

$error = null;
$mensaje_exito = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['password2'] ?? ''; 

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Por favor complete todos los campos obligatorios.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        try {
            $stmtCheck = $pdo->prepare("SELECT id FROM Usuario WHERE nombre_usuario = ? OR email = ?");
            $stmtCheck->execute([$username, $email]);
            
            if ($stmtCheck->rowCount() > 0) {
                $error = "El nombre de usuario o email ya está registrado.";
            } else {
                // --- GUARDADO EN TEXTO PLANO ---
                // Guardamos la contraseña directamente, sin hashear.
                $passwordToSave = $password; 
                
                // Usamos UUID() de MySQL para generar el ID
                $sql = "INSERT INTO Usuario (id, nombre_usuario, email, contrasena, es_admin) VALUES (UUID(), ?, ?, ?, 0)";
                $stmtInsert = $pdo->prepare($sql);
                
                if ($stmtInsert->execute([$username, $email, $passwordToSave])) {
                    $mensaje_exito = "¡Cuenta creada con éxito! Ahora puedes iniciar sesión.";
                } else {
                    $error = "Hubo un error interno al registrar el usuario.";
                }
            }
        } catch (PDOException $e) {
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
  <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<div class="contenedor-login">
    <h2>Registro de Usuario</h2>
    
    <?php if ($mensaje_exito): ?>
        <div style="color: green; text-align: center; margin-bottom: 15px; background: #d4edda; padding: 10px; border-radius: 5px;">
            <?php echo $mensaje_exito; ?>
            <br><br>
            <a href="login.php" style="color: #155724; font-weight: bold; text-decoration: underline;">Ir a Iniciar Sesión</a>
        </div>
    <?php else: ?>
        <form method="POST" action="registro.php">
            
            <input type="text" id="username" name="username" placeholder="Nombre de Usuario" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
            <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <input type="password" id="password2" name="password2" placeholder="Confirmar Contraseña" required>

            <button type="submit" class="btn-action">Registrarse</button>
            
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