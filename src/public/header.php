
<header>
    <img src='/uploads/header/Cinemark-Logo.png' alt="Logo de Cinemark" width="200px" height="100px">
    <nav>
        <a href="/index.php" class="activo">Inicio</a>
        <a href="/public/cartelera.php">Cartelera</a>
        <a href="/public/salas.php">Salas</a>
        <a href="/public/confiteria.php">Confiteria</a>
        <a href="/public/conocenos.php">Conocenos</a>
        <a href="/public/ubicanos.php" >Ubicanos</a>
        <?php 
        //Verifica si el usuario ha iniciado sesión
        if (isset($usuario)){
            if (isset($usuario['es_admin']) && $usuario['es_admin']){
                echo '<a href="/admin/admin_panel.php">Panel Admin</a>';
            }
            echo '<a href="/logout.php">Cerrar Sesión</a>';
        } else {
            echo '<a href="/public/login.php">Iniciar Sesión</a>';
        }
        ?>
    </nav>
</header>