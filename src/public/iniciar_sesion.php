<?php
// Usar el archivo de conexión (asumiendo que está en 'config/conexiondb.php' basado en la imagen anterior)
require_once __DIR__ . '/../config/conexionDB.php';

// Iniciar sesión
session_start();

// Si el usuario ya está logeado, redirigir al inicio para evitar que entre de nuevo
if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Obtener y sanitizar los datos del formulario
    $identifier = $_POST['identifier'] ?? ''; // Puede ser nombre de usuario o email
    $password = $_POST['contrasena'] ?? '';
    
    // 2. Validar que los campos no estén vacíos
    if (empty($identifier) || empty($password)) {
        $error = 'Por favor, introduce tu nombre de usuario/email y contraseña.';
    } else {
        // 3. Buscar el usuario por nombre de usuario O email
        $stmt = $pdo->prepare("SELECT id, nombre_usuario, email, contrasena, es_admin FROM Usuario WHERE nombre_usuario = :identifier OR email = :identifier");
        $stmt->execute(['identifier' => $identifier]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Verificar el usuario y la contraseña
        if ($usuario) {
            // NOTA: En un entorno de producción, DEBES USAR password_verify() y contraseñas hasheadas.
            // Aquí, por simplicidad, se usa una comparación directa con la contraseña '123' del admin.
            // Para la contraseña del administrador '123' y los demás usuarios:
            if ($password === $usuario['contrasena']) { // Aquí DEBERÍA usarse password_verify($password, $usuario['contrasena'])
                // 5. Autenticación exitosa
                $_SESSION['user'] = [
                    'id' => $usuario['id'],
                    'nombre_usuario' => $usuario['nombre_usuario'],
                    'email' => $usuario['email'],
                    'es_admin' => (bool)$usuario['es_admin'] // Asegurar que es booleano
                ];

                $success = '¡Inicio de sesión exitoso!';
                
                // 6. Redirigir al usuario
                if ($_SESSION['user']['es_admin']) {
                    // Redirigir a la página de administrador si es admin
                    header('Location: ../admin/admin_panel.php');
                } else {
                    // Redirigir al inicio para un usuario normal
                    header('Location: ../index.php');
                }
                exit;

            } else {
                $error = 'Contraseña incorrecta.';
            }
        } else {
            $error = 'Usuario o Email no encontrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Cinemark</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/iniciar_sesion.css">
</head>
<body>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        
        <?php if ($error): ?>
            <p class="message error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form action="iniciar_sesion.php" method="POST">
            <div class="form-group">
                <label for="identifier">Usuario o Correo:</label>
                <input type="text" id="identifier" name="identifier" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
            <p class="register-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </form>
    </div>

</body>
</html>