<?php

session_start();

$usuario = $_SESSION['user'] ?? null;

if (!$usuario || !($usuario['es_admin'] ?? false)) {
    
    header('Location: /index.php');
    exit; 
}

?>