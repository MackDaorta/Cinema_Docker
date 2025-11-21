<?php
//Logica de seguridad
session_start();

$usuario = $_SESSION['user'] ?? null;

// Verifica si esta logeado ademas que debe ser admin
if (!$usuario || !($usuario['es_admin'] ?? false)) {
    // Si incumple cualquiera de las dos condiciones, sera redirigido al incio
    header('Location: /index.php');
    exit; // detiene la ejecucion del panel para evitar filtraciones
}

?>