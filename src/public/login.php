<?php
session_start();
<<<<<<< HEAD

// Si el usuario ya está logueado, redirigir al inicio
if (isset($_SESSION['user'])) {
    // Nota: Si index.php está en la raíz, la ruta es '/index.php' o '../index.php'
    header('Location: /index.php'); 
    exit;
}

// Definimos esta constante para que conexionDB.php NO envíe headers JSON
define('MODO_HTML', true);

// --- CORRECCIÓN DE RUTA ---
// Salimos de 'public' (..) y entramos a 'config'
require_once __DIR__ . '/../config/conexionDB.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Por favor ingrese usuario y contraseña.";
    } else {
        // Consulta segura a la BD
        $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE nombre_usuario = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verificar hash
        if ($user && password_verify($password, $user->contrasena)) {
            
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
=======
$error = $_SESSION['login_error'] ?? '';
>>>>>>> decf3173e5388c704f459c127c1c65537a0ca648
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
<<<<<<< HEAD
  <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<div class="contenedor-login">
    <!-- action="" envía al mismo archivo -->
    <form id="formulario-login" method="POST" action="">
        <h2>Iniciar Sesion</h2>
        
        <input type="text" id="usuario" name="username" placeholder="Usuario" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
        <input type="password" id="clave" name="password" placeholder="Contraseña" required>
        
        <button type="submit">Ingresar</button>
        
        <!-- Apuntamos a registro.php que también está en 'public' -->
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
=======
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
>>>>>>> decf3173e5388c704f459c127c1c65537a0ca648
