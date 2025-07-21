<?php
session_start(); // inicia la sesión para poder acceder a los datos guardados

session_unset(); // borra todas las variables de sesión

session_destroy(); // destruye la sesión

// después de cerrar la sesión, redirige al login
header('Location: login.php');
exit(); // corta la ejecución del script para asegurarse que no se muestre más nada
?>