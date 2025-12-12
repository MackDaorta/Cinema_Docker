<?php

session_start();   // Inicia o reanuda la sesión del usuario actual

$usuario = $_SESSION['user'] ?? null;  // Obtiene los datos del usuario de la sesión o null si no existe

// Verifica si no hay sesión iniciada o si el usuario no es administrador
if (!$usuario || !($usuario['es_admin'] ?? false)) {
    
    header('Location: /index.php');  // Redirige al inicio si no cumple las condiciones
    exit; 
}

?>