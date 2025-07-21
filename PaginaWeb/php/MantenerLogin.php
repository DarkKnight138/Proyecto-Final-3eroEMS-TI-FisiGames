<?php
session_start(); // inicia sesión para poder usar datos del usuario

require 'conexion.php'; // se conecta a la base de datos

// si ya hay una sesión iniciada
if (isset($_SESSION['nombre'])) {
    header('Location: dashboard.php'); // lo manda a la pagina principal
    exit(); // corta la ejecución del código para que no siga mostrando nada
}

// si llegó a esta página con un error
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "Error: usuario o contraseña incorrectos\n"; // muestra un mensaje de error
}
?>
