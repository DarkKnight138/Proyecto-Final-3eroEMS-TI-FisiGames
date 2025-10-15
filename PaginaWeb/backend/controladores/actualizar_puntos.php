<?php
session_start();
require 'conexion.php';

// Verificamos si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo "No estás logueado";
    exit;
}

// Tomamos el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario_id'];
$puntos = isset($_POST['puntos']) ? intval($_POST['puntos']) : 0;

// Actualizamos los puntos solo si se envió un valor positivo
if ($puntos > 0) {
    $sql = "UPDATE cuentas SET puntuacion_total = IFNULL(puntuacion_total, 0) + ? WHERE id_cuenta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $puntos, $usuario_id);

    if ($stmt->execute()) {
        echo "Puntos actualizados correctamente";
    } else {
        echo "Error al actualizar puntos: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No se sumaron puntos";
}

$conexion->close();
?>
