<?php
session_start(); // Inicia sesión para acceder a datos del usuario
include("conexion.php"); // Conecta a la base de datos

if (!isset($_SESSION["usuario_id"])) { // Verifica si el usuario está logueado
    echo "Error: usuario no logueado.";
    exit;
}

$id_usuario = $_SESSION["usuario_id"]; // Obtiene el ID del usuario logueado
$puntos = intval($_POST["puntos"]); // Convierte los puntos recibidos a entero

// Actualiza la puntuación total sumando los nuevos puntos
$sql = "UPDATE cuentas SET puntuacion_total = puntuacion_total + ? WHERE id_cuenta = ?";
$stmt = $conexion->prepare($sql); // Prepara la consulta
$stmt->bind_param("ii", $puntos, $id_usuario); // Vincula los parámetros

if ($stmt->execute()) { // Ejecuta la consulta
    echo "¡Ganaste 20 puntos! "; // Mensaje de éxito
} else {
    echo "Error al actualizar puntos: " . $stmt->error; // Mensaje de error
}

$stmt->close(); // Cierra la sentencia
$conexion->close(); // Cierra la conexión a la base de datos
?>
