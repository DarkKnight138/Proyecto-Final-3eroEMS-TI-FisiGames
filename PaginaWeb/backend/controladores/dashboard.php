<?php
session_start(); // inicia la sesión para acceder a los datos guardados del usuario

require 'conexion.php'; // conecta con la base de datos

// verifica si NO hay una sesión activa con el nombre del usuario
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php'); // si no hay sesión, lo manda a la página de login
    exit(); // termina el script para que no siga ejecutando nada
}

// si hay sesión, muestra un mensaje con el nombre del usuario logueado
echo "Conectado como: " . $_SESSION['nombre'];
?>