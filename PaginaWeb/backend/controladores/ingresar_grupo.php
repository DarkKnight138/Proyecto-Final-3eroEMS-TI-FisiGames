<?php
header('Content-Type: application/json');
require 'conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_grupo = trim($_POST['nombre_grupo']);
    $contraseña = trim($_POST['contraseña']);
    $id_usuario = intval($_POST['id_usuario']); // ID del usuario logueado

    // Verificar grupo
    $stmt = $conn->prepare("SELECT id_grupo FROM grupos WHERE nombre = ? AND contraseña = ?");
    $stmt->bind_param("ss", $nombre_grupo, $contraseña);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $grupo = $resultado->fetch_assoc();
        $id_grupo = $grupo['id_grupo'];

        // Verificar si ya pertenece
        $check = $conn->prepare("SELECT * FROM pertenece_a WHERE id_cuenta = ? AND id_grupo = ?");
        $check->bind_param("ii", $id_usuario, $id_grupo);
        $check->execute();
        $res_check = $check->get_result();

        if ($res_check->num_rows == 0) {
            // Insertar relación
            $insert = $conn->prepare("INSERT INTO pertenece_a (id_cuenta, id_grupo) VALUES (?, ?)");
            $insert->bind_param("ii", $id_usuario, $id_grupo);
            $insert->execute();
        }

        $response = ["status" => "ok", "message" => "Te uniste al grupo '$nombre_grupo'."];
    } else {
        $response = ["status" => "error", "message" => "Grupo o contraseña incorrectos."];
    }
    $stmt->close();
}

//echo json_encode($response);
?>
