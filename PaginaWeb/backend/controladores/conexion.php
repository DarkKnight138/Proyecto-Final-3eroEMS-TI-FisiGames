<?php
$server = "localhost";
$db     = "fisigames";
$user   = "root";
$pass   = "";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->conexionect_errno) {
    die("Conexión fallida: " . $conexion->conexionect_error);
}
?>
