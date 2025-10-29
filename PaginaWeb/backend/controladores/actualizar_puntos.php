<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    echo "Error: usuario no logueado.";
    exit;
}

$id_usuario = $_SESSION["usuario_id"];
$puntos = intval($_POST["puntos"]);

$sql = "UPDATE usuarios SET puntos = puntos + ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $puntos, $id_usuario);

if ($stmt->execute()) {
    echo "Puntos actualizados correctamente";
} else {
    echo "Error al actualizar puntos: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
