<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    echo "Error: usuario no logueado.";
    exit;
}

$id_usuario = $_SESSION["usuario_id"];
$puntos = intval($_POST["puntos"]);

// 💥 Actualiza la puntuación total en la tabla cuentas
$sql = "UPDATE cuentas SET puntuacion_total = puntuacion_total + ? WHERE id_cuenta = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $puntos, $id_usuario);

if ($stmt->execute()) {
    echo "¡Ganaste 20 puntos! 🎉";
} else {
    echo "Error al actualizar puntos: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
