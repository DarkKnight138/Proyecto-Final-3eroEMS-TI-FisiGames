<?php
$server = "localhost";
$db     = "fisigames";
$user   = "root";
$pass   = "";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) { 
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
    