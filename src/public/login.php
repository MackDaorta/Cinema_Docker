<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: /index.php'); 
    exit;
}

define('MODO_HTML', true);

require_once __DIR__ . '/../config/conexionDB.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Por favor ingrese usuario y contraseña.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE nombre_usuario = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $password === $user->contrasena) {
            
            $_SESSION['user'] = [
                'id' => $user->id,
                'username' => $user->nombre_usuario,
                'email' => $user->email,
                'es_admin' => (bool)$user->es_admin
            ];
            
            header('Location: /index.php');
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<div class="contenedor-login">
    <form id="formulario-login" method="POST" action="">
        <h2>Iniciar Sesion</h2>
        
        <input type="text" id="usuario" name="username" placeholder="Usuario" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
        <input type="password" id="clave" name="password" placeholder="Contraseña" required>
        
        <button type="submit">Ingresar</button>
        
        <a class="btn-action" href="registro.php">Registrarse</a>
    </form>

    <?php if ($error): ?>
        <p style="color: red;text-align: center;margin-top: 15px;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>
</div>

</body>
</html>