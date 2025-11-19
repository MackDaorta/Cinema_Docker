
<header>
    <img src='uploads/header/Cinemark-Logo.png' alt="Logo de Cinemark" width="200px" height="100px">
    <nav>
        <a href="index.php" class="activo">Inicio</a>
        <a href="cartelera.php">Cartelera</a>
        <a href="salas.php">Salas</a>
        <a href="confiteria.php">Confiteria</a>
        <a href="conocenos.php">Conocenos</a>
        <a href="ubicanos.php" >Ubicanos</a>
        <?php 
        //Verifica si el usuario ha iniciado sesión
        if (isset($usuario)){
            if (isset($usuario['es_admin']) && $usuario['es_admin']){
                echo '<a href="admin/dashboard.php">Panel Admin</a>';
            }
            echo '<a href="logout.php">Cerrar Sesión</a>';
        } else {
            echo '<a href="login.php">Iniciar Sesión</a>';
        }
        ?>
    </nav>
</header>