<?php 
session_start();
$usuario = $_SESSION["user"]?? null;
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera - Cinemark</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="{% static 'css/cartelera.css' %}">
<?php
include 'header.php'; 
?>
<body>
    <main>
        <h2 class="titulo">Películas en Cartelera</h2>
        <div class="cartelera-grid">
            
            <div id="peliculas-container" class="peliculas">
                
                


<?php
include 'footer.php';
?>